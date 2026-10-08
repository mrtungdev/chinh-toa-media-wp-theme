## Bố cục Trang chủ

*Dành cho Quản lý.* Trang chủ gồm: **thanh bên** (cột nhỏ bên phải), **khối Nổi bật** ở
đầu trang, và nhiều **khối nội dung** xếp từ trên xuống. Tất cả nằm ở **Thiết lập giao
diện → Trang chủ & Bài viết → Trang chủ**
([mở](admin.php?page=ct-theme-settings#noidung/homepage)).

> Các khối chỉ hiện trên trang có tên **Trang chủ** (đang dùng mẫu trang **Trang Chủ** và
> đặt làm trang chủ trong **Cài đặt → Đọc**). **Không đổi** hai thiết lập này.

### Thanh bên trang chủ

- **Hiển thị thanh bên**: tích **Bật** để có cột nhỏ bên cạnh các khối.
- **Vị trí thanh bên**: **Bên trái** hoặc **Bên phải**.
- Nội dung thanh bên (lịch, liên kết, bài xem nhiều…) đặt ở khu vực widget **Trang Chủ**
  (xem mục *Thanh bên & Widget*).

### Khối Nổi bật (tuỳ chọn)

1. **Hiển thị khối Nổi bật**: tích **Bật**.
2. **Kiểu khối Nổi bật**: chọn **Tin nổi bật + tiêu điểm**. (Kiểu *Tự dựng bằng Shortcode*
   dành cho người làm kỹ thuật.)
3. **Bật khối "Tin Hot"**: một dải tên các bài được xem nhiều. Chọn **Chuyên mục**, **Số
   bài**, và **Trong vòng (ngày)**: chỉ lấy bài đăng trong số ngày gần đây (để trống là
   các bài trong tuần này).
4. **Tiêu điểm**: một bài lớn và các bài nhỏ. Chọn **Chuyên mục** và **Số bài**. Để trống
   Số bài thì hiện 5 bài.

![Thiết lập khối Nổi bật]({{img}}/khoi-noi-bat.webp)
*Số trên ảnh trùng với số bước. Giữ phím Ctrl (Windows) hoặc Cmd (Mac) để chọn nhiều chuyên mục.*

<!-- if:loichua -->
Box **“5 phút”** cũng nằm ở đây. Xem mục *Thẻ Lời Chúa & Box “5 phút”*.
<!-- endif -->

### Các nút của một khối nội dung

Kéo xuống phần **Khối nội dung trang chủ**. Mỗi khối là một thanh ngang, có các nút:

1. **Biểu tượng ✥**: bấm giữ rồi kéo lên, xuống để **đổi thứ tự** khối.
2. **Mũi tên**: thu gọn hoặc mở rộng khối cho dễ nhìn.
3. **Ô chọn mẫu**: chọn kiểu trình bày của khối (xem bảng chín mẫu bên dưới).
4. **Xoá khối**: xoá khối này.
5. **+ Thêm khối**: thêm một khối mới ở cuối danh sách.

![Danh sách khối trang chủ]({{img}}/trang-chu-cac-khoi.webp)
*Danh sách khối khi đã thu gọn. Số trên ảnh trùng với số trong danh sách trên.*

### Chín mẫu khối

![Chín mẫu khối]({{img}}/mau-khoi.webp)
*Tên mẫu trong ô chọn và hình dáng khối ngoài website.*

| Mẫu | Ô cần điền |
|---|---|
| **Tĩnh / HTML** | **Nội dung (HTML)**: lời ngỏ, bản đồ, video nhúng. Có thể dán mã nhúng YouTube. |
| **Danh sách bài 1–5**, **Lưới ảnh mosaic** | **Chuyên mục** và **Số bài hiển thị** |
| **Tabs chuyên mục** | **Chuyên mục (Tab đầu)**, và **+ Thêm Tab** cho mỗi chuyên mục khác (gõ **Tên Tab**, chọn chuyên mục). Mỗi tab hiện 10 bài. |
<!-- if:loichua -->
| **Lời Chúa hôm nay (bài theo ngày)** | **Chuyên mục** chứa bài mỗi ngày. Xem mục *Lời Chúa hôm nay & Lịch*. |
<!-- endif -->

### Thêm một khối mới

Bấm **+ Thêm khối**. Khối mới hiện ở cuối danh sách:

1. Ở **ô chọn mẫu**, chọn mẫu phù hợp.
2. **Thông tin khối**: gõ **Tiêu đề khối** (hiện trên đầu khối). **Hiển thị khối** để
   **Có**. Chọn **Không** khi muốn tạm ẩn khối mà không xoá.
3. **Nguồn bài viết**: chọn **Chuyên mục** và kéo **Số bài hiển thị**.
4. **Hiển thị trên thẻ bài**: chọn **Có / Không** cho Ảnh đại diện, Mô tả ngắn, Ngày đăng,
   Lượt xem, Tác giả.
5. Kéo xuống cuối trang, bấm **Lưu thay đổi**.

![Chi tiết một khối]({{img}}/khoi-chi-tiet.webp)
*Số 1–4 trùng với bước 1–4.*

> **Chỉ Admin thấy: Có** dùng để thử khối mới: chỉ người đăng nhập bằng tài khoản Quản lý
> mới thấy khối ngoài website. Thử xong thì chuyển về **Không**.

### Màu của khối và nút “Xem thêm”

1. **Dùng màu mặc định**: chọn **Không** để tự đặt màu cho khối.
2. Chọn **Màu nền**, **Màu chữ**, **Màu nhấn**.
3. **Hiện nút**: chọn **Có** để thêm nút “Xem thêm” ở cuối khối.
4. Gõ **Chữ trên nút**, dán **Liên kết** (thường là trang của chuyên mục), chọn **Mở tab
   mới** nếu cần.

![Màu khối và nút Xem thêm]({{img}}/khoi-mau-xem-them.webp)
*Mẫu “Tabs chuyên mục”<!-- if:loichua --> và “Lời Chúa hôm nay”<!-- endif --> không có nút Xem thêm.*

### Đổi thứ tự hoặc xoá khối

- **Đổi thứ tự:** bấm giữ biểu tượng **✥** của khối, kéo tới vị trí mới, thả ra, rồi bấm
  **Lưu thay đổi**.
- **Xoá:** bấm **Xoá khối**. Khối biến mất ngay trên màn hình nhưng **chưa xoá thật** cho
  tới khi bấm **Lưu thay đổi**. Lỡ tay thì **tải lại trang** (không lưu) để lấy lại.

Lưu xong, trang chủ cập nhật ngay. Nếu chưa thấy đổi, bấm tải lại trang (phím F5).
