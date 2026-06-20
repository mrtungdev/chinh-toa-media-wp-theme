# Chính sách bảo mật

## Báo cáo lỗ hổng

Nếu bạn phát hiện lỗ hổng bảo mật, vui lòng **báo riêng tư trước** (không mở issue
công khai cho tới khi có bản vá):

- Mở [GitHub Security Advisory](https://github.com/mrtungdev/chinh-toa-media-wp-theme/security/advisories/new), hoặc
- Liên hệ tác giả qua kênh ghi trong hồ sơ repo.

Vui lòng kèm: mô tả, bước tái hiện, phiên bản theme/WordPress/PHP, và mức ảnh hưởng.

## Phạm vi

Theme này là phần mềm phía máy chủ chạy trong WordPress. Các loại lỗi quan tâm:
XSS (stored/reflected), thiếu kiểm `nonce`/quyền (`current_user_can`), lộ dữ liệu,
chèn SQL, đọc/ghi file ngoài ý muốn.

## Nguyên tắc bảo mật trong mã nguồn

- Mọi output ra HTML phải được escape (`esc_html/esc_attr/esc_url/wp_kses_post`).
- Mọi AJAX/endpoint và thao tác lưu dữ liệu phải kiểm `nonce` + quyền.
- Không tin dữ liệu client; trang chủ render section theo cấu hình lưu ở server,
  client chỉ gửi chỉ số (index).
- Trường script đầu/cuối trang chỉ dành cho quản trị viên (`manage_options`).

Xem thêm các vấn đề đã rà soát và xử lý: [docs/AUDIT.md](docs/AUDIT.md).
