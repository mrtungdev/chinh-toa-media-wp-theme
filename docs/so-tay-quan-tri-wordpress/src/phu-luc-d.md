# Phần phụ lục

## Phụ lục D UpdraftPlus tùy chọn

UpdraftPlus là plugin sao lưu (tạo bản sao dự phòng của website). Trên website mẫu, UpdraftPlus **đang tắt**: plugin có trong danh sách nhưng chưa được kích hoạt. Phụ lục này dùng khi website của bạn có cài và bật UpdraftPlus. Việc bật plugin là việc của người phụ trách kỹ thuật.

### Bài D.01 Hiểu bản sao lưu dùng để làm gì

**Nhãn:** Kỹ thuật dành cho quản trị viên · 🟡 Cẩn thận

#### Mục đích

Hiểu bản sao lưu gồm những gì và dùng khi nào. Bản sao lưu dùng cho sự cố lớn của cả website, không dùng để lấy lại một bài.

#### Trước khi bắt đầu

Đăng nhập bằng tài khoản **Quản lý**. Bài này chỉ để hiểu, không bấm nút nào.

#### Các bước thực hiện

1. **Bước 1:** Nhớ phần thứ nhất của bản sao lưu là **Cơ sở dữ liệu**: chứa bài viết, trang, danh mục, tài khoản và thiết lập.
2. **Bước 2:** Nhớ phần thứ hai là **Tệp**: chứa ảnh đã tải lên, giao diện và plugin.
3. **Bước 3:** Hỏi người phụ trách kỹ thuật website đang được sao lưu bằng cách nào: bằng UpdraftPlus hay do nơi thuê máy chủ (hosting) làm.
4. **Bước 4:** Ghi lại ba điều: lần sao lưu gần nhất, bản sao cất ở đâu, ai giữ quyền lấy lại.
5. **Bước 5:** Khi lỡ xoá một bài hay sửa sai một đoạn, dùng **Thùng rác** hoặc **Lịch sử sửa** (Bài 23.08, Bài 06.04). Không dùng bản sao lưu.

#### Ảnh minh họa

Bài này không cần ảnh minh họa.

#### Kết quả

Bạn sẽ biết website có bản sao lưu hay không, cất ở đâu, và ai lo việc này.

#### Lưu ý

- Bản sao lưu cất ngay trên máy chủ của website có thể mất cùng website khi máy chủ hỏng. Nên có thêm bản cất ở nơi khác.
- Lấy lại bản sao lưu sẽ đưa cả website về thời điểm sao lưu. Mọi bài viết sau thời điểm đó có thể mất.

#### Không nên làm

- Không tự bật plugin UpdraftPlus đang tắt.
- Không coi bản sao lưu là cách sửa lỗi nhỏ.

#### Nếu gặp lỗi

- Không ai biết website có sao lưu không → báo người chịu trách nhiệm website. Đây là việc cần làm rõ sớm.
- Người phụ trách kỹ thuật cũ đã nghỉ, không ai giữ bản sao lưu → hỏi nơi thuê máy chủ của website.

### Bài D.02 Kiểm tra trạng thái bản sao lưu

**Nhãn:** Kỹ thuật dành cho quản trị viên · 🟡 Cẩn thận

#### Mục đích

Xem lần sao lưu gần nhất là khi nào và lần sau sẽ chạy lúc nào. Chỉ xem, không thay đổi gì.

#### Trước khi bắt đầu

Đăng nhập bằng tài khoản **Quản lý**. Không bấm bất cứ nút nào trong trang UpdraftPlus khi chỉ kiểm tra.

#### Các bước thực hiện

1. **Bước 1:** Mở **Plugin → Plugin đã cài đặt**.
2. **Bước 2:** Tìm dòng **UpdraftPlus - Sao lưu/Khôi phục**. Nếu dưới tên có liên kết **Kích hoạt**, plugin đang tắt. Dừng ở đây và hỏi người phụ trách kỹ thuật.
3. **Bước 3:** Nếu plugin đang bật, mở **Cài đặt → UpdraftPlus Backups**.
4. **Bước 4:** Bấm tab **Sao lưu / Khôi phục**.
5. **Bước 5:** Đọc phần **Các bản sao lưu được lên lịch tiếp theo**, cho cả **Tệp** và **Cơ sở dữ liệu**.
6. **Bước 6:** Kéo xuống phần **Bản sao lưu hiện có**. Ghi lại ngày của bản mới nhất.

#### Ảnh minh họa

Bài này không cần ảnh minh họa. Dòng UpdraftPlus trong danh sách plugin xem Hình E.02.

#### Kết quả

Bạn sẽ biết ngày của bản sao lưu gần nhất và lịch sao lưu tiếp theo.

#### Lưu ý

- Tên nút và tab có thể khác một chút tùy phiên bản UpdraftPlus.
- Nếu thấy dòng “Hiện tại chưa có gì được lên lịch”, website không tự sao lưu. Báo người phụ trách kỹ thuật.

#### Không nên làm

- Không bấm **Khôi Phục** hay **Xóa** cạnh bất kỳ bản sao lưu nào.
- Không bấm **Kích hoạt** plugin khi chưa được người phụ trách kỹ thuật đồng ý.

#### Nếu gặp lỗi

- Bản sao lưu mới nhất đã cũ hơn một tháng → báo người phụ trách kỹ thuật trước khi làm thay đổi lớn nào.
- Không thấy mục **UpdraftPlus Backups** trong **Cài đặt** → plugin đang tắt hoặc chưa cài. Hỏi người phụ trách kỹ thuật.
- Trang UpdraftPlus báo lỗi màu đỏ → chụp màn hình và gửi người phụ trách kỹ thuật.

### Bài D.03 Tạo bản sao lưu thủ công trước thay đổi lớn

**Nhãn:** Kỹ thuật dành cho quản trị viên · 🟡 Cẩn thận

#### Mục đích

Tạo một bản sao lưu ngay trước khi làm việc lớn, ví dụ đổi nhiều thiết lập giao diện. Nếu có sự cố, người phụ trách kỹ thuật có điểm để đưa website về.

#### Trước khi bắt đầu

- Website có cài và bật UpdraftPlus (Bài D.02).
- Người phụ trách kỹ thuật đồng ý cho bạn tạo bản sao lưu.
- Làm vào lúc ít người xem website, ví dụ buổi tối.

#### Các bước thực hiện

1. **Bước 1:** Mở **Cài đặt → UpdraftPlus Backups**, tab **Sao lưu / Khôi phục**.
2. **Bước 2:** Bấm nút **Sao Lưu Ngay**.
3. **Bước 3:** Trong hộp hiện ra, giữ dấu tích ở ô **Bao gồm cơ sở dữ liệu của bạn trong bản sao lưu**.
4. **Bước 4:** Giữ dấu tích ở ô **Bao gồm các tệp của bạn trong bản sao lưu**.
5. **Bước 5:** Bấm nút **Sao Lưu Ngay** trong hộp đó.
6. **Bước 6:** Giữ trang mở và chờ đến khi thanh tiến trình chạy xong.
7. **Bước 7:** Kéo xuống **Bản sao lưu hiện có** và kiểm tra có bản mới với ngày giờ hôm nay.

#### Ảnh minh họa

Bài này không cần ảnh minh họa.

#### Kết quả

Bạn sẽ thấy một bản sao lưu mới trong **Bản sao lưu hiện có**, ghi ngày giờ vừa tạo.

#### Lưu ý

- Website có nhiều ảnh thì sao lưu có thể mất vài phút đến vài chục phút.
- Ô **Gửi bản sao lưu này đến lưu trữ từ xa** chỉ có tác dụng khi người phụ trách kỹ thuật đã cài sẵn nơi cất bên ngoài. Giữ như mặc định.
- Tạo xong bản sao lưu chưa chắc đã lấy lại được. Người phụ trách kỹ thuật cần kiểm tra định kỳ.

#### Không nên làm

- Không bắt đầu thay đổi lớn khi bản sao lưu còn đang chạy hoặc báo lỗi.
- Không bấm **Sao Lưu Ngay** nhiều lần liên tiếp. Máy chủ có thể bị đầy.

#### Nếu gặp lỗi

- Sao lưu dừng giữa chừng hoặc báo lỗi → không thử lại liên tục. Chụp màn hình và báo người phụ trách kỹ thuật.
- Không thấy bản mới trong **Bản sao lưu hiện có** → tải lại trang (F5). Vẫn không có thì hoãn thay đổi lớn và báo người phụ trách kỹ thuật.

### Bài D.04 Không tự khôi phục bản sao lưu

**Nhãn:** Kỹ thuật dành cho quản trị viên · 🔴 Không tự thay đổi

#### Mục đích

Biết vì sao không tự bấm khôi phục. Khôi phục ghi đè cả website và có thể làm mất bài viết mới.

#### Trước khi bắt đầu

Bài này chỉ để nhận biết. Việc khôi phục do người phụ trách kỹ thuật làm, theo kế hoạch.

#### Các bước thực hiện

1. **Bước 1:** Khi website gặp sự cố, dừng thao tác và làm theo Bài 24.07.
2. **Bước 2:** Không bấm **Khôi Phục** trong trang UpdraftPlus.
3. **Bước 3:** Báo người phụ trách kỹ thuật: sự cố bắt đầu lúc nào, những bài nào mới đăng gần đây.
4. **Bước 4:** Ghi lại các bài đã đăng sau lần sao lưu gần nhất. Các bài này có thể cần đăng lại sau khi khôi phục.
5. **Bước 5:** Chờ người phụ trách kỹ thuật báo xong rồi mới đăng nhập làm việc tiếp.

#### Ảnh minh họa

Bài này không cần ảnh minh họa.

#### Kết quả

Bạn sẽ thấy việc khôi phục, nếu cần, được người phụ trách kỹ thuật làm có kế hoạch. Bài viết mới không bị mất ngoài ý muốn.

#### Lưu ý

- Khôi phục đưa bài viết, tài khoản và thiết lập về thời điểm sao lưu. Mọi thay đổi sau đó có thể mất.
- Lỗi của một bài hay một ảnh không bao giờ cần khôi phục cả website.

#### Không nên làm

- Không khôi phục để sửa một bài viết hay một ảnh.
- Không bấm tiếp khi UpdraftPlus hiện cảnh báo.

#### Nếu gặp lỗi

- Lỡ bấm **Khôi Phục** → không bấm thêm gì, không đóng trang. Gọi ngay người phụ trách kỹ thuật.
- Sau khi khôi phục thấy thiếu bài mới → đối chiếu với danh sách đã ghi ở Bước 4 và đăng lại từng bài.
