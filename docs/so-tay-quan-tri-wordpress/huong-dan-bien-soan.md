# Hướng dẫn biên soạn Sổ tay (bản 2026-10, theme 1.1.0)

Tài liệu nội bộ cho người viết các chương trong `src/`. Mọi chương phải theo đúng quy tắc
dưới đây để `scripts/validate_sources.py` chạy qua và để sổ tay thống nhất.

## 1. Người đọc và văn phong

Người đọc là **Biên tập viên giáo xứ, không rành công nghệ**, làm theo từng bước.

- Câu ngắn (dưới ~25 từ). Mỗi câu một ý. Tránh câu bị động, tránh “được thực hiện bởi”.
- **Mỗi bước một hành động**: “Bấm **Lưu thay đổi**.” — không gộp 3 việc vào 1 bước.
- Gọi **đúng tên trên màn hình**, in đậm, giữ nguyên chữ hoa/thường như giao diện
  (xem mục 4). Đường đi qua menu dùng `→`: **Bài viết → Thêm Bài Viết**.
- Giải thích từ chuyên môn ngay lần đầu: “thanh bên (cột nhỏ bên cạnh nội dung)”,
  “danh mục (chuyên mục — nhóm bài cùng chủ đề)”, “widget (ô nhỏ ở thanh bên)”.
- Không dùng: HTML, CSS, oEmbed, metadata, transient, cache (trừ khi giải thích),
  `manage_options`, “trường” → dùng “ô”. “Click” → “bấm”. “Tick/checkbox” → “tích vào ô”.
- Không nhắc: `5plc`, “môi trường kiểm chứng”, `[TÀI LIỆU MẪU]`, “dữ liệu mẫu tạm”,
  “hoàn nguyên dữ liệu mẫu”, tên tài khoản (admin, btv, tai_lieu_editor).
  Ví dụ minh hoạ thì dùng dữ liệu của website mẫu “5 phút cho Lời Chúa” (mục 3) và nói
  “ví dụ”.
- Giọng thân thiện, tôn trọng: “bạn”. Không đe doạ; cảnh báo ngắn, nói rõ hậu quả.
- Tiếng Anh chỉ ghi khi màn hình hiện tiếng Anh (VD **Image Caption**, **First Name**,
  **Nickname (bắt buộc)**), hoặc trong ngoặc sau từ Việt: Đầu trang (Header).

## 2. Định dạng bắt buộc (máy kiểm tra)

```markdown
# Phần 6 Thiết lập giao diện Chính Tòa Media

## Chương 14 Màu sắc, thanh menu, Đầu trang (Header) và Cuối trang (Footer)

### Bài 14.04 Đầu trang kiểu Logo và khẩu hiệu

**Nhãn:** Kỹ thuật dành cho quản trị viên · 🟡 Cẩn thận

#### Mục đích

Một–hai câu: làm việc này để làm gì.

#### Trước khi bắt đầu

Cần chuẩn bị gì, đăng nhập bằng vai trò nào, mở màn hình nào.

#### Các bước thực hiện

1. **Bước 1:** Bấm tab con **Header**.
2. **Bước 2:** Ở **Kiểu Header**, chọn **Logo + khẩu hiệu**.
...

#### Ảnh minh họa

![Mô tả ngắn ảnh](../screenshots/final/P06-C14-B04-01-header-logo-khau-hieu.png)

*Hình 14.04 Một câu chú thích, kết thúc bằng dấu chấm.*

#### Kết quả

Bạn sẽ thấy … (điều người đọc nhìn thấy khi làm đúng).

#### Lưu ý

…

#### Không nên làm

…

#### Nếu gặp lỗi

- Tình huống 1 → cách xử lý.
- Tình huống 2 → cách xử lý.
```

Quy tắc:

- Mỗi tệp chương bắt đầu bằng `# Phần N <tên>` rồi `## Chương N <tên>` (phụ lục:
  `# Phần phụ lục` rồi `## Phụ lục X <tên>`), **giống hệt** mục lục `src/00-muc-luc-de-xuat.md`.
- Tiêu đề bài `### Bài NN.MM <tên>` **giống từng chữ** với mục lục.
- Dòng nhãn: `**Nhãn:** <Đối tượng> · <🟢 An toàn | 🟡 Cẩn thận | 🔴 Không tự thay đổi>`
  — đối tượng và màu **giống mục lục**.
- Đủ 8 mục `####` theo đúng thứ tự trên, không mục nào để trống.
- Các bước: `1. **Bước 1:** …`, đánh số liên tục từ 1, số danh sách = số trong “Bước n”.
  Một bài chỉ có **một** danh sách bước (các danh sách phụ dùng gạch đầu dòng `-`).
- **Nếu gặp lỗi**: 2–5 gạch đầu dòng, mỗi dòng một tình huống cụ thể của bài đó
  (không chép câu chung chung lặp lại ở mọi bài).
- Bài có nhãn `Ảnh` trong mục lục: mục **Ảnh minh họa** phải có ảnh (`![…](…)` + dòng
  `*Hình NN.MM …*` ngay sau, cách một dòng trống) **hoặc** câu dẫn “Xem Hình XX.YY …”.
  Bài `Không ảnh`: ghi một câu “Bài này không cần ảnh minh họa.” hoặc dẫn hình khác.
- Số hình = số bài đang viết: ảnh nhúng trong Bài 05.02 là *Hình 05.02*; bài có hai ảnh
  thì *Hình 05.02a*, *Hình 05.02b*. Được nhúng lại một ảnh ở bài khác nếu giúp người đọc
  không phải lật trang (chú thích theo số bài mới).
- Đường dẫn ảnh: `../screenshots/final/<tên>.png` (chỉ dùng ảnh có trong mục 6).
- Không dùng TODO, TBD, `[SCREENSHOT`, Lorem ipsum.
- Được dùng bảng Markdown đơn giản (tối đa 3 cột, chữ ngắn) khi so sánh giúp dễ hiểu;
  còn lại dùng gạch đầu dòng. Không lồng danh sách nhiều cấp.

### Số trên ảnh = số bước

Ảnh có khung đỏ và **số tròn xanh**. Ưu tiên viết các bước sao cho **Bước n** ứng với
**số n** trên ảnh. Khi bài cần thêm bước không có trên ảnh (mở menu, bấm Lưu…), đặt
bước đó ở **cuối**, hoặc ghi rõ trong chú thích “Số 1: …; số 2: …”. Khi dẫn hình của bài
khác, nói rõ số: “Xem Hình 15.04, số 5 (nút **+ Thêm khối**)”.

## 3. Dữ liệu của website mẫu (dùng làm ví dụ)

- Tên website: **5 phút cho Lời Chúa**. Khẩu hiệu: *Lời Chúa là ngọn đèn soi cho con
  bước* (Tv 119,105).
- Danh mục (chuyên mục): **Suy niệm** (danh mục mặc định, không có nút Xóa), **Từ vựng**,
  **Giáo lý**, **Vui học Kinh Thánh**, **Huấn quyền**.
- Trang: **Trang chủ** (dùng mẫu trang **Trang Chủ**, được đặt làm trang chủ), **Liên hệ**.
- Menu: **Menu chính** (gắn vị trí **Menu Chính**; có 3 cấp: Giáo lý → 3 bài; Huấn quyền →
  “Công đồng Vaticanô II”, “Đức Thánh Cha Phanxicô” → các văn kiện) và **Liên kết**
  (5 liên kết ngoài, hiện qua widget **Menu**).
- Bài Suy niệm mỗi ngày là loại **Lời Chúa (câu ghi nhớ)**, không có ảnh đại diện, có
  Ngày phụng vụ, Lễ / kính thánh, Đoạn Tin Mừng; các ngày tương lai ở trạng thái
  **Đã lên lịch**. Ví dụ bài 06/10/2026: *Chỉ có một chuyện cần thôi* — câu *Maria đã chọn
  phần tốt nhất và sẽ không bị lấy mất.* (Lc 10,42), *Thứ Ba Tuần 27 TN*, *Th. Brunô, linh
  mục*, Tin Mừng *Lc 10,38-42*.
- Trang chủ có 6 khối: **Lời Chúa hôm nay** (mẫu *Lời Chúa hôm nay (bài theo ngày)*),
  **Bài viết mới** (*Lưới ảnh mosaic*), **Từ vựng** (*Danh sách bài 4*), **Giáo lý**
  (*Danh sách bài 3*), **Vui học Kinh Thánh** (*Danh sách bài 5*), **Huấn quyền**
  (*Danh sách bài 1*). Thanh bên trang chủ: bên phải.
- Widget khu vực **Trang Chủ**: **Lịch Lời Chúa** (tiêu đề “Lịch Lời Chúa”, chuyên mục
  Suy niệm), **Menu** (Liên kết), **Danh Sách Bài Viết** (“Bài xem nhiều”, Xem nhiều nhất).
- Header kiểu **Logo + khẩu hiệu**; menu kiểu thứ 4 (thanh màu đặc, chia đều); màu giao
  diện **Tự chọn**.
- Plugin đang bật: Elementor, Yoast SEO, WP-PostViews. **UpdraftPlus đang tắt.**

## 4. Nhãn thật trên màn hình (WordPress 7.1 tiếng Việt + theme 1.1.0)

**Menu trái (Quản lý thấy đủ; Biên tập viên chỉ thấy phần đánh dấu \*):**
Bảng Điều Khiển\* · Chính Tòa Media\* (→ Chính Tòa Media = trang Hướng dẫn sử dụng; →
Thiết lập giao diện chỉ Quản lý) · Elementor · Bài viết\* (Tất cả bài viết, Thêm Bài Viết,
Danh mục, Thẻ) · Thư viện\* (Thư viện, Tải tệp lên) · Trang\* (Tất cả các trang, Thêm
Trang) · Bình luận\* · Giao diện (Giao diện, Các mẫu, Sửa giao diện, **Cấu hình cột tiện
ích**, Phông chữ, **Thiết lập Menu**, Sửa tệp tin giao diện) · Plugin (Plugin đã cài đặt,
Thêm Plugin, Sửa tệp tin Plugin) · Tài khoản (Tất cả người dùng, Thêm người dùng, Hồ sơ)
· Cài đặt (Tổng quan, Viết, Đọc, Bình luận, Thư viện, Cấu trúc đường dẫn, Riêng tư,
WP-PostViews) · Yoast SEO · Thu gọn Menu. Biên tập viên thấy **Hồ sơ** riêng ở menu trái.

**Bảng Điều Khiển:** “Xin chào, <tên>!”; các thẻ: **Hướng dẫn sử dụng** (nút *Xem hướng
dẫn*), **Thiết lập giao diện** (*Mở thiết lập*, chỉ Quản lý), **Viết bài mới** (*Viết
bài*), **Chuyên mục** (*Quản lý*), **Menu điều hướng** (*Chỉnh menu*, chỉ Quản lý).

**Đăng nhập:** Tên người dùng hoặc địa chỉ email · Mật khẩu · Ghi nhớ đăng nhập · nút
**Đăng nhập** · liên kết **Bạn quên mật khẩu?** → ô *Tên người dùng hoặc địa chỉ email*,
nút **Lấy mật khẩu mới**. Thanh đen trên cùng: tên website (rê chuột → **Xem website**);
góc phải “Xin chào, <tên>” (rê chuột → **Đăng xuất**).

**Danh sách bài viết:** tiêu đề **Bài viết**, nút **Thêm Bài Viết**; lọc trạng thái
*Tất cả · Đã xuất bản · Đã lên lịch · Nội dung quan trọng* (và *Bản nháp*, *Thùng rác*
khi có); ô tìm + nút **Tìm các bài viết**; ô **Hành động hàng loạt** + **Áp dụng**; ô
**Tất cả các ngày**, **Tất cả danh mục** + nút **Lọc**; cột Tiêu đề, Tác giả, Danh mục,
Thẻ, Bình luận, Thời gian (và cột Điểm SEO của Yoast). Rê chuột vào tên bài: **Chỉnh sửa
| Sửa nhanh | Xóa tạm | Xem** (bài chưa đăng: *Xem trước*). Bài hẹn giờ ghi “— Lên kế
hoạch”. Sửa nhanh có ô Tiêu đề, Đường dẫn, Thời gian, Danh mục, Trạng thái, nút **Cập
nhật** và **Hủy**. Thùng rác: **Khôi phục lại** (bài về dạng Bản nháp), **Xóa vĩnh
viễn**. Nút góc phải trên danh sách: **Tùy chọn màn hình** (ẩn/hiện cột), **Hỗ trợ**.
Lịch sử chỉnh sửa của bài: dòng **Lịch sử sửa** ở cột phải (khi bài có từ 2 bản lưu).

**Trình soạn bài (Gutenberg):** thanh trên: nút **+** (*Mở thư viện khối*), **Hoàn tác**,
**Làm lại**, **Tổng quan nội dung**, nút xanh **Sửa với Elementor** (KHÔNG bấm), tên bài,
**Xem** (hình màn hình: *Máy tính, Máy tính bảng, Di động, Xem trước ở tab mới*), Yoast,
**Cài đặt** (ô vuông chia đôi), **Lưu nháp** (bài chưa đăng), nút xanh **Xuất bản** (bài
mới) / **Lên lịch** (khi chọn ngày tương lai) / **Lưu thay đổi** (bài đã đăng), **Tùy
chọn** (3 chấm). Bấm **Xuất bản** → bảng “**Bạn sẵn sàng xuất bản chưa?**” với **Hủy** và
**Xuất bản** (bấm lần 2 mới đăng). Ô tiêu đề mờ: **Thêm tiêu đề**; dòng trống: “Gõ / để
chọn khối”. Thư viện khối: tab *Khối · Mẫu khối · Thư viện*, ô **Tìm kiếm**, khối
**Văn bản** (đoạn văn), **Tiêu đề**, **Danh sách**, **Trích dẫn**, **Hình ảnh**,
**Thư viện ảnh**, **Âm thanh**… Thanh công cụ của một khối văn bản: **Văn bản** (đổi loại
khối), **Căn lề văn bản**, **Đậm**, **Nghiêng**, **Liên kết**, **Thêm**, **Tùy chọn**.
Khối Hình ảnh trống: **Tải lên**, **Tất cả tập tin**, **Chèn từ URL**.
Cột phải: tab **Bài viết** / **Khối**, nút **Đóng cài đặt** (X). Tab Bài viết: **Đặt ảnh
đại diện**, mô tả ngắn (**Thêm mô tả ngắn…** / **Sửa mô tả ngắn**), **Trạng thái**
(*Bản nháp / Đã xuất bản / Đã lên lịch*), **Xuất bản** (*Ngay lập tức* hoặc ngày giờ —
bấm mở lịch: *Thời gian*, ngày/tháng/năm, nút *Bây giờ*), **Đường dẫn**, **Tác giả**,
**Mẫu trang**, **Bình luận**, **Di chuyển đến thùng rác**; các bảng **Yoast SEO**,
**Danh mục** (danh sách ô tích + liên kết **Thêm Danh Mục**), **Thẻ**; hộp **Phân loại
bài viết**, hộp **Tuỳ chỉnh giao diện bài viết**. Hộp chọn ảnh đại diện: tiêu đề **Ảnh đại
diện**, tab **Tải lên tệp mới** / **Tất cả tập tin**, ô **Văn bản thay thế**, nút **Đặt
ảnh đại diện**.

**Thư viện:** tiêu đề **Tất cả tập tin**, nút **Tải tệp lên**; trang tải lên **Tải lên
phương tiện**: khung *Thả các tệp tin để tải lên*, nút **Chọn tệp tin**, dòng *Kích thước
tệp tin tải lên tối đa: 300 MB*. Chi tiết ảnh (**Chi tiết tệp đính kèm**): **Mô tả SEO**
(văn bản thay thế), **Tiêu đề**, **Image Caption**, **Miêu tả**, **URL tệp:**, **Sao chép
liên kết**, nút **Sửa ảnh**, liên kết *Xem tập tin | Sửa chi tiết hơn | Tải về tệp tin |*
**Xóa vĩnh viễn**. Các ô tự lưu khi bấm ra ngoài.

**Danh mục:** **Bài viết → Danh mục**; khung **Thêm Danh Mục**: Tên, Đường dẫn, Danh mục
cha (*Không có*), Miêu tả, hộp **Tuỳ chỉnh giao diện chuyên mục**, nút **Thêm Danh Mục**.
Danh sách cột Tên, Miêu tả, Đường dẫn, Lượt (số bài). Rê chuột: **Chỉnh sửa | Sửa nhanh |
Xóa | Xem**. Trang sửa: **Sửa danh mục**, nút **Cập nhật**, liên kết **Xóa**.

**Trang:** **Trang**, nút **Thêm Trang**; “Trang chủ — Trang chủ” (nhãn cho biết đang là
trang chủ). Rê chuột: **Chỉnh sửa | Sửa nhanh | Xóa tạm | Xem**. Cột “lượt xem”.

**Cài đặt → Đọc** (tiêu đề **Cài đặt đọc**): **Trang chủ của bạn hiển thị**: *Bài viết
mới nhất* / *Một trang tĩnh (chọn dưới đây)* → *Trang chủ:* Trang chủ, *Trang bài viết:*
— Chọn —. Nút **Lưu thay đổi**.

**Hồ sơ:** **Hồ sơ**; nhóm **Tuỳ chọn cá nhân**, **Tên** (Tên người dùng — *không thay đổi
được*, **First Name**, **Last Name**, **Nickname (bắt buộc)**, **Hiển thị tên công khai
như**), **Thông tin liên hệ** (Email (bắt buộc)…), **Quản lý tài khoản** (**Mật khẩu mới**
→ nút **Đặt mật khẩu mới**, ô mật khẩu + **Ẩn** + **Hủy**, thanh độ mạnh), nút **Cập nhật
hồ sơ**.

**Người dùng (Quản lý):** **Tài khoản**, nút **Thêm người dùng**; cột Tên người dùng,
Tên, Email, Vai trò, Bài viết; ô **Đổi thành…** + nút **Thay đổi**. **Thêm người dùng**:
Tên người dùng (bắt buộc), Email (bắt buộc), First Name, Last Name, Trang web, Ngôn ngữ,
Mật khẩu (nút **Tạo mật khẩu**), Gửi thông báo đến thành viên, **Vai trò** (Biên tập
viên…), nút **Thêm người dùng**. Vai trò: Quản lý (Quản trị viên), Biên tập viên, Tác giả,
Cộng tác viên, Thành viên đăng ký.

**Menu (Quản lý):** **Giao diện → Thiết lập Menu**; tab **Sửa menu** / **Quản lý vị trí
menu**; dòng **Chọn menu để sửa:** + nút **Chọn**; cột **Thêm các mục menu**: Trang, Bài
viết, Liên kết tự tạo (**URL**, **Văn bản liên kết**), Danh mục; tab *Dùng nhiều nhất ·
Xem tất cả · Tìm kiếm*; **Chọn toàn bộ**; nút **Thêm vào menu**. **Cấu trúc menu**: **Tên
menu**, **Chọn hàng loạt**; mở một mục (mũi tên ▾): **Nhãn Điều Hướng**, **Menu Cha**,
**Thứ tự Menu**, *Di chuyển: Lên một bậc · Xuống một bậc…*, **Gốc:**, **Xóa bỏ | Hủy**;
mục con ghi *mục phụ*. **Thiết lập menu**: *Tự động thêm trang* (ô *Tự động thêm các
trang cấp cao nhất mới vào trình đơn này*), *Vị trí menu* (ô **Menu Chính**). Nút **Lưu
menu**, liên kết **Xóa menu**. Tab vị trí: *Vị trí menu* / *Menu đã gán*, nút **Lưu thay
đổi**.

**Widget (Quản lý):** **Giao diện → Cấu hình cột tiện ích**; **Tiện ích sẵn có**; khu vực
**Tiện ích không sử dụng**, **Trang Chủ**, **Bài Viết Chi Tiết**, **Chuyên Mục**, (khi bật
ở Footer) **Cuối Trang - Cột 1…4**. Widget có sẵn: Bài viết mới, Bình luận gần đây, Danh
mục, **Danh Sách Bài Viết**, HTML Tùy chỉnh, Hình ảnh, Khối, Lịch (của WordPress, khác
Lịch Lời Chúa), **Lịch Lời Chúa**, **Lời Chúa: Câu ghi nhớ**, Lưu trữ, lượt xem, **Menu**,
Meta, Mây Thẻ, RSS, Thư viện ảnh, Trang, Tìm kiếm, Video, Văn bản, Âm thanh. Trong widget:
liên kết **Xóa | Hoàn thành**, nút **Lưu thay đổi** (khi chưa đổi gì nút xám **Đã lưu**).

**Thiết lập giao diện (Quản lý):** **Chính Tòa Media → Thiết lập giao diện**; 4 tab:
**Giao diện** (tab con **Màu sắc & hiển thị**, **Header**, **Footer**), **Trang chủ & Bài
viết** (**Trang chủ**, **Trang Chuyên mục & Bài viết**), **Tiện ích** (Thanh thông báo),
**Nâng cao** (Kỹ thuật). Nút **Lưu thay đổi** cuối trang → thông báo xanh **“Đã lưu thay
đổi.** Mở website (hoặc tải lại trang) để xem kết quả.” và mở lại đúng tab đang làm.
Công tắc hiện chữ **Bật** (ô tích). Ô chọn chuyên mục: danh sách, giữ Ctrl/Cmd để chọn
nhiều. Chi tiết từng ô ở mục 5.

**Hộp trong trang viết bài (theme):**
- **Phân loại bài viết** → **Loại bài viết**: *Mặc định (bài viết thường)* / *Lời Chúa
  (câu ghi nhớ)* / *Video (audio) Lời Chúa*. Loại Lời Chúa: **Câu Lời Chúa**, **Trích
  dẫn**, (ghi chú), **Ngày phụng vụ** (VD Thứ Hai Tuần 27 TN), **Lễ / kính thánh** (VD
  Th. Faustina, trinh nữ), **Đoạn Tin Mừng** (VD Lc 10,25-37). Loại Video: **Link
  video/audio** (dán link YouTube/Vimeo; trình phát hiện ở đầu bài thay ảnh đại diện).
- **Tuỳ chỉnh giao diện bài viết** → công tắc **Tuỳ chỉnh riêng bài này** (Tắt: dùng
  thiết lập chung); khi bật: **Thanh bên (sidebar)** (+ vị trí trái/phải), **Hình đại
  diện**, **Menu điều hướng** (đường dẫn kiểu “Trang chủ › …”, cần Yoast), **Tiêu đề bài
  viết**, **Tác giả**, **Ngày & lượt xem**.
- Khối **Lời Chúa: Câu ghi nhớ** (tìm “Câu ghi nhớ”): bảng **Nguồn nội dung** (**Chế độ**:
  *Nhập tay (Static)* / *Tự động từ bài viết (Dynamic)*; **Lấy từ**: *Mới nhất trong chuyên
  mục* / *Bài đang xem* / *Bài viết cụ thể*; **ID chuyên mục (0 = mọi chuyên mục)**; **ID
  bài viết**), **Nội dung thẻ** (**Nhãn (LABEL)** mặc định CÂU GHI NHỚ, **Câu Lời Chúa**,
  **Trích dẫn (CITATION)**), **Màu sắc** (Màu nền, Màu chữ (câu), Màu nhấn (nhãn + trích
  dẫn)).

**Hộp chuyên mục (Quản lý, ở trang sửa/thêm danh mục):** **Tuỳ chỉnh giao diện chuyên
mục** → công tắc **Tuỳ chỉnh riêng chuyên mục này**; khi bật: **Số cột danh sách** (4 ô
1–4 cột), **Kiểu trình bày** (ảnh trên chữ dưới / chữ trên ảnh), **Hiển thị thanh bên
(sidebar)** (+ **Vị trí thanh bên**), **Hình đại diện**, **Mô tả ngắn**, **Ngày & lượt
xem**. (Nhóm “Biểu tượng & màu sắc” cũ đã bị ẩn từ bản 1.1.0 — không viết về nó.)

## 5. Thiết lập giao diện — từng ô (đã kiểm tra với mã nguồn 1.1.0)

**Giao diện → Màu sắc & hiển thị:** **Màu giao diện** (Trắng, Đen, Xanh lá, Đỏ, Hồng,
Tím, Vàng, Xanh dương, **Tự chọn**) → **Màu tự chọn** (khi chọn Tự chọn) · **Kiểu thanh
menu** (4 ảnh: kiểu 1 thanh tối mục đang xem tô màu; kiểu 2 như 1 + vạch màu dưới thanh;
kiểu 3 thanh sáng màu kem, gạch chân; kiểu 4 thanh màu đặc, các mục chia đều) · **Màu nền
thanh menu**, **Màu chữ thanh menu**, **Màu nhấn thanh menu** (gạch chân mục đang chọn ở
kiểu 3 và 4, màu nền mục đang chọn ở kiểu 1, 2; trống = màu giao diện) · **Nền trang**
(*Dùng màu nền* → **Màu nền**; *Dùng hình nền* → **Hình nền**, **Cách lặp hình nền**:
Không lặp / Lặp cả hai chiều / Lặp theo chiều ngang / Lặp theo chiều dọc).

**Giao diện → Header:** **Kiểu Header**: *Tự nhập nội dung* (→ **Nội dung Header** có
nút Thêm tệp, **Màu nền Header**) / *Ảnh banner rộng* (→ **Ảnh cho Máy tính**, **Ảnh cho
Máy tính bảng**, **Ảnh cho Điện thoại** (trống = dùng ảnh máy tính), **Mô tả ảnh** (thành
mô tả ảnh cho Google/người khiếm thị), **Liên kết khi bấm** (trống = về trang chủ), **Mở
liên kết ở tab mới**) / *Logo + khẩu hiệu* (→ **Logo** (PNG nền trong suốt; trống = hiện
tên website), **Câu khẩu hiệu**, **Trích dẫn**, **Màu nền phía trên**, **Màu nền phía
dưới** (trống cả hai = nền trong suốt), **Màu chữ khẩu hiệu**). Mỗi ảnh: nút **Chọn ảnh**,
**Xoá**. Trên điện thoại kiểu Logo + khẩu hiệu xếp chồng, căn giữa.

**Giao diện → Footer:** **Hiển thị cột Widget** (Bật) → **Số cột Widget** (4 ô 1–4 cột;
tạo khu vực widget “Cuối Trang - Cột 1…N”) · **Nội dung cuối trang** (soạn văn bản) ·
**Màu nền cuối trang** · **Màu chữ cuối trang**.

**Trang chủ & Bài viết → Trang chủ:** **Hiển thị thanh bên** → **Vị trí thanh bên** (Bên
trái / Bên phải) · **Hiển thị khối Nổi bật** → **Kiểu khối Nổi bật** (*Tin nổi bật +
tiêu điểm* / *Tự dựng bằng Shortcode* → ô **Shortcode**) → **Bật khối "Tin Hot"** (dải tên
bài xem nhiều) → **Tin Hot — Chuyên mục**, **Tin Hot — Số bài**, **Tin Hot — Trong vòng
(ngày)** (số ngày gần đây; trống = các bài trong tuần này) · **Tiêu điểm — Chuyên mục**,
**Tiêu điểm — Số bài** (trống = 5) · **Hiển thị box "5 phút"** → **Tiêu đề box**, **Chuyên
mục nguồn**, **Hiện ở Trang chủ**, **Hiện ở trang Bài viết**, **Hiện ở trang Chuyên mục**
(box ở dưới thanh menu: tiêu đề + tóm tắt bài mới nhất của chuyên mục + liên kết
“Suy niệm »”; chỉ hiện ở đúng nơi được bật, không hiện ở trang tìm kiếm).

**Khối nội dung trang chủ** (cuối tab Trang chủ): mỗi khối có: biểu tượng **✥** (kéo để
sắp xếp), mũi tên (*Thu gọn / Mở rộng*), **ô chọn mẫu**, tóm tắt tiêu đề, nút **Xoá khối**
(xoá ngay trên màn hình, chỉ thật sự xoá khi bấm Lưu thay đổi), ảnh “Bố cục mẫu khối:”.
Nút **+ Thêm khối** ở cuối. Chín mẫu: **Tĩnh / HTML**, **Danh sách bài 1** (1 bài lớn trên,
3 bài nhỏ dưới), **Danh sách bài 2** (bài lớn trái, danh sách cuộn phải), **Danh sách bài
3** (2 bài lớn, 2 cột danh sách nhỏ), **Danh sách bài 4** (lưới 2 cột ảnh+tên+mô tả),
**Danh sách bài 5** (lưới 2 cột, tên trên ảnh), **Tabs chuyên mục** (mỗi tab 10 bài),
**Lời Chúa hôm nay (bài theo ngày)**, **Lưới ảnh mosaic** (bài đầu ô lớn, các bài sau ô
nhỏ). Nhóm ô trong khối:
- **Thông tin khối**: **Tiêu đề khối**, **Hiển thị khối** (Có/Không — Không = tạm ẩn),
  **Chỉ Admin thấy** (Có = chỉ Quản lý đang đăng nhập thấy, để thử).
- **Nội dung** (chỉ mẫu Tĩnh / HTML): **Nội dung (HTML)** (chấp nhận mã nhúng YouTube).
- **Nguồn bài viết**: **Chuyên mục** + **Số bài hiển thị** (thanh kéo 1–30; mặc định 6) —
  mẫu Danh sách 1–5 và Lưới ảnh mosaic; mẫu Tabs: **Chuyên mục (Tab đầu)** + **Danh sách
  Tabs** (mỗi tab: ô **Tên Tab** + chuyên mục + **×**; nút **+ Thêm Tab**; tab không tên tự
  lấy tên chuyên mục; khối Tabs không tiêu đề thì tab đầu lấy tên chuyên mục); mẫu Lời Chúa
  hôm nay: **Chuyên mục** (chỉ dùng chuyên mục đầu tiên được chọn).
- **Hiển thị trên thẻ bài** (không có ở Tĩnh, Lời Chúa hôm nay): **Ảnh đại diện** Có, **Mô
  tả ngắn** Có, **Ngày đăng** Có, **Lượt xem** Có, **Tác giả** Không.
- **Màu sắc khối**: **Dùng màu mặc định** (Có/Không) → khi Không: **Màu nền**, **Màu chữ**,
  **Màu nhấn** (Tĩnh không có Màu nhấn).
- **Nút "Xem thêm"** (không có ở Tabs, Lời Chúa hôm nay): **Hiện nút** (Không), **Chữ trên
  nút**, **Liên kết**, **Mở tab mới** (Không).
Lưu thiết lập là trang chủ cập nhật ngay (không phải chờ). Các khối chỉ hiện trên trang
dùng mẫu trang **Trang Chủ** đang được đặt làm trang chủ.

**Khối Lời Chúa hôm nay ngoài website:** tiêu đề có dấu ✚; thẻ: bên trái ảnh đại diện
(không có ảnh → thẻ màu có câu Lời Chúa + trích dẫn), bên phải: ngày dd/mm/yyyy · Ngày
phụng vụ, tên bài, Lễ / kính thánh, “Tin Mừng: <đoạn>”, mô tả ngắn, “**Đọc tiếp →**”. Lấy
bài có **ngày đăng = hôm nay** trong chuyên mục; chưa có thì hiện bài gần nhất trước đó.
**Widget Lịch Lời Chúa**: tiêu đề tháng “Tháng 10 Năm 2026”, mũi tên ‹ › (Tháng trước /
Tháng sau — chỉ tới tháng hiện tại), hàng thứ HAI…CN, ngày có bài được tô màu, hôm nay có
vòng tròn, Chủ Nhật màu đỏ; dòng “Bấm vào ngày được tô màu để xem bài của ngày đó.” Widget
cùng chuyên mục với khối → bấm ngày đổi bài ngay trong khối; khác chuyên mục hoặc ở trang
khác → mở bài của ngày đó. Ô widget: **Tiêu đề**, **Chuyên mục nguồn** (ghi chú: chọn cùng
chuyên mục với khối “Lời Chúa hôm nay”).

**Trang chủ & Bài viết → Trang Chuyên mục & Bài viết:** nhóm **Trang Chuyên mục**: **Số
cột danh sách** (1–4), **Kiểu trình bày** (2 ảnh), **Hiển thị thanh bên** (khu vực widget
“Chuyên Mục”), **Vị trí thanh bên**; nhóm **Trang Bài viết**: **Hiển thị thanh bên** (khu
vực “Bài Viết Chi Tiết”), **Vị trí thanh bên**.

**Tiện ích → Thanh thông báo** (tiêu đề “Thanh thông báo nổi bật”): **Bật thanh thông báo**
→ **Bắt đầu hiển thị**, **Kết thúc hiển thị** (ô ngày dd/mm/yyyy + ô giờ), **Tiêu đề**,
**Nội dung** (chấp nhận mã nhúng YouTube/Facebook), **Đang phát trực tiếp** (hiện nhãn đỏ
“● Trực tiếp” cạnh tiêu đề), **Class CSS (nâng cao)**. Thanh là một ô trắng dưới đầu
trang, chỉ hiện trong khoảng thời gian đặt (theo giờ của website), mọi trang trừ trang 404.

**Nâng cao → Kỹ thuật** (“Dành cho người rành kỹ thuật — nhập sai có thể ảnh hưởng
website”): **Mã Google Analytics**, **Mã chèn vào <head>**, **Mã chèn cuối trang** (website
mẫu đang chứa CSS riêng ở đây — xoá là vỡ giao diện), **Shortcode cho Quản trị viên**,
**Shortcode cho Biên tập viên**, **Shortcode cho vai trò khác**.

**Ngoài website:** thanh chia sẻ nổi bên trái (Facebook, Twitter, Email); menu điện thoại:
nút ☰ → thẻ trắng trên nền mờ, mục cha có mũi tên mở menu con, ✕ hoặc chạm nền mờ để đóng;
máy tính: mục có menu con có ▾, rê chuột hoặc dùng phím Tab để mở; menu tối đa 3 cấp.
Widget **Danh Sách Bài Viết**: Tiêu đề, **Kiểu hiển thị** (*Số thứ tự (1–X)* / *Audio (icon
play)*), **Chuyên mục**, **Số bài hiển thị (X)**, **Sắp xếp** (*Tự động theo kiểu / Xem nhiều
nhất / Mới nhất*), **Màu nền**, ☐ **Hiện ảnh thu nhỏ**, ☑ **Hiện mô tả (tóm tắt)**, ☐ **Hiện
lượt xem / ngày đăng (kiểu số thứ tự)**. Widget **Lời Chúa: Câu ghi nhớ**: **Tiêu đề (tuỳ
chọn, hiện phía trên thẻ)**, **Chế độ**, **Nhãn (vd CÂU GHI NHỚ)**, **Câu Lời Chúa**, **Trích
dẫn (vd Mt 6, 33)**, **Dynamic — lấy từ**, **Chuyên mục (cho "Mới nhất trong chuyên mục")**,
**ID bài viết (cho "Bài viết cụ thể")**, Màu nền / Màu chữ (câu) / Màu nhấn.
Bài Lời Chúa không ảnh đại diện → câu Lời Chúa hiện ở chỗ ảnh (thẻ trang chủ, lưới mosaic,
trang chuyên mục, tìm kiếm); trang bài chỉ có thẻ câu ghi nhớ ở đầu bài.
Lượt xem do plugin WP-PostViews đếm (cột “lượt xem”, chữ “views”).

## 6. Danh mục ảnh (ý nghĩa từng số)

Thư mục `screenshots/final/`. Ảnh PNG độ nét 2x. Vai trò chụp ghi trong ngoặc.

- `P01-C01-B01-01-website-cong-khai` (khách): trang chủ ngoài website. 1 đầu trang (logo +
  khẩu hiệu) · 2 thanh menu · 3 khối Lời Chúa hôm nay · 4 widget Lịch Lời Chúa ở thanh bên.
- `P01-C01-B02-01-man-hinh-dang-nhap` (khách): 1 Tên người dùng hoặc địa chỉ email · 2 Mật
  khẩu · 3 Ghi nhớ đăng nhập · 4 nút Đăng nhập · 5 Bạn quên mật khẩu?
- `P01-C01-B04-01-bang-dieu-khien` (Biên tập viên): 1 menu bên trái · 2 các thẻ lối tắt.
- `P01-C01-B05-01-menu-trai-thanh-cong-cu` (BTV): 1 thanh công cụ đen phía trên · 2 menu trái.
- `P01-C01-B06-01-xem-website` (BTV): 1 tên website trên thanh đen · 2 mục Xem website.
- `P01-C01-B07-01-dang-xuat` (BTV): 1 “Xin chào, …” góc phải · 2 Đăng xuất.
- `P01-C02-B03-01-khu-vuc-quan-ly` (Quản lý): menu trái, 1 Giao diện · 2 Plugin · 3 Tài
  khoản · 4 Cài đặt (các khu vực chỉ Quản lý thấy).
- `P02-C03-B01-01-danh-sach-bai-viet` (BTV): 1 Tất cả bài viết · 2 bảng danh sách bài.
- `P02-C03-B02-01-cot-va-trang-thai` (BTV): 1 dòng lọc trạng thái (Tất cả, Đã xuất bản, Đã
  lên lịch…) · 2 cột Tiêu đề · 3 cột Danh mục · 4 cột Thời gian · 5 nhãn “Lên kế hoạch”.
- `P02-C03-B03-01-tim-kiem` (BTV): 1 ô tìm (đã gõ “Lời Chúa”) · 2 nút Tìm các bài viết.
- `P02-C03-B04-01-loc-thang-danh-muc` (BTV): 1 Tất cả các ngày · 2 Tất cả danh mục · 3 Lọc.
- `P02-C03-B05-01-thao-tac-tren-hang` (BTV): 1 tên bài (bấm để mở) · 2 dòng Chỉnh sửa |
  Sửa nhanh | Xóa tạm | Xem trước.
- `P02-C04-B01-01-viet-bai-moi` (BTV): 1 Bài viết → Thêm Bài Viết (menu) · 2 nút Thêm Bài Viết.
- `P02-C04-B02-01-tieu-de-noi-dung` (BTV): 1 tiêu đề (đã gõ) · 2 đoạn nội dung đầu tiên.
- `P02-C04-B03-01-thanh-cong-cu` (BTV): 1 nút + · 2 Hoàn tác / Làm lại · 3 Tổng quan nội
  dung · 4 Xem · 5 Cài đặt · 6 Lưu nháp · 7 Xuất bản.
- `P02-C04-B03-02-thu-vien-khoi` (BTV): 1 nút + (đang mở) · 2 ô Tìm kiếm · 3 khối Văn bản ·
  4 khối Hình ảnh.
- `P02-C04-B04-01-thanh-cong-cu-khoi` (BTV): 1 Văn bản (đổi loại khối, VD thành Tiêu đề) ·
  2 Căn lề văn bản · 3 Đậm / Nghiêng · 4 Liên kết · 5 Tùy chọn (3 chấm: nhân bản, xoá khối…).
- `P02-C04-B09-01-bang-cai-dat` (BTV): 1 nút Cài đặt (mở/đóng cột phải) · 2 tab Bài viết /
  Khối · 3 nút Đóng cài đặt (X).
- `P02-C05-B01-01-chen-anh` (BTV): khối Hình ảnh trống: 1 Tải lên · 2 Tất cả tập tin · 3 Chèn từ URL.
- `P02-C05-B02-01-nut-anh-dai-dien` (BTV): 1 nút Đặt ảnh đại diện ở cột phải.
- `P02-C05-B02-02-chon-anh-dai-dien` (BTV): hộp Ảnh đại diện: 1 tab Tất cả tập tin · 2 ảnh
  đã chọn (dấu tích) · 3 ô Văn bản thay thế · 4 nút Đặt ảnh đại diện.
- `P02-C05-B03-01-chon-danh-muc` (BTV): 1 bảng Danh mục · 2 các ô tích danh mục · 3 liên kết
  Thêm Danh Mục.
- `P02-C05-B04-01-the` (BTV): 1 ô nhập thẻ trong bảng Thẻ.
- `P02-C05-B05-01-mo-ta-ngan` (BTV): hộp Mô tả ngắn mở ra: 1 ô gõ mô tả ngắn.
- `P02-C05-B06-01-xem-truoc` (BTV): 1 nút Xem · 2 Máy tính / Máy tính bảng / Di động ·
  3 Xem trước ở tab mới.
- `P02-C05-B09-01-luu-thay-doi` (BTV, bài đã đăng): 1 Xem bài viết · 2 nút Lưu thay đổi.
- `P02-C05-B10-01-hen-gio` (BTV): 1 chữ cạnh Xuất bản (Ngay lập tức) — bấm để mở lịch ·
  2 ô giờ · 3 lịch chọn ngày.
- `P02-C06-B01-01-sua-nhanh` (BTV): 1 ô Tiêu đề · 2 khung Danh mục · 3 ô Trạng thái ·
  4 nút Cập nhật.
- `P03-C07-B02-01-danh-sach-danh-muc` (BTV): 1 Bài viết → Danh mục · 2 bảng danh mục ·
  3 khung Thêm Danh Mục.
- `P03-C07-B03-01-them-danh-muc` (BTV): 1 Tên · 2 Đường dẫn · 3 Danh mục cha · 4 Miêu tả ·
  5 nút Thêm Danh Mục.
- `P03-C07-B05-01-sua-danh-muc` (BTV): trang Sửa danh mục: 1 Tên · 2 Đường dẫn · 3 Danh
  mục cha · 4 Miêu tả.
- `P03-C07-B07-01-xoa-danh-muc` (BTV): 1 dòng “Chỉnh sửa | Sửa nhanh | Xóa | Xem” của Giáo
  lý · 2 dòng của Suy niệm (không có Xóa vì là danh mục mặc định).
- `P03-C08-B01-01-tuy-chinh-chuyen-muc` (Quản lý): 1 công tắc Tuỳ chỉnh riêng chuyên mục
  này · 2 Số cột danh sách · 3 Kiểu trình bày · 4 Hiển thị thanh bên (sidebar) · 5 Hình đại
  diện / Mô tả ngắn / Ngày & lượt xem.
- `P04-C10-B01-01-thu-vien` (BTV): 1 menu Thư viện · 2 nút Tải tệp lên · 3 thanh lọc / tìm.
- `P04-C10-B02-01-tai-anh-moi` (BTV): 1 khung thả tệp · 2 nút Chọn tệp tin · 3 dòng kích
  thước tối đa.
- `P04-C10-B04-01-chi-tiet-anh` (BTV): 1 Mô tả SEO · 2 Tiêu đề · 3 Image Caption · 4 Miêu tả
  · 5 nút Sửa ảnh · 6 Xóa vĩnh viễn.
- `P05-C11-B02-01-danh-sach-trang` (BTV): 1 Tất cả các trang · 2 nút Thêm Trang · 3 nhãn
  “— Trang chủ”.
- `P05-C11-B04-01-sua-trang` (BTV, trang Liên hệ): 1 tiêu đề trang · 2 nút Lưu thay đổi.
- `P05-C12-B01-01-mau-trang-chu` (Quản lý, trang Trang chủ): 1 dòng Mẫu trang: Trang Chủ.
- `P05-C12-B04-01-cai-dat-doc` (Quản lý): 1 nhóm Trang chủ của bạn hiển thị.
- `P06-C13-B01-01-mo-thiet-lap` (QL): 1 menu Chính Tòa Media · 2 mục Thiết lập giao diện.
- `P06-C13-B02-01-bon-nhom` (QL): 1 Giao diện · 2 Trang chủ & Bài viết · 3 Tiện ích ·
  4 Nâng cao · 5 các tab con (Màu sắc & hiển thị, Header, Footer).
- `P06-C13-B04-01-da-luu` (QL): 1 thông báo “Đã lưu thay đổi.”
- `P06-C14-B01-01-mau-giao-dien` (QL): 1 Màu giao diện · 2 Màu tự chọn.
- `P06-C14-B01-02-nen-trang` (QL): 1 Nền trang · 2 Màu nền.
- `P06-C14-B02-01-kieu-thanh-menu` (QL): 1 Kiểu thanh menu · 2 Màu nền thanh menu · 3 Màu
  chữ thanh menu · 4 Màu nhấn thanh menu.
- `P06-C14-B02-02-bon-kieu-menu`: bảng so sánh 4 kiểu thanh menu (Kiểu 1–4), không có số.
- `P06-C14-B03-01-chon-kieu-header` (QL): 1 tab con Header · 2 Kiểu Header (3 lựa chọn).
- `P06-C14-B04-01-header-logo-khau-hieu` (QL): 1 tab con Header · 2 Kiểu Header · 3 Logo ·
  4 Câu khẩu hiệu + Trích dẫn · 5 ba ô màu · 6 Lưu thay đổi.
- `P06-C14-B04-02-header-ngoai-website` (khách): 1 logo · 2 câu khẩu hiệu + trích dẫn · 3 thanh menu.
- `P06-C14-B05-01-header-tu-nhap` (QL): 1 Kiểu Header (Tự nhập nội dung) · 2 Nội dung
  Header · 3 Màu nền Header · 4 Lưu thay đổi.
- `P06-C14-B06-01-header-banner` (QL): 1 Kiểu Header (Ảnh banner rộng) · 2 ba ô ảnh (Máy
  tính, Máy tính bảng, Điện thoại) · 3 Mô tả ảnh · 4 Liên kết khi bấm + Mở liên kết ở tab
  mới · 5 Lưu thay đổi.
- `P06-C14-B07-01-footer` (QL): 1 tab con Footer · 2 Nội dung cuối trang · 3 Màu nền + Màu
  chữ cuối trang · 4 Lưu thay đổi.
- `P06-C14-B08-01-cot-widget-footer` (QL): 1 Hiển thị cột Widget · 2 Số cột Widget.
- `P06-C15-B01-01-thanh-ben-trang-chu` (QL): 1 tab Trang chủ & Bài viết · 2 tab con Trang
  chủ · 3 Hiển thị thanh bên · 4 Vị trí thanh bên.
- `P06-C15-B02-01-khoi-noi-bat` (QL): 1 Hiển thị khối Nổi bật · 2 Kiểu khối Nổi bật · 3 các
  ô Tin Hot · 4 các ô Tiêu điểm.
- `P06-C15-B03-01-box-5-phut` (QL): 1 Hiển thị box "5 phút" · 2 Tiêu đề box · 3 Chuyên mục
  nguồn · 4 ba ô Hiện ở….
- `P06-C15-B04-01-danh-sach-khoi` (QL): 6 khối đã thu gọn: 1 biểu tượng ✥ · 2 mũi tên thu
  gọn/mở · 3 ô chọn mẫu · 4 Xoá khối · 5 + Thêm khối.
- `P06-C15-B05-01-chin-mau-khoi`: bảng 9 mẫu khối (tên + hình), không có số.
- `P06-C15-B06-01-chi-tiet-khoi` (QL, khối “Từ vựng” mẫu Danh sách bài 4): 1 ô chọn mẫu ·
  2 Thông tin khối · 3 Nguồn bài viết · 4 Hiển thị trên thẻ bài.
- `P06-C15-B07-01-khoi-loi-chua-hom-nay` (QL): 1 ô chọn mẫu (Lời Chúa hôm nay) · 2 Tiêu đề
  khối · 3 Nguồn bài viết (Chuyên mục).
- `P06-C15-B07-02-loi-chua-hom-nay-ngoai-website` (khách): 1 khối Lời Chúa hôm nay · 2 widget
  Lịch Lời Chúa.
- `P06-C15-B08-01-mosaic-ngoai-website` (khách): khối “Bài viết mới” mẫu Lưới ảnh mosaic, không có số.
- `P06-C15-B10-01-mau-va-xem-them` (QL): 1 Dùng màu mặc định (Không) · 2 Màu nền / Màu chữ /
  Màu nhấn · 3 Hiện nút (Có) · 4 Chữ trên nút / Liên kết / Mở tab mới.
- `P06-C15-B12-01-bo-cuc-chuyen-muc` (QL): 1 Số cột danh sách · 2 Kiểu trình bày · 3 Hiển thị thanh bên.
- `P06-C15-B13-01-bo-cuc-bai-viet` (QL): 1 Hiển thị thanh bên (nhóm Trang Bài viết).
- `P06-C16-B01-01-thanh-thong-bao` (QL): 1 tab Tiện ích · 2 Bật thanh thông báo · 3 Bắt đầu
  / Kết thúc hiển thị · 4 Tiêu đề · 5 Nội dung · 6 Đang phát trực tiếp · 7 Lưu thay đổi.
- `P06-C16-B04-01-nang-cao` (QL): 1 Mã Google Analytics · 2 Mã chèn vào <head> + Mã chèn
  cuối trang · 3 ba ô Shortcode.
- `P07-C17-B01-01-vi-tri-menu` (QL): 1 tab Quản lý vị trí menu · 2 ô chọn menu cho Menu Chính.
- `P07-C17-B02-01-cau-truc-menu` (QL): 1 Chọn menu để sửa + Chọn · 2 cột Thêm các mục menu ·
  3 Tên menu · 4 Cấu trúc menu.
- `P07-C17-B03-01-them-muc-menu` (QL): 1 nhóm Danh mục (đang mở) · 2 các ô tích · 3 Thêm vào menu.
- `P07-C17-B04-01-sua-muc-menu` (QL, mục “Suy niệm” mở ra): 1 mũi tên mở mục · 2 Nhãn Điều
  Hướng · 3 Xóa bỏ.
- `P07-C17-B06-01-menu-con` (QL): 1 mục cha Giáo lý · 2 ba mục con (lùi vào, chữ “mục phụ”).
- `P07-C17-B07-01-lien-ket-tu-tao` (QL): 1 URL · 2 Văn bản liên kết · 3 Thêm vào menu.
- `P07-C17-B09-01-thiet-lap-menu` (QL): 1 Tự động thêm trang · 2 Vị trí menu: Menu Chính ·
  3 nút Lưu menu.
- `P07-C17-B10-01-menu-dien-thoai` (khách, điện thoại): 1 nút ✕ đóng menu (khi đóng là ☰) ·
  2 mục Giáo lý đang mở menu con.
- `P07-C17-B10-02-menu-may-tinh` (khách, máy tính): 1 mục Huấn quyền · 2 menu con thả xuống.
- `P07-C18-B01-01-khu-vuc-widget` (QL): 1 Cấu hình cột tiện ích · 2 Tiện ích sẵn có · 3 các khu vực.
- `P07-C18-B04-01-widget-danh-sach-bai-viet` (QL, widget “Bài xem nhiều”): 1 Tiêu đề ·
  2 Kiểu hiển thị · 3 Chuyên mục / Số bài / Sắp xếp · 4 nút Lưu thay đổi (đang xám “Đã lưu”).
- `P07-C18-B06-01-widget-cau-ghi-nho` (QL, khu vực Chuyên Mục): 1 Chế độ · 2 Nhãn / Câu Lời
  Chúa / Trích dẫn · 3 Dynamic — lấy từ · 4 nút Lưu thay đổi.
- `P07-C18-B07-01-widget-lich` (QL): 1 Tiêu đề · 2 Chuyên mục nguồn (+ ghi chú) · 3 nút Lưu thay đổi.
- `P07-C18-B08-01-thanh-ben-ngoai-website` (khách): 1 Lịch Lời Chúa · 2 Liên kết (widget
  Menu) · 3 Bài xem nhiều (Danh Sách Bài Viết).
- `P08-C19-B03-01-bai-loi-chua` (BTV, hộp Phân loại bài viết): 1 Loại bài viết: Lời Chúa
  (câu ghi nhớ) · 2 Câu Lời Chúa · 3 Trích dẫn. (Bên dưới thấy Ngày phụng vụ, Lễ / kính
  thánh, Đoạn Tin Mừng không đánh số.)
- `P08-C19-B03-02-loi-chua-ngoai-website` (khách): 1 thẻ câu ghi nhớ ở đầu bài.
- `P08-C19-B05-01-ngay-phung-vu` (BTV): 1 Ngày phụng vụ · 2 Lễ / kính thánh · 3 Đoạn Tin Mừng.
- `P08-C19-B06-01-hen-gio-loi-chua` (BTV, bài hẹn 31/10): 1 Trạng thái: Đã lên lịch ·
  2 Xuất bản: Tháng 10 31 5:00 sáng · 3 nút (Lưu thay đổi).
- `P08-C19-B07-01-bai-video` (BTV): 1 Loại bài viết: Video (audio) Lời Chúa · 2 Link video/audio.
- `P08-C20-B01-01-tim-khoi-loi-chua` (BTV): 1 nút + · 2 ô Tìm kiếm (gõ “Lời Chúa”) ·
  3 khối Lời Chúa: Câu ghi nhớ.
- `P08-C20-B02-01-cai-dat-khoi-loi-chua` (BTV): 1 khối trong bài · 2 bảng Nguồn nội dung ·
  3 bảng Nội dung thẻ.
- `P08-C20-B03-01-tuy-chinh-bai-viet` (QL): 1 Tuỳ chỉnh riêng bài này · 2 Thanh bên
  (sidebar) · 3 các công tắc Hình đại diện … Ngày & lượt xem.
- `P08-C20-B06-01-luot-xem` (QL): 1 cột “lượt xem” trong danh sách trang.
- `P09-C21-B01-01-ho-so` (BTV): nhóm Tên: 1 First Name + Last Name · 2 Nickname (bắt buộc) ·
  3 Hiển thị tên công khai như.
- `P09-C21-B03-01-doi-mat-khau` (BTV): 1 nút Đặt mật khẩu mới · 2 ô mật khẩu mới (đã làm mờ).
- `P09-C21-B04-01-quen-mat-khau` (khách): 1 ô Tên người dùng hoặc địa chỉ email · 2 nút Lấy mật khẩu mới.
- `P09-C22-B01-01-danh-sach-nguoi-dung` (QL, tên đã làm mờ): 1 Tất cả người dùng · 2 cột
  Vai trò · 3 Đổi thành… + Thay đổi.
- `P09-C22-B02-01-them-nguoi-dung` (QL): 1 Tên người dùng · 2 Email · 3 Mật khẩu · 4 Gửi
  thông báo · 5 Vai trò · 6 nút Thêm người dùng.
- `PHU-LUC-B-01-yoast` (QL): 1 bảng Yoast SEO ở cột phải (Phân tích SEO, Phân tích khả năng
  đọc, nút Cải tiến bài viết của bạn với Yoast SEO).
- `PHU-LUC-C-01-nut-elementor` (BTV): 1 nút Sửa với Elementor.
- `PHU-LUC-E-01-plugin` (QL): danh sách Plugin, 1 menu Plugin.
- `PHU-LUC-E-02-cau-truc-duong-dan` (QL): 1 Cài đặt cơ bản (Cấu trúc đường dẫn cố định).

## 7. Những điểm đã đổi so với bản 05/09/2026 (phải phản ánh trong bài)

- Theme 1.1.0: Header kiểu **Logo + khẩu hiệu**; menu kiểu 4; khối **Lời Chúa hôm nay** và
  **Lưới ảnh mosaic**; widget **Lịch Lời Chúa**; 3 ô Ngày phụng vụ / Lễ / kính thánh / Đoạn
  Tin Mừng; bài Lời Chúa không ảnh tự hiện câu Lời Chúa; menu điện thoại mới.
- Đã sửa trong 1.1.0: “Mã chèn vào <head>” nay hoạt động; “Mô tả ảnh” của banner nay
  dùng được; “Mở liên kết ở tab mới” tắt thì mở cùng tab; “Đang phát trực tiếp” hiện nhãn;
  “Tin Hot — Trong vòng (ngày)” lọc đúng số ngày; box “5 phút” chọn đúng chuyên mục và chỉ
  hiện đúng nơi; hai khối trùng tiêu đề không còn hiện chung bài; Tabs chuyên mục không cần
  tiêu đề; số bài tối thiểu 1; lưu Thiết lập giao diện có thông báo và nhớ tab; Biên tập
  viên không thấy thẻ Thiết lập giao diện; Màu sắc khối có nhãn rõ; iframe (video nhúng)
  được giữ trong ô Nội dung; nhóm “Biểu tượng & màu sắc” của chuyên mục bị ẩn; nút nổi
  “Giờ Thánh Lễ” bị ẩn. **Không viết các lỗi cũ như là hành vi hiện tại.**
- WordPress 7.1: nút đăng bài là **Xuất bản** (không phải “Đăng”); cập nhật bài đã đăng là
  **Lưu thay đổi**; “Danh mục” (không phải “Chuyên mục”) trong trang quản trị;
  “Xóa tạm” (không phải “Bỏ vào thùng rác”); khối đoạn văn tên **Văn bản**; ô văn bản thay
  thế trong Thư viện tên **Mô tả SEO**.
- Bỏ khái niệm “dữ liệu mẫu [TÀI LIỆU MẪU]” và “hoàn nguyên”: người đọc thực hành bằng
  **bản nháp** của chính họ; muốn thử thiết lập giao diện thì ghi lại giá trị cũ (chụp màn
  hình) trước khi đổi.
