# Đối chiếu sổ tay với mã nguồn Chính Tòa Media

Ngày kiểm tra: 05 tháng 09 năm 2026

## Phạm vi

Đối chiếu được thực hiện trên thư mục `chinhtoa`, là mã nguồn của giao diện Chính Tòa Media 1.0.0 đang được `5plc` sử dụng. Giao diện thật vẫn là nguồn ưu tiên; mã nguồn được dùng để xác nhận tên trường, điều kiện hiển thị và những giới hạn không thể kết luận chỉ từ ảnh chụp.

## Điểm vào và loại nội dung

| Nội dung trong sổ tay | Bằng chứng mã nguồn | Kết luận |
|---|---|---|
| Giao diện dùng Gutenberg và widget cổ điển | `chinhtoa/functions.php:81-110` đăng ký menu, ảnh đại diện, responsive embeds và tắt trình widget dạng khối | Đúng |
| Không có Custom Post Type đang dùng | Không có lệnh `register_post_type` trong mã giao diện được nạp; `functions.php:35-70` chỉ nạp chức năng bài viết thông thường | Đúng |
| Ba loại bài đều là Bài viết | `inc/post/post-kind.php:37-50` khai báo Mặc định, Lời Chúa và Video/audio, áp dụng mặc định cho `post` | Đúng |
| Trường Lời Chúa và video/audio | `inc/post/post-kind.php:120-167` hiển thị Câu Lời Chúa, Trích dẫn và Link video/audio theo loại đã chọn | Đúng |
| Video/audio dùng oEmbed | `inc/post/post-kind.php:202-217` chỉ dựng trình phát khi URL được `wp_oembed_get` nhận diện | Đúng |
| Khối theo loại nằm đầu bài | `template-parts/post/content.php:16-24` gọi thẻ Lời Chúa hoặc trình phát trước nội dung bài | Đúng |

Tệp `inc/widget/ct_giothanhle_widget.php` tồn tại trong mã nguồn nhưng không được `functions.php` nạp. Không có bằng chứng chức năng lịch Thánh lễ đang hoạt động, nên sổ tay không tạo hướng dẫn cho chức năng này.

## Thiết lập giao diện

| Khu vực | Bằng chứng mã nguồn | Phạm vi bài |
|---|---|---|
| Bốn nhóm Giao diện, Trang chủ & Bài viết, Tiện ích, Nâng cao | `inc/options/admin/admin-page.php:130-164` | Chương 13 |
| Màu giao diện, ba kiểu menu, ba màu menu và nền trang | `inc/options/admin/sections.php:30-76` | Bài 14.01 |
| Hai kiểu Header và ba ảnh theo kích thước | `inc/options/admin/sections.php:81-103` | Bài 14.02–14.05 |
| Nội dung, màu và một đến bốn cột widget Footer | `inc/options/admin/sections.php:106-124` | Bài 14.06–14.07 |
| Mã Analytics, mã đầu/cuối trang và shortcode theo vai trò | `inc/options/admin/sections.php:127-139` | Bài 16.04–16.05 |
| Thanh thông báo, thời gian, nội dung, livestream và class CSS | `inc/options/admin/sections.php:142-156` | Bài 16.01–16.03 |
| Thanh bên, khối Nổi bật và box 5 phút | `inc/options/admin/sections.php:159-202` | Bài 15.01–15.03 |
| Bố cục mặc định chuyên mục và bài viết | `inc/options/admin/sections.php:437-466` | Bài 15.09–15.10 |

### Giới hạn Header đã ghi vào sổ tay

- Giao diện quản trị có trường **Mô tả ảnh**, nhưng `inc/options/flatteners/helper-general.php:41-65` không chuyển giá trị này sang dữ liệu render và `template-parts/header/header-image.php:29-31` chưa xuất thuộc tính `alt`. Bài 14.05 không tuyên bố nhập trường là đã hoàn tất yêu cầu trợ năng.
- Khi không bật tab mới, helper trả `target="self"` thay vì giá trị chuẩn `_self` tại `inc/options/flatteners/helper-general.php:63-64`. Bài 14.05 yêu cầu bấm thử liên kết và chuyển kỹ thuật nếu hành vi không đúng.
- Khi ảnh máy tính bảng hoặc điện thoại để trống, tập nguồn còn lại được dùng làm dự phòng tại `template-parts/header/header-image.php:11-27`.

## Trình dựng khối trang chủ

| Nội dung | Bằng chứng mã nguồn | Kết luận đưa vào sổ tay |
|---|---|---|
| Bảy mẫu khối | `inc/options/admin/sections.php:204-215` | Tĩnh/HTML, Danh sách bài 1–5 và Tabs chuyên mục |
| Thêm, xóa và kéo sắp xếp | `inc/options/admin/sections.php:249-268, 411-434` | Bài 15.04, 15.06 và 15.08 |
| Tiêu đề, hiển thị và Chỉ Admin thấy | `inc/options/admin/sections.php:285-292`; điều kiện quyền ở `inc/options/flatteners/helper-homepage.php:69-159` | Bài 15.04 |
| Nguồn theo loại mẫu | `inc/options/admin/sections.php:294-315` | HTML/shortcode cho mẫu tĩnh; chuyên mục và số bài cho danh sách; danh sách tab cho Tabs |
| Thành phần thẻ bài | `inc/options/admin/sections.php:317-327` | Ảnh, mô tả, ngày, lượt xem và tác giả |
| Màu riêng | `inc/options/admin/sections.php:330-342` | Màu nền, chữ và màu nhấn; mẫu tĩnh không có màu nhấn |
| Nút Xem thêm | `inc/options/admin/sections.php:344-354` | Có ở mọi mẫu trừ Tabs chuyên mục |
| Không có trường sắp xếp | Không có control sắp xếp trong renderer khối; front-end đọc nguồn ở `inc/options/flatteners/helper-homepage.php:61-190` | Bài 15.05 ghi rõ giới hạn |

## Chuyên mục, bài viết, widget và block

| Nội dung trong sổ tay | Bằng chứng mã nguồn | Kết luận |
|---|---|---|
| Bố cục riêng của chuyên mục | `inc/options/admin/term-post-meta.php:193-269` | 1–4 cột, 2 kiểu, thanh bên, ảnh, mô tả, ngày/lượt xem, biểu tượng và màu |
| Bố cục riêng của bài viết | `inc/options/admin/term-post-meta.php:306-371` | Thanh bên, ảnh, breadcrumb, tiêu đề, tác giả, ngày/lượt xem |
| Ba sidebar cố định | `inc/utilities/sidebar.php:6-40` | Trang Chủ, Bài Viết Chi Tiết và Chuyên Mục |
| Footer sidebar động | `inc/options/flatteners/helper-general.php:150-169` | Chỉ đăng ký số cột đã chọn khi Footer widget được bật |
| Widget Danh Sách Bài Viết | `inc/widget/ct_postlist_widget.php:212-275` | Kiểu số/audio, chuyên mục, số bài, sắp xếp, màu, ảnh và mô tả |
| Widget Lời Chúa Câu ghi nhớ | `inc/widget/ct_loichua_card_widget.php:109-187` | Nhập tay hoặc động theo chuyên mục, bài hiện tại hay ID bài; có ba màu |
| Block Lời Chúa dùng chung renderer | `inc/blocks/loader.php:18-54` và `inc/blocks/loichua-card/render.php:79-174` | Block và widget dùng cùng cách lấy nội dung/hiển thị |

## Kết luận

Các bài về giao diện, trang chủ, chuyên mục, bài viết đặc biệt, widget và block đã được đối chiếu theo đúng mã đang được nạp. Những tệp hoặc chú thích cũ nhắc tới CPT/lịch Thánh lễ nhưng không được nạp không được coi là chức năng thật. Hai giới hạn Header được nêu rõ thay vì suy đoán theo nhãn giao diện.
