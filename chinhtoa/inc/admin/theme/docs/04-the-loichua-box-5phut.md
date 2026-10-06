## Thẻ Lời Chúa & Box “5 phút”

Website có ba cách hiện một câu Lời Chúa nổi bật:

| Cách | Đặt ở đâu | Ai làm |
|---|---|---|
| **Khối “Lời Chúa: Câu ghi nhớ”** | Bên trong một bài viết hoặc trang | Biên tập viên |
| **Widget “Lời Chúa: Câu ghi nhớ”** | Cột bên phải (thanh bên) | Quản lý |
| **Box “5 phút”** | Ngay dưới thanh menu, trên đầu trang | Quản lý |

### Chèn khối “Lời Chúa: Câu ghi nhớ” vào bài

1. Trong trang viết bài, bấm **dấu +** ở góc trái trên.
2. Gõ **Câu ghi nhớ** vào ô **Tìm kiếm**.
3. Bấm khối **Lời Chúa: Câu ghi nhớ**. Khối hiện trong bài và cột bên phải chuyển sang
   tab **Khối** với các thiết lập của khối.

![Khối Lời Chúa và các thiết lập ở cột phải]({{img}}/khoi-cau-ghi-nho.webp)
*Số 1: khối trong bài. Số 2: Nguồn nội dung. Số 3: Nội dung thẻ.*

**Nguồn nội dung**, ô **Chế độ** có hai lựa chọn:

- **Nhập tay (Static)**: bạn tự gõ câu Lời Chúa ở phần **Nội dung thẻ**.
- **Tự động từ bài viết (Dynamic)**: thẻ tự lấy câu Lời Chúa từ một bài loại *Lời Chúa*.
  Chọn tiếp ô **Lấy từ**:
  - **Mới nhất trong chuyên mục**: bài mới nhất của chuyên mục có số ở ô **ID chuyên mục**
    (để 0 là mọi chuyên mục).
  - **Bài đang xem**: chính bài đang chứa khối.
  - **Bài viết cụ thể**: bài có số ở ô **ID bài viết**.

**Nội dung thẻ**: **Nhãn (LABEL)** (mặc định *CÂU GHI NHỚ*), **Câu Lời Chúa**,
**Trích dẫn (CITATION)** (ví dụ *Mt 6, 33*). Ở chế độ Tự động, để trống thì thẻ lấy
từ bài viết.

**Màu sắc**: **Màu nền**, **Màu chữ (câu)**, **Màu nhấn (nhãn + trích dẫn)**. Để trống
thì dùng màu mặc định.

> **Tìm số ID:** vào **Bài viết → Danh mục** (hoặc **Tất cả bài viết**), bấm vào tên
> chuyên mục (hoặc tên bài). Trên thanh địa chỉ, số sau `tag_ID=` (hoặc `post=`)
> chính là ID.

### Widget “Lời Chúa: Câu ghi nhớ” ở cột bên phải *(Quản lý)*

Vào **Giao diện → Cấu hình cột tiện ích** ([mở](widgets.php)). Kéo widget **Lời Chúa:
Câu ghi nhớ** thả vào khu vực mong muốn (**Trang Chủ**, **Bài Viết Chi Tiết** hoặc
**Chuyên Mục**). Widget tự mở ra:

1. Chọn **Chế độ**: *Nhập tay (Static)* hoặc *Tự động từ bài viết (Dynamic)*.
2. Nhập tay thì điền **Nhãn**, **Câu Lời Chúa**, **Trích dẫn**.
3. Tự động thì chọn ô **Dynamic — lấy từ** và **Chuyên mục** (hoặc **ID bài viết**).
4. Bấm **Lưu thay đổi**. Nút đổi thành **Đã lưu** là xong.

![Widget Lời Chúa: Câu ghi nhớ]({{img}}/widget-cau-ghi-nho.webp)
*Số trên ảnh trùng với số bước.*

### Box “5 phút” *(Quản lý)*

Box nằm ngay dưới thanh menu. Box hiện tiêu đề, đoạn tóm tắt của bài **mới nhất** trong
chuyên mục bạn chọn, và liên kết **Suy niệm »**.

Vào **Thiết lập giao diện → Trang chủ & Bài viết → Trang chủ**
([mở](admin.php?page=ct-theme-settings#noidung/homepage)), kéo xuống phần box “5 phút”:

1. Ở **Hiển thị box "5 phút"**, tích **Bật**.
2. Gõ **Tiêu đề box**, ví dụ *5 phút Lời Chúa*.
3. Ở **Chuyên mục nguồn**, bấm chọn chuyên mục chứa bài suy niệm, ví dụ *Suy niệm*.
4. Tích nơi muốn hiện: **Hiện ở Trang chủ**, **Hiện ở trang Bài viết**, **Hiện ở trang
   Chuyên mục**.
5. Kéo xuống cuối trang, bấm **Lưu thay đổi**.

![Thiết lập box 5 phút]({{img}}/box-5-phut.webp)
*Số 1–4 trùng với bước 1–4.*

Bài mới đăng trong chuyên mục nguồn sẽ tự thay vào box. Không cần sửa lại thiết lập.
