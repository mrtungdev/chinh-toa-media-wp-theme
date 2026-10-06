# Biên bản khảo sát website 5plc

Ngày khảo sát: 05 tháng 09 năm 2026

## Mục đích

Biên bản này ghi lại cấu trúc thật của `5plc` trước khi tạo dữ liệu minh họa. Thông tin dưới đây là căn cứ để lập mục lục, kiểm tra độ chính xác và hoàn nguyên website sau khi chụp ảnh.

## Môi trường

- Website chạy cục bộ bằng Local tại `http://5plc.local`.
- WordPress phiên bản 7.1, không dùng multisite.
- PHP 8.2.29, MySQL 8.4.0 và nginx.
- Giao diện đang kích hoạt là Chính Tòa Media phiên bản 1.0.0.
- Thư mục giao diện đang chạy là liên kết tới mã nguồn `chinhtoa` trong workspace này.
- WordPress dùng trình soạn thảo khối Gutenberg cho nội dung hiện tại.
- Giao diện ép màn hình widget về kiểu cổ điển.

## Thiết lập chung

- Tên website: `5plc`.
- Khẩu hiệu đang để trống.
- Trang chủ hiển thị một trang tĩnh có tên `Trang Chủ`.
- Chưa chỉ định trang riêng cho danh sách bài viết.
- Ngôn ngữ ban đầu là English United States. Theo quyết định đã duyệt, ngôn ngữ đã đổi sang Tiếng Việt ngày 05 tháng 09 năm 2026.
- Múi giờ ban đầu là UTC+0. Theo quyết định đã duyệt, múi giờ đã đổi sang Ho Chi Minh ngày 05 tháng 09 năm 2026.
- Định dạng ngày và giờ chưa được thay đổi trong đợt chuẩn bị này.

## Nội dung hiện có cần giữ nguyên

### Bài viết

Website có 4 bài viết đã xuất bản:

1. Lạy Cha chúng con ở trên trời
2. Hãy yêu thương kẻ thù
3. Đấng thấu suốt nơi kín đáo
4. Kho tàng của anh em ở đâu

Không dùng các bài viết này để thử thao tác xóa, sửa hoặc đổi chuyên mục.

### Chuyên mục

Website có 8 chuyên mục:

1. Audio
2. Hạnh Các Thánh
3. Học hỏi
4. Suy niệm
5. Tản Mạn
6. Tất cả
7. Từ Vựng Công Giáo
8. Video lời Chúa

`Tất cả` là chuyên mục mặc định và không được xóa hoặc đổi tên trong quá trình làm tài liệu.

### Trang

Website có 4 trang:

1. Newsletter, đã xuất bản
2. Privacy Policy, bản nháp và đang được chọn làm trang Chính sách riêng tư
3. Sample Page, đã xuất bản
4. Trang Chủ, đã xuất bản và đang được chọn làm trang chủ

Không chỉnh sửa hoặc xóa bốn trang này để tạo ví dụ.

### Thư viện

Thư viện Media đang trống tại thời điểm khảo sát.

### Tài khoản

Website ban đầu có 1 tài khoản Quản lý đang sở hữu 4 bài viết. Không ghi lại email hoặc mật khẩu trong bộ tài liệu. Ngày 05 tháng 09 năm 2026 đã tạo tài khoản Biên tập viên tạm `tai_lieu_editor` để kiểm tra quyền và chụp ảnh; tài khoản này phải được xóa khi hoàn nguyên.

## Menu và widget

- Menu hiện tại có tên `Menu 1` và được gán vào vị trí `Menu Chính`.
- Chức năng tự động thêm trang mới vào menu đang tắt.
- Menu gồm `Trang Chủ`, `Suy niệm`, `Tản Mạn`, `Video lời Chúa` và `Từ Vựng Công Giáo`.
- Thanh bên trang chủ đang bật ở bên phải.
- Các widget đang thấy trên trang chủ gồm ô tìm kiếm, `BÀI XEM NHIỀU` và `Audio mới nhất`.
- Các cột widget cuối trang đang tắt.

## Cấu hình giao diện Chính Tòa Media

- Nhóm giao diện gồm Màu sắc & hiển thị, Header và Footer.
- Nhóm Trang chủ và Bài viết gồm Trang chủ, các khối nội dung trang chủ và bố cục mặc định của chuyên mục và bài viết.
- Nhóm Tiện ích có dải thông báo theo khoảng thời gian.
- Nhóm Nâng cao chứa mã theo dõi, mã chèn đầu hoặc cuối trang và shortcode bảng điều khiển.
- Màu giao diện hiện chọn biến thể vàng; thanh menu dùng kiểu đầu tiên với màu nền và màu nhấn riêng.
- Header đang dùng chế độ tự nhập nội dung với hai dòng chữ `Suy niệm Tin Mừng mỗi ngày` và `5 Phút Cho Lời Chúa`.
- Footer chưa có nội dung riêng và chưa bật cột widget.
- Khối Nổi bật và box 5 phút đang tắt.
- Trang chủ có hai khối nội dung mang tiêu đề `TC` và `A`. Khối đầu dùng mẫu danh sách 1, lấy tất cả chuyên mục và hiển thị 6 bài; khối sau dùng mẫu 4.
- Bố cục chung của trang chuyên mục dùng 2 cột, kiểu hiển thị 1 và thanh bên phải.
- Bố cục chung của bài viết dùng thanh bên phải.
- Dải thông báo đang tắt.
- Khu vực Nâng cao có mã tải phông chữ và CSS cho Header. Khu vực này được xếp mức đỏ và không dùng làm bài thực hành cho Biên tập viên.

## Chức năng riêng đã xác minh

### Phân loại bài viết

Giao diện không đăng ký Custom Post Type đang sử dụng. Nội dung đặc biệt vẫn là Bài viết thông thường và được điều khiển bằng hộp `Phân loại bài viết`:

- `Mặc định (bài viết thường)`.
- `Lời Chúa (câu ghi nhớ)`: hiện thêm trường `Câu Lời Chúa` và `Trích dẫn`.
- `Video (audio) Lời Chúa`: hiện thêm trường `Link video/audio`.

### Tùy chỉnh bài viết và chuyên mục

- Mỗi bài viết có thể bật bố cục riêng, chọn vị trí thanh bên và bật hoặc tắt ảnh đại diện, breadcrumb, tiêu đề, tác giả, ngày và lượt xem.
- Mỗi chuyên mục có thể bật bố cục riêng, chọn 1 đến 4 cột, kiểu hiển thị, thanh bên, ảnh, mô tả, ngày và lượt xem.
- Chuyên mục còn có thể chọn biểu tượng Font Awesome hoặc ảnh tải lên, cùng màu biểu tượng, màu nền và màu chữ.

### Khối và widget riêng

- Khối Gutenberg và widget `Lời Chúa: Câu ghi nhớ` hỗ trợ dữ liệu nhập tay hoặc lấy động từ bài viết.
- Widget `Danh Sách Bài Viết` hỗ trợ kiểu đánh số hoặc kiểu audio, chọn chuyên mục, số lượng, cách sắp xếp, ảnh, mô tả và màu sắc.
- `WP-PostViews` là plugin bắt buộc của giao diện và cung cấp số lượt xem.

## Plugin

Plugin đang bật:

- Elementor 4.2.4
- UpdraftPlus 1.26.7
- WP-PostViews 2.0.1
- Yoast SEO 28.4

Plugin đang tắt:

- Newsletter 9.3.5
- Web Accessibility by Pojo 4.1.4

Elementor, Yoast SEO và UpdraftPlus chỉ được đưa vào phụ lục tùy chọn. Không có bằng chứng cho thấy các trang hiện tại được xây bằng Elementor. Hai plugin đang tắt không thuộc phạm vi tài liệu chính.

## Phân loại an toàn

- 🟢 An toàn: xem danh sách, tạo và sửa bản nháp, định dạng nội dung, xem trước, chọn ảnh, cập nhật hồ sơ cá nhân.
- 🟡 Cẩn thận: xuất bản, hẹn giờ, chuyển vào thùng rác, đổi chuyên mục, xóa media, thay menu, widget, Header, Footer hoặc bố cục trang chủ.
- 🔴 Không tự thay đổi: cài hoặc xóa plugin và theme, trình sửa tệp, cấu trúc đường dẫn, mã chèn nâng cao, khôi phục bản sao lưu, cấp quyền Quản lý.

## Ngoài phạm vi đã xác minh

Không tìm thấy chức năng riêng đang hoạt động cho lịch Thánh lễ, sự kiện, linh mục, hội đoàn hoặc Custom Post Type. Không đưa các chủ đề này vào sổ tay nếu website chưa có chức năng thật tương ứng.

Nội dung trang “Hướng dẫn sử dụng” hiện có không được dùng làm nguồn, không được sao chép và không được chỉnh sửa.
