# Biên bản kiểm thử và nghiệm thu sổ tay

Ngày kiểm tra: 05 tháng 09 năm 2026

## Phạm vi bản nghiệm thu

- Tên tài liệu: **Sổ tay quản trị website WordPress**.
- Phạm vi áp dụng: website dùng giao diện Chính Tòa Media.
- Nguồn nội dung: phần mở đầu, 24 chương và 5 phụ lục tùy chọn; mục lục được lưu riêng.
- Quy mô: 177 bài hướng dẫn, 43 ảnh minh họa và 108 trang A4.
- Môi trường kiểm chứng: WordPress 7.1, Gutenberg và Chính Tòa Media 1.0.0 trên `5plc`.

## Kết quả kiểm tra nguồn

- Mục lục và nguồn chương cùng có đủ 177 mã bài, không trùng và không thiếu.
- Mỗi bài có đủ các mục bắt buộc: Mục đích, Trước khi bắt đầu, Các bước thực hiện, Ảnh minh họa, Kết quả, Lưu ý, Không nên làm và Nếu gặp lỗi.
- Mỗi bài có đúng 2 tình huống xử lý lỗi cụ thể; bộ kiểm tra chấp nhận từ 2 đến 5 tình huống theo yêu cầu gốc.
- Bản Word rút gọn cách hiển thị nhưng không làm mất nguồn kiểm chứng: bỏ câu **Kết quả** hiển nhiên, chỉ giữ tình huống lỗi đặc trưng đầu tiên ở bài thường và giữ thêm lệnh dừng ở bài rủi ro đỏ.
- Tiêu đề, nhãn đối tượng và mức an toàn của cả 177 bài khớp mục lục đã duyệt; các bước thao tác đều được đánh số và bắt đầu từ số 1.
- Không có mục bắt buộc để trống, số thứ tự bước lệch nhãn hoặc từ khóa nội dung tạm như `TODO`, `TBD` và `[SCREENSHOT …]`.
- Các khu vực chỉ dành cho Quản lý được gắn nhãn kỹ thuật và mức an toàn riêng.
- Elementor chỉ còn vai trò nâng cao không bắt buộc: Chương 11 không còn bài Elementor, phần chính chỉ có một cảnh báo không mở nhầm; toàn bộ hướng dẫn và hai ảnh liên quan nằm trong Phụ lục C.
- Trang “Hướng dẫn sử dụng” có sẵn không được dùng làm nguồn và không bị chỉnh sửa.
- Chức năng riêng của theme được đối chiếu với giao diện thật và mã nguồn; không mô tả Custom Post Type không tồn tại.
- Đối chiếu sâu lần cuối đã bổ sung chính xác nền trang, hai kiểu khối Nổi bật, bảy mẫu khối trang chủ, điều kiện Chỉ Admin thấy, các thành phần thẻ bài, giới hạn nút Xem thêm của mẫu Tabs và hai hạn chế hiện tại của Header.

## Kết quả kiểm tra ảnh

- Có 43 ảnh sạch và cả 43 ảnh đều được chèn vào DOCX.
- Ảnh đã cắt theo vùng thao tác, giữ đúng tỷ lệ, dùng khung đỏ và nhãn số xanh.
- Các URL, email, tên tài khoản và giá trị cấu hình riêng cần bảo vệ đã được che.
- OCR cục bộ đã nhận dạng 30.308 ký tự trên 43 ảnh; không phát hiện chuỗi thuộc danh sách riêng tư cần che.
- Các ảnh Header, tùy chỉnh chuyên mục và thanh thông báo được chụp ở trạng thái chưa lưu; trang đã được tải lại ngay sau khi chụp để loại bỏ thay đổi tạm.

## Kết quả kiểm tra DOCX

- Tệp nghiệm thu có SHA-256 `c419c0fa28a35b68adc2d8af60d6879cdecabf895462bd27419a305e7b5aa007` và dung lượng 10.719.444 byte.
- File nén OOXML hợp lệ, không có lỗi dữ liệu nén.
- Trang đúng A4 dọc, lề trái/phải 2 cm và lề trên/dưới 1,8 cm.
- Bản render cuối có 108 trang, được đánh dấu truy cập, không có trang bị PDF đánh dấu là đáng ngờ.
- Kiểm tra trợ năng: 0 lỗi mức cao, 0 lỗi mức trung bình và 0 lỗi mức thấp.
- Cấu trúc tiêu đề: 13 Heading 1, 33 Heading 2 và 177 Heading 3; các nhãn nhỏ được trình bày gọn thay vì tạo 1.416 Heading 4 gây rối.
- Hệ thống chữ dùng duy nhất Arial với sáu cỡ cố định: 26, 18, 15, 12, 11 và 9,5 pt. Không còn cỡ chữ hoặc font đặt trực tiếp trong phần nội dung; tiêu đề Phần, Chương và Bài không còn trộn nhãn nhỏ với tiêu đề lớn.
- Mục lục dùng cùng cỡ 11 pt và màu đen; cấp Phần được phân biệt bằng đậm, cấp Chương bằng thụt lề. Metadata chỉ dùng màu ở chấm báo mức an toàn.
- Điều hướng: 177 bookmark bài học và 40 liên kết nội bộ phần/chương trong mục lục nhanh; không có đích liên kết bị thiếu.
- 43 ảnh đều là ảnh inline, nằm trong khổ trang và có mô tả thay thế.
- Ba bảng dùng cỡ 9,5 pt, hàng tiêu đề nền nhạt, đường lưới xám và kích thước cột cố định; kiểm tra OOXML xác nhận độ rộng bảng, lưới và mọi ô khớp nhau.
- Trường số trang `PAGE` và `NUMPAGES` có trong footer; tài liệu yêu cầu cập nhật trường khi mở.
- Văn bản DOCX không chứa tên miền `5plc.local`, tên đăng nhập mẫu hoặc email tài khoản mẫu.

## Kiểm tra trực quan

- Toàn bộ 108 trang được kiểm tra qua chín contact sheet liên tiếp sau lần dàn trang cuối.
- Các trang 1, 2, 5, 95 và 108 được kiểm tra thêm ở kích thước 100% để xác nhận bìa, mục lục, bài có ảnh, bảng dài và trang kết thúc.
- Bìa không còn header, footer hoặc đường kẻ trang trí; mục lục một trang chỉ liệt kê Phần và Chương. Tiêu đề bài, nhãn đối tượng và mức an toàn có cấp bậc rõ ràng.
- Các tiêu đề bài được giữ cùng mục tiêu, chuẩn bị và bước đầu; ảnh và chú thích không bị tách. Không có chữ hoặc ảnh tràn lề.
- Kiểm tra tự động trên PDF cuối xác nhận đủ 108 khối trang có văn bản và không có trang trắng.
- Các bước trong từng bài bắt đầu lại từ số 1; bản render không còn chuỗi lặp `Bước 1:`, tiêu đề `Ảnh minh họa` hoặc Heading 4 dày đặc.

## Trạng thái website trước hoàn nguyên cuối

Hai thay đổi được duyệt và sẽ giữ lại:

- Ngôn ngữ WordPress: Tiếng Việt.
- Múi giờ WordPress: Ho Chi Minh.

Dữ liệu mẫu còn chờ xác nhận xóa:

- Bài viết nháp ID 40 và bản sửa đổi phụ thuộc ID 41.
- Trang nháp Gutenberg ID 43 và bản sửa đổi phụ thuộc ID 44.
- Trang nháp Elementor ID 45 và bản sửa đổi phụ thuộc ID 46.
- Chuyên mục ID 11.
- Media ID 42, gồm tệp gốc và năm kích thước ảnh sinh kèm.
- Tài khoản Biên tập viên tạm ID 2.

Tài khoản ID 2 chỉ sở hữu ba bản nháp và ba bản sửa đổi mẫu; không sở hữu nội dung thật. Chuyên mục mẫu có 0 bài và media mẫu không gắn với bài cha. Không có thay đổi mẫu nào được lưu vào menu, widget, Header, Footer, thanh thông báo hoặc khu vực Nâng cao. Việc xóa dữ liệu mẫu chỉ được thực hiện sau khi người dùng xác nhận ngay tại thời điểm xóa.

Quét chỉ đọc toàn bộ nội dung, taxonomy, người dùng và `Menu 1` xác nhận không còn đối tượng mẫu nào khác ngoài danh sách trên. Chín dấu vân tay chuẩn hóa trước hoàn nguyên đã được lưu trong `fingerprint-truoc-hoan-nguyen.md` để chạy lại ngay sau khi xóa.

Lần chạy chỉ đọc ngay trước bàn giao ngày 05 tháng 09 năm 2026 xác nhận cả chín dấu vân tay hiện tại vẫn khớp tuyệt đối với mốc đã lưu; chưa có thay đổi ngoài ý muốn đối với nội dung gốc, chuyên mục, menu, widget, plugin, theme hoặc các thiết lập được phép giữ lại.

Script `cleanup_sample_5plc.php` cũng đã chạy ở chế độ mặc định `dry-run` và đạt toàn bộ điều kiện trước khi xóa: đúng post/revision/media ID 40–46, chuyên mục rỗng ID 11, tài khoản Biên tập viên ID 2 chỉ sở hữu sáu mục mẫu, media trỏ đúng tệp cùng năm kích thước sinh kèm. Chế độ `apply` vẫn bị khóa và chưa được kích hoạt.
