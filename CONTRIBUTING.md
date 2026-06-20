# Đóng góp cho Chính Tòa Media

Cảm ơn bạn quan tâm! Đây là theme cho giáo xứ Công giáo nên ngoài kỹ thuật, vui
lòng lưu ý **dùng từ ngữ Công giáo chính xác và trang trọng**.

## Thiết lập môi trường

- WordPress 5.4+, PHP 7.4+ (khuyến nghị 8.1–8.3).
- `pnpm install` rồi `pnpm run build` (xem [BUILD.md](BUILD.md)).
- Kiểm thử nhanh logic options: `php tests/*.php`.

## Quy ước mã nguồn

- **Bảo mật là số 1:** luôn escape khi xuất ra HTML — `esc_html()`, `esc_attr()`,
  `esc_url()`, `wp_kses_post()`. Luôn kiểm `nonce` + `current_user_can()` cho
  AJAX/lưu dữ liệu. Không tin dữ liệu từ `$_GET/$_POST/$_REQUEST`.
- **i18n:** mọi chuỗi hiển thị bọc `__()/_e()/esc_html_e()…` với text domain
  `'chinhtoa'`. Thêm `/* translators: … */` cho thuật ngữ phụng vụ.
- **Đặt tên:** ưu tiên tiền tố `ct_` cho hàm mới. (Một số hàm cũ còn tiền tố
  `bb_`/`gen_`/`home_` — sẽ chuẩn hóa dần, có alias để không phá tương thích.)
- **Coding standards:** WordPress Coding Standards. Có sẵn `phpcs.xml`:
  `phpcs --standard=phpcs.xml chinhtoa/` (cần cài PHPCS + WPCS).
- **Không commit** file build/nhị phân: `dist/`, `*.zip`, `*.map`, `node_modules/`
  (đã có trong `.gitignore`).

## Thuật ngữ Công giáo (tiếng Việt)

Dùng nhất quán, đúng chính tả hiện hành: **Lời Chúa**, **Thánh Lễ**, **Giờ Thánh Lễ**,
**Suy niệm**, **Tin Mừng**, **Giáo Xứ**, **Giáo Phận**, **Nhà thờ Chính Tòa**
(dùng "Tòa", không "Toà"). Khi không chắc, hỏi trong issue trước khi đổi.

## Quy trình Pull Request

1. Tạo nhánh: `feature/<mô-tả>` hoặc `fix/<mô-tả>`.
2. Commit nhỏ, rõ nghĩa.
3. Chạy `pnpm run build` (nếu đụng CSS) và `php tests/*.php` trước khi mở PR.
4. Mô tả thay đổi + ảnh chụp trước/sau nếu đụng giao diện.
5. Với thay đổi giao diện, vui lòng kiểm thử trực quan trên trình duyệt.

## Báo lỗi

Mở issue tại <https://github.com/mrtungdev/chinh-toa-media-wp-theme/issues>. Lỗi **bảo mật**: xem
[SECURITY.md](SECURITY.md), vui lòng báo riêng tư trước khi công khai.
