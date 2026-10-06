# Danh sách hoàn nguyên website 5plc

Chỉ dùng danh sách này sau khi người dùng đã duyệt toàn bộ ảnh. Trước thao tác xóa dữ liệu hoặc tài khoản mẫu, phải xin xác nhận ngay tại thời điểm thực hiện.

## Thay đổi được giữ lại

- [x] Ngôn ngữ website là Tiếng Việt
- [x] Múi giờ website là Ho Chi Minh

## Trạng thái phải giữ nguyên

- [ ] Bốn bài viết gốc còn nguyên tiêu đề, nội dung, trạng thái và chuyên mục
- [ ] Tám chuyên mục gốc còn nguyên tên và thiết lập
- [ ] Bốn trang gốc còn nguyên nội dung và trạng thái
- [ ] `Trang Chủ` vẫn là trang chủ tĩnh
- [ ] `Privacy Policy` vẫn là trang Chính sách riêng tư
- [ ] `Menu 1` vẫn gán vào `Menu Chính` và có đúng năm mục ban đầu
- [ ] Chức năng tự động thêm trang vào menu vẫn tắt
- [ ] Thanh bên trang chủ vẫn ở bên phải
- [ ] Widget trang chủ trở về đúng thứ tự ban đầu
- [ ] Cột widget Footer vẫn tắt
- [ ] Hai khối trang chủ `TC` và `A` trở về đúng cấu hình ban đầu
- [ ] Khối Nổi bật, box 5 phút và dải thông báo vẫn tắt
- [ ] Các giá trị Header, Footer, màu sắc, bố cục chuyên mục và bài viết trở về trạng thái gốc
- [ ] Danh sách plugin và trạng thái bật tắt không thay đổi

## Dữ liệu mẫu cần xóa sau khi duyệt

- [ ] Bài viết nháp ID 40; bản sửa đổi phụ thuộc ID 41 sẽ được WordPress xóa cùng bài cha
- [ ] Trang Gutenberg nháp ID 43; bản sửa đổi phụ thuộc ID 44 sẽ được WordPress xóa cùng trang cha
- [ ] Trang Elementor nháp ID 45; bản sửa đổi phụ thuộc ID 46 sẽ được WordPress xóa cùng trang cha
- [ ] Chuyên mục ID 11; hiện có 0 bài viết
- [ ] Media ID 42; tệp gốc `2026/09/screenshot.png` và năm kích thước sinh kèm
- [ ] Tài khoản Biên tập viên tạm ID 2, đăng nhập `tai_lieu_editor`; tài khoản chỉ sở hữu ba bản nháp và ba bản sửa đổi mẫu nêu trên
- [x] Không có mục menu hoặc widget tạm

Lần quét toàn bộ trước hoàn nguyên không thấy mục menu mẫu. Không có dữ liệu mang tiền tố `[TÀI LIỆU MẪU]` nào ngoài các ID nêu trên.

## Kiểm tra cuối

- [x] Chạy `scripts/cleanup_sample_5plc.php` ở chế độ mặc định `dry-run`; kiểm tra đạt và không ghi dữ liệu
- [ ] Chỉ chuyển script sang `apply` sau khi người dùng xác nhận ngay trước thao tác
- [ ] Không còn dữ liệu có tiền tố `[TÀI LIỆU MẪU]`
- [ ] Không còn tài khoản tạm
- [ ] Chạy lại `scripts/fingerprint_5plc.php` và đối chiếu đủ chín dấu vân tay chuẩn hóa
- [ ] Trang chủ ngoài website hiển thị như trước khi chụp ảnh
- [ ] Không có plugin hoặc theme mới
- [ ] Không có thiết lập Nâng cao nào bị thay đổi
