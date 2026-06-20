<?php
/**
 * Tự động cập nhật theme từ GitHub Releases.
 *
 * Khi repo GitHub công khai có một Release mới (tag phiên bản, ví dụ v1.0.1),
 * WordPress sẽ hiển thị thông báo cập nhật và cho phép cập nhật theme 1-chạm —
 * giống theme tải từ WordPress.org.
 *
 * Cách hoạt động:
 *   - Hook `pre_set_site_transient_update_themes`: gọi GitHub API lấy release mới
 *     nhất, so sánh với `Version` trong style.css; nếu mới hơn thì thêm vào danh
 *     sách cập nhật của WordPress (kèm link tải gói `.zip`).
 *   - Hook `upgrader_source_selection`: đổi tên thư mục giải nén từ gói GitHub về
 *     đúng slug theme đang cài, để WordPress cập nhật ĐÚNG CHỖ (không tạo theme mới).
 *   - Kết quả API được cache (transient) để tránh giới hạn tần suất của GitHub.
 *
 * White-label: đổi repo qua filter `ct_github_repo` (trả về '' để tắt cập nhật).
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

class CT_GitHub_Theme_Updater
{
    /** @var string Slug = tên thư mục theme đang chạy (get_stylesheet()). */
    private $slug;

    /** @var WP_Theme */
    private $theme;

    /** @var string "owner/repo" trên GitHub. */
    private $repo;

    /** @var string Khóa transient cache. */
    private $cache_key;

    /** @var int TTL cache (giây). */
    private $cache_ttl = 21600; // 6 giờ

    public function __construct()
    {
        $this->slug      = get_stylesheet();
        $this->theme     = wp_get_theme($this->slug);
        $this->repo      = (string) apply_filters('ct_github_repo', 'mrtungdev/chinh-toa-media-wp-theme');
        $this->cache_key = 'ct_gh_update_' . md5($this->slug . '|' . $this->repo);

        add_filter('pre_set_site_transient_update_themes', array($this, 'check_update'));
        add_filter('upgrader_source_selection', array($this, 'fix_source_dir'), 10, 4);
        add_action('upgrader_process_complete', array($this, 'clear_cache'), 10, 2);
    }

    /**
     * Lấy thông tin release mới nhất từ GitHub (có cache).
     *
     * @return array|null array{version:string, zip:string, url:string} hoặc null.
     */
    private function get_remote_release()
    {
        if (empty($this->repo)) {
            return null;
        }

        $cached = get_transient($this->cache_key);
        if (false !== $cached) {
            return is_array($cached) && !empty($cached) ? $cached : null;
        }

        $url = 'https://api.github.com/repos/' . $this->repo . '/releases/latest';
        $res = wp_remote_get($url, array(
            'timeout' => 10,
            'headers' => array(
                'Accept'     => 'application/vnd.github+json',
                'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . home_url('/'),
            ),
        ));

        if (is_wp_error($res) || 200 !== (int) wp_remote_retrieve_response_code($res)) {
            // Cache "rỗng" ngắn hạn để không spam API khi lỗi/không có release.
            set_transient($this->cache_key, array(), 30 * MINUTE_IN_SECONDS);
            return null;
        }

        $data = json_decode(wp_remote_retrieve_body($res), true);
        if (!is_array($data) || empty($data['tag_name'])) {
            set_transient($this->cache_key, array(), 30 * MINUTE_IN_SECONDS);
            return null;
        }

        $info = array(
            'version' => ltrim((string) $data['tag_name'], 'vV'),
            'zip'     => $this->pick_zip($data),
            'url'     => isset($data['html_url']) ? $data['html_url'] : '',
        );
        set_transient($this->cache_key, $info, $this->cache_ttl);
        return $info;
    }

    /**
     * Chọn URL gói tải: ưu tiên asset .zip đính kèm release; nếu không có thì dùng
     * mã nguồn (zipball) — cả hai đều được `fix_source_dir()` đổi tên thư mục đúng.
     *
     * @param array $data Dữ liệu release từ GitHub.
     * @return string
     */
    private function pick_zip($data)
    {
        if (!empty($data['assets']) && is_array($data['assets'])) {
            foreach ($data['assets'] as $asset) {
                if (!empty($asset['browser_download_url'])
                    && '.zip' === strtolower(substr((string) $asset['name'], -4))) {
                    return $asset['browser_download_url'];
                }
            }
        }
        return isset($data['zipball_url']) ? $data['zipball_url'] : '';
    }

    /**
     * Thêm theme vào danh sách cập nhật của WordPress nếu có phiên bản mới hơn.
     *
     * @param object $transient
     * @return object
     */
    public function check_update($transient)
    {
        if (empty($transient) || empty($transient->checked)) {
            return $transient;
        }

        $remote = $this->get_remote_release();
        $local  = $this->theme->get('Version');

        if ($remote && !empty($remote['version']) && !empty($remote['zip'])
            && version_compare($remote['version'], $local, '>')) {
            $transient->response[$this->slug] = array(
                'theme'       => $this->slug,
                'new_version' => $remote['version'],
                'url'         => $remote['url'],
                'package'     => $remote['zip'],
            );
        } elseif ($remote && !empty($remote['version'])) {
            // Đánh dấu "đã mới nhất" để WordPress không hỏi lại liên tục.
            $transient->no_update[$this->slug] = array(
                'theme'       => $this->slug,
                'new_version' => $local,
                'url'         => $remote['url'],
                'package'     => '',
            );
        }

        return $transient;
    }

    /**
     * Gói tải từ GitHub giải nén ra thư mục KHÔNG trùng tên theme đang cài
     * (ví dụ "owner-repo-<hash>/" hoặc "chinh-toa-media-wp-theme/"). Đổi tên thư
     * mục nguồn về đúng slug để WordPress cập nhật đè lên theme hiện tại.
     *
     * @param string      $source
     * @param string      $remote_source
     * @param WP_Upgrader $upgrader
     * @param array       $args
     * @return string|WP_Error
     */
    public function fix_source_dir($source, $remote_source, $upgrader, $args = array())
    {
        global $wp_filesystem;

        if (empty($args['theme']) || $args['theme'] !== $this->slug) {
            return $source;
        }
        if (!is_object($wp_filesystem)) {
            return $source;
        }

        $desired = trailingslashit($remote_source) . $this->slug . '/';
        if (untrailingslashit($source) === untrailingslashit($desired)) {
            return $source;
        }
        if ($wp_filesystem->move($source, $desired, true)) {
            return $desired;
        }
        return $source;
    }

    /**
     * Xóa cache khi vừa cập nhật theme xong.
     *
     * @param WP_Upgrader $upgrader
     * @param array       $hook_extra
     */
    public function clear_cache($upgrader = null, $hook_extra = null)
    {
        if (empty($hook_extra) || (isset($hook_extra['type']) && 'theme' === $hook_extra['type'])) {
            delete_transient($this->cache_key);
        }
    }
}

add_action('after_setup_theme', function () {
    new CT_GitHub_Theme_Updater();
});
