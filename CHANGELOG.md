# Changelog

Tất cả thay đổi đáng chú ý của theme được ghi tại đây. Định dạng theo
[Keep a Changelog](https://keepachangelog.com/), phiên bản theo [SemVer](https://semver.org/lang/vi/).

## [1.2.0] — chưa phát hành

### Thêm
- Hộp **“Tuỳ chỉnh giao diện trang”** ở màn hình soạn Trang (page): bật/tắt thanh bên và
  vị trí, ảnh đại diện, menu điều hướng (breadcrumb), tiêu đề. Cùng khoá meta
  `ct_options.post_custom` với bài viết; không có ô Tác giả / Ngày & lượt xem.
- **Khối tiêu đề trang chuyên mục** (`archive.php`): breadcrumb, `<h1>` tên chuyên mục, mô tả
  chuyên mục. Trang **tìm kiếm**: `<h1>` “Kết quả tìm kiếm cho “…””, số bài tìm thấy, ô tìm kiếm.
- `template-parts/category/content-none.php`: thông báo tiếng Việt khi chuyên mục chưa có bài
  hoặc tìm kiếm không có kết quả (trước đây trang trống trơn vì file không tồn tại).
- Trang 404 có ô tìm kiếm và link về trang chủ; link “Chuyển đến nội dung” cho bàn phím.

### SEO
- **Khối trang chủ render phía server** (`page-homepage.php` gọi `homepage_tabs_template_get()`)
  thay vì mỗi khối 1 request admin-ajax: nội dung và link bài có ngay trong HTML cho Google/
  Facebook/Zalo, không còn lỗi “Có lỗi trong quá trình tải dữ liệu” khi nonce trong trang đã
  page-cache hết hạn. Endpoint AJAX cũ vẫn giữ để tương thích.
- Trang chủ có `<h1>` (ẩn: tên site – khẩu hiệu); 404 dùng `<h1>`; tiêu đề thẻ bài là `<h3>`
  (giao diện giữ nguyên).
- `header.php`: bỏ `X-UA-Compatible`, Content-Type trùng, `og:image:alt` lẻ; khi Yoast SEO bật
  thì không in robots/keywords/fb:profile_id/og:type/og:locale của theme (trước bị trùng —
  VD og:locale vi_VN và en_US cùng lúc). Khôi phục `rel="canonical"` (khi không có Yoast) và
  link RSS chính.
- Bỏ tiền tố “Chuyên mục:” khỏi `get_the_archive_title()`. Phân trang dùng `<nav aria-label>`.
- Nút chia sẻ có `aria-label`; vùng nội dung chính là `<main id="site-content">`.

### Tốc độ
- **Ảnh đúng cỡ:** `getPostImage($id, $size = 'medium')` — thẻ bài dùng bản 690px, widget 320px,
  tin nổi bật 960px (trước đây luôn trả ảnh gốc full-size).
- **Ảnh đầu bài/trang (LCP)** tải ngay với `fetchpriority="high"`, srcset, width/height thay vì
  lazy-load bằng JS (hết giật trang khi ảnh hiện).
- JS của theme tải `defer`; Swipebox chỉ nạp ở trang bài/trang; bỏ jQuery Migrate ở trang ngoài;
  bỏ emoji của WordPress.
- Trang chủ (template Trang Chủ), chuyên mục, tìm kiếm không nạp CSS khối Gutenberg
  (`wp-block-library`, ~140 KB).
- Font: Lobster chỉ nạp khi bật box “5 phút”; Lora chỉ còn 2 kiểu đang dùng và chỉ nạp khi thẻ
  Lời Chúa thực sự hiển thị (trước nạp mọi trang); preconnect `fonts.gstatic.com`.
- Danh sách bài đọc từ transient được nạp sẵn cache meta/chuyên mục/ảnh (`ct_prime_post_caches()`)
  → hết truy vấn lặp cho từng thẻ bài. Video oEmbed (bài loại Media) được cache 1 tuần.

### Thay đổi
- **Trang tĩnh (`page.php`)** dùng cùng khung với trang bài viết: thẻ nền trắng bo góc,
  breadcrumb, tiêu đề, nội dung dùng chung CSS `.post-content` (trích dẫn, danh sách, ảnh…),
  thanh bên widget “Bài viết” (`ct-widget-single`) theo Thiết lập giao diện → Bài viết, link
  “Sửa trang”. Trước đây nội dung nằm thẳng trên nền trang, không lề, không thanh bên.
- Footer luôn nằm sát đáy màn hình khi nội dung ngắn (trang Liên hệ, 404…), không còn lộ
  khoảng nền trống bên dưới. CSS mới `assets/css/layout.css` (luôn nạp).

### Sửa
- `sidebar.php` kiểm tra nhầm sidebar `dynamic_sidebar` (không tồn tại) nên không bao giờ
  hiện widget; nay dùng `ct-widget-homepage` (trang danh sách bài `index.php`).
- Tin Hot sắp xếp lượt xem theo chuỗi (“9” > “100”); nay `meta_value_num`.
- `archive.php` lỗi notice ở trang lưu trữ không phải chuyên mục (tác giả, ngày).
- Ô tìm kiếm dùng `home_url()` (trước `site_url()` — sai khi WordPress cài ở thư mục con) và giữ
  từ khoá đang tìm.
- `bootstrap_pagination()`: tham số nullable tường minh (cảnh báo PHP 8.4).
- Site đã đặt **Site Icon** thì dùng icon đó thay bộ favicon mặc định của theme.

### Công cụ (ngoài gói theme)
- `tools/setup-site-vi.php`: thiết lập chuẩn cho site Công giáo tiếng Việt — giờ VN, tắt bình
  luận, Yoast (bỏ `/category/`, tiêu đề/breadcrumb tiếng Việt, tổ chức + logo, ảnh chia sẻ, tiêu
  đề + mô tả trang chủ, tắt trang tác giả/ngày), chuyển CSS riêng của site lên `<head>`, sửa link
  cứng `/category/`. Chạy lại được, không xoá nội dung.

## [1.1.0] — 2026-10-06

### Thêm
- **Header kiểu “Logo + khẩu hiệu”** (`header_data.c_brand`): logo trái, câu khẩu
  hiệu + trích dẫn phải, nền gradient 2 màu. Mặc định vẫn là “Tự nhập nội dung”.
- **Kiểu menu c4 “Thanh chia đều”**: thanh màu đặc, các mục chia đều bề ngang, mục đang
  chọn/hover có gạch nhấn theo màu nhấn thanh menu; menu con dạng thẻ trắng bo góc.
- **Lời Chúa hôm nay**: khối trang chủ `temp7` tự hiện bài có ngày đăng là hôm nay
  (trong chuyên mục chọn); 3 ô mới trong metabox loại “Lời Chúa”: ngày phụng vụ,
  lễ/kính thánh, đoạn Tin Mừng (`_ct_lc_day_title`, `_ct_lc_saint`, `_ct_lc_gospel_ref`).
- **Widget “Lịch Lời Chúa”**: lịch tháng, bấm ngày → đổi bài trong khối Lời Chúa hôm
  nay qua AJAX (trang khác: mở bài); nút ‹ › đổi tháng. AJAX công khai chỉ đọc
  `ct_daily_word`, `ct_daily_calendar`.
- **Khối trang chủ `temp8` “Lưới ảnh mosaic”**: bài đầu ô lớn 2×2, các bài sau ô nhỏ.
- **Bài loại “Lời Chúa” không có ảnh đại diện** hiện **câu Lời Chúa** (câu ghi nhớ + trích
  dẫn) ở chỗ ảnh — thẻ bài, thẻ ảnh, lưới mosaic, trang chuyên mục/tìm kiếm — thay cho ảnh
  trống; trang chi tiết chỉ còn thẻ câu ghi nhớ (bỏ ảnh giữ chỗ). Có ảnh đại diện thì vẫn
  dùng ảnh. Hàm `ct_loichua_thumb_fallback_html()`, CSS `assets/css/loichua-thumb.css`.
- Bài hướng dẫn “Lời Chúa hôm nay & Lịch” trong trang Giới thiệu của theme.

### Thay đổi
- **Menu trên điện thoại/tablet (mọi kiểu menu c1–c4)** làm lại: thanh trên gọn (tên site chữ
  đậm, nút ☰ vẽ bằng CSS → ✕ khi mở, thay ảnh chữ “MENU”); menu mở dạng **lớp phủ** — thẻ
  trắng bo góc trên nền mờ, chữ 1.1rem, mục đang xem có gạch “—” màu nhấn + nền xám nhạt;
  **menu con thu gọn**, nút mũi tên bên phải mục cha để mở/thu (bấm chữ vẫn mở trang), nhánh
  chứa trang đang xem tự mở; chạm nền mờ hoặc Esc để đóng; khoá cuộn trang khi mở.
  CSS mới `assets/css/nav-menu.css` (luôn nạp), JS trong `ct-media.js`.
- **Menu desktop:** mũi tên ▾ / › cho mục có menu con; mở menu con bằng bàn phím (Tab);
  menu con cấp 3+ của mục sát mép phải tự mở sang trái (không tràn màn hình).
- **Widget “Danh Sách Bài Viết” – kiểu số thứ tự** gọn hơn: cột số hẹp (trước rộng ~50px vì
  `min-width: 1.7em` theo cỡ chữ số lớn), số chữ đậm căn theo dòng đầu tiêu đề (bỏ chữ số
  kiểu cũ của Georgia bị lệch dòng), top 3 màu nhấn – hạng sau màu xám, tiêu đề tối đa 2 dòng.
  Tuỳ chọn mới **“Hiện lượt xem / ngày đăng”** (mặc định tắt). Markup cũ giữ nguyên; danh
  sách có ảnh thu nhỏ thêm class `ct-postlist--with-thumb`.

### Sửa
- Menu mobile không hiện dạng lớp phủ mà xổ ra đẩy nội dung xuống: `ct-media.js` bật/tắt
  `.is-active` theo class `collapsed` lúc click, nhưng Bootstrap 5 xử lý click ở pha capture
  nên đổi class trước → logic đảo ngược. Nay theo sự kiện `show/hidden.bs.collapse`.
- Khi đăng nhập, trên mobile thanh menu che ~46px đầu trang (theme ẩn admin bar nhưng vẫn
  kéo `body` lên). Nay trả lề về 0 dưới 992px.
- Nút “Xem thêm” của khối trang chủ không mở tab mới dù đã chọn “Mở tab mới”
  (`readmore_blank` lưu `'y'` nhưng so sánh với `1`).
- **“Mã chèn vào &lt;head&gt;”** (Nâng cao → Kỹ thuật) không bao giờ được in ra: gắn nhầm vào
  hook không tồn tại `wp_header`. Nay dùng `wp_head`.
- Header **Ảnh banner rộng**: ô **“Mô tả ảnh”** nay thành `alt` của banner (trống → tên
  website); tắt **“Mở liên kết ở tab mới”** nay mở cùng tab (trước ghi `target="self"` nên
  trình duyệt vẫn mở cửa sổ mới); bật thì thêm `rel="noopener"`.
- Thanh thông báo: bật **“Đang phát trực tiếp”** nay hiện nhãn đỏ “● Trực tiếp” cạnh tiêu
  đề (trước không có tác dụng). Ô “Nội dung” (và khối “Tĩnh / HTML”) nay giữ được
  `<iframe>` (danh sách thuộc tính an toàn) để nhúng video/livestream —
  `ct_kses_rich_text()`, filter `ct_rich_text_allowed_html`.
- Khối Nổi bật: **“Tin Hot — Trong vòng (ngày)”** nay lọc đúng số ngày gần đây (trước bị bỏ
  qua, luôn lấy “tuần này”); để trống vẫn là tuần này.
- Box **“5 phút”**: chuyên mục nguồn có ID ≥ 10 bị đọc sai (chỉ lấy ký tự đầu của chuỗi
  `"12,15"`). Box nay chỉ hiện ở đúng nơi bật (Trang chủ / Chuyên mục / Bài viết), không
  còn tự hiện ở trang tìm kiếm.
- Khối trang chủ: hai khối (hoặc hai tab) **trùng tiêu đề / cùng để trống tiêu đề hiện cùng
  một danh sách bài** do cache theo tiêu đề. Nay cache theo tham số truy vấn; lưu Thiết lập
  giao diện hoặc thêm / đổi tên / xoá chuyên mục cũng xoá cache bài để trang chủ cập nhật ngay.
- Dòng hướng dẫn ở hộp **Tuỳ chỉnh giao diện chuyên mục** ghi đúng tên trang “Thiết lập giao diện”
  (trước ghi tên cũ “Tuỳ Chọn Giao Diện”).
- Khối **“Tabs chuyên mục”**: không có tiêu đề khối thì mất cả thanh tab — nay tab đầu lấy
  tên chuyên mục; tab để trống tên cũng lấy tên chuyên mục; tab có nhiều chuyên mục không
  bấm được (class chứa dấu phẩy).
- **“Số bài hiển thị”** của khối bắt đầu từ 1 (0/trống → 6 bài, trước ra 5 bài).
- Trang **Thiết lập giao diện**: sau khi lưu hiện thông báo “Đã lưu thay đổi.” và mở lại
  đúng tab đang làm; hỗ trợ liên kết thẳng tới tab, VD `#noidung/homepage`.
- Bảng Điều Khiển: mỗi thẻ chỉ hiện với người có quyền dùng (Biên tập viên không thấy “Thiết lập giao diện”; Người đăng ký không thấy “Viết bài mới”, “Chuyên mục”, “Hướng dẫn sử dụng”).
- Khối trang chủ → **Màu sắc khối**: ba nút “Chọn màu” mất nhãn (bộ chọn màu của WordPress ẩn
  luôn `<label>` bọc ô nhập). Nay nhãn “Màu nền / Màu chữ / Màu nhấn” đứng riêng phía trên nút.
- Ô **“Màu nhấn thanh menu”** ghi rõ áp dụng cho kiểu menu 3 và 4.
- Ẩn nhóm **“Biểu tượng & màu sắc”** ở form chuyên mục (giao diện chưa hiển thị các giá trị
  này; dữ liệu cũ vẫn giữ khi lưu; bật lại bằng filter `ct_category_icon_fields`).
- Ẩn nút nổi **“Giờ Thánh Lễ”** (trỏ tới `#mass-times-widget` không tồn tại; bật lại bằng
  filter `ct_show_mass_times_button`).

### Tài liệu
- Viết lại toàn bộ trang **Hướng dẫn sử dụng** trong quản trị theo từng bước cho người
  không rành công nghệ, có ảnh minh hoạ (`assets/imgs/guide/*.webp`, token `{{img}}`
  trong tệp `.md`).

## [1.0.0] — 2026-06-20

Bản phát hành **mã nguồn mở đầu tiên** (giấy phép MIT). Theme bắt nguồn từ dự án
[CongGiaoWordpressTheme](https://github.com/mrtungdev/CongGiaoWordpressTheme), được
viết lại và tối ưu toàn diện về bảo mật, tốc độ, chất lượng mã và i18n trước khi mở mã.