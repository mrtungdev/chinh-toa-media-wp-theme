# Phần 10 Xử lý vấn đề thường gặp

## Chương 24 Lỗi giao diện và truy cập

### Bài 24.01 Menu đã lưu nhưng ngoài website chưa đổi

**Nhãn:** Kỹ thuật dành cho quản trị viên · 🟡 Cẩn thận

#### Mục đích

Tìm lý do đã sửa menu mà thanh menu ngoài website vẫn như cũ. Kiểm tra lần lượt: đúng menu, đúng vị trí, đã lưu, rồi mới tới trình duyệt.

#### Trước khi bắt đầu

- Đăng nhập bằng tài khoản **Quản lý**.
- Mở **Giao diện → Thiết lập Menu**.
- Biết mục menu nào đang thiếu hoặc sai.

#### Các bước thực hiện

1. **Bước 1:** Kiểm tra dòng **Chọn menu để sửa:**. Menu đang mở phải là **Menu chính**, không phải **Liên kết**. Nếu sai, chọn **Menu chính** rồi bấm **Chọn**.
2. **Bước 2:** Kiểm tra khung **Cấu trúc menu** có mục vừa thêm hoặc vừa sửa.
3. **Bước 3:** Kiểm tra dòng **Vị trí menu** ở cuối trang. Ô **Menu Chính** phải có dấu tích (Hình 17.09, số 2).
4. **Bước 4:** Bấm **Lưu menu** (Hình 17.09, số 3). Chờ dòng thông báo “Menu chính đã cập nhật.”
5. **Bước 5:** Mở website và tải lại trang (F5).
6. **Bước 6:** Nếu vẫn chưa đổi, mở website bằng cửa sổ ẩn danh.
7. **Bước 7:** Trên điện thoại, bấm nút ☰ để mở menu. Mục con nằm trong mũi tên của mục cha.

#### Ảnh minh họa

Xem Hình 17.09: số 1 là **Tự động thêm trang** (nên để trống), số 2 là ô **Menu Chính** trong **Vị trí menu**, số 3 là nút **Lưu menu**.

#### Kết quả

Bạn sẽ thấy thanh menu ngoài website có đúng các mục và đúng thứ tự như trong **Cấu trúc menu**.

#### Lưu ý

- Menu chỉ hiện tối đa 3 cấp. Mục ở cấp thứ tư sẽ không hiện ngoài website.
- Menu **Liên kết** không nằm trên thanh menu. Menu này hiện ở thanh bên qua widget **Menu** (Bài 18.03).
- Mỗi lần sửa xong phải bấm **Lưu menu**. Rời trang khi chưa lưu là mất thay đổi.

#### Không nên làm

- Không xoá menu rồi tạo lại để “làm mới”. Liên kết **Xóa menu** xoá cả menu.
- Không gán **Menu Chính** cho menu **Liên kết**.

#### Nếu gặp lỗi

- Thanh menu biến mất hẳn → ô **Menu Chính** đã bị bỏ tích. Tích lại ô này ở **Vị trí menu** và bấm **Lưu menu**.
- Mục mới hiện ở cuối thanh menu, sai chỗ → kéo mục đó trong **Cấu trúc menu** về đúng vị trí (Bài 17.05) rồi lưu.
- Thanh menu có thêm trang lạ mà bạn không thêm → ô **Tự động thêm trang** đang được tích. Bỏ tích, gỡ mục lạ (Bài 17.08) và lưu.

### Bài 24.02 Khối trang chủ không có bài để hiển thị

**Nhãn:** Kỹ thuật dành cho quản trị viên · 🟡 Cẩn thận

#### Mục đích

Tìm lý do một khối ở trang chủ trống, thiếu bài hoặc không hiện. Hầu hết do chọn sai chuyên mục hoặc chuyên mục chưa có bài đã đăng.

#### Trước khi bắt đầu

- Đăng nhập bằng tài khoản **Quản lý**.
- Mở **Chính Tòa Media → Thiết lập giao diện → Trang chủ & Bài viết → Trang chủ**.
- Kéo xuống **Khối nội dung trang chủ** và bấm mũi tên để mở khối bị lỗi.

#### Các bước thực hiện

1. **Bước 1:** Kiểm tra nhóm **Nguồn bài viết**. Trong ô **Chuyên mục**, phải có ít nhất một tên được tô xám (đang chọn).
2. **Bước 2:** Kiểm tra chuyên mục đó có bài đã đăng. Mở **Bài viết → Danh mục** và xem cột **Lượt** (số bài) của chuyên mục.
3. **Bước 3:** Kiểm tra ô **Số bài hiển thị**. Số này cho biết khối hiện mấy bài, từ 1 đến 30.
4. **Bước 4:** Kiểm tra nhóm **Thông tin khối**. Ô **Hiển thị khối** phải là **Có**.
5. **Bước 5:** Kiểm tra ô **Chỉ Admin thấy**. Nếu là **Có**, chỉ Quản lý đang đăng nhập thấy khối, khách không thấy.
6. **Bước 6:** Sửa ô sai, kéo xuống cuối trang và bấm **Lưu thay đổi**.
7. **Bước 7:** Mở trang chủ và tải lại (F5).

#### Ảnh minh họa

Xem Hình 15.06: số 2 là nhóm **Thông tin khối** (Tiêu đề khối, Hiển thị khối, Chỉ Admin thấy), số 3 là nhóm **Nguồn bài viết** (Chuyên mục, Số bài hiển thị).

#### Kết quả

Bạn sẽ thấy thông báo xanh “Đã lưu thay đổi.” Khối ngoài trang chủ có lại bài. Trang chủ cập nhật ngay, không phải chờ.

#### Lưu ý

- Hai khối cùng chọn một chuyên mục sẽ hiện cùng bài. Kiểm tra ô **Chuyên mục** của từng khối.
- Bài nháp và bài hẹn giờ chưa tới giờ không hiện trong khối.
- Các khối chỉ hiện trên trang dùng mẫu trang **Trang Chủ** đang được đặt làm trang chủ (Bài 12.01).
- Ghi lại giá trị cũ (chụp màn hình) trước khi sửa (Bài 13.03).

#### Không nên làm

- Không đăng bài tạm vô nghĩa chỉ để khối có bài.
- Không bấm **Xoá khối** để làm lại khối từ đầu.

#### Nếu gặp lỗi

- Bấm **Lưu thay đổi** mà không thấy thông báo xanh → thay đổi chưa được lưu. Tải lại trang thiết lập, sửa lại và bấm lưu lần nữa.
- Khối hiện khi bạn đăng nhập nhưng khách không thấy → ô **Chỉ Admin thấy** đang là **Có**. Đổi sang **Không** và lưu.
- Khối Tabs có tab trống → kiểm tra chuyên mục của tab đó trong **Danh sách Tabs** (Bài 15.08).

### Bài 24.03 Widget không hiện đúng khu vực

**Nhãn:** Kỹ thuật dành cho quản trị viên · 🟡 Cẩn thận

#### Mục đích

Tìm lý do một widget (ô nhỏ ở thanh bên) không hiện, hoặc hiện sai trang. Mỗi loại trang dùng một khu vực widget riêng.

#### Trước khi bắt đầu

- Đăng nhập bằng tài khoản **Quản lý**.
- Biết widget cần hiện ở đâu: trang chủ, trang bài viết, trang chuyên mục hay cuối trang.
- Mở **Giao diện → Cấu hình cột tiện ích**.

#### Các bước thực hiện

1. **Bước 1:** Tìm widget trong các khu vực bên phải màn hình.
2. **Bước 2:** Kiểm tra đúng khu vực: **Trang Chủ** cho trang chủ, **Bài Viết Chi Tiết** cho trang bài, **Chuyên Mục** cho trang chuyên mục, **Cuối Trang - Cột 1…4** cho cuối trang.
3. **Bước 3:** Kiểm tra widget có nằm trong **Tiện ích không sử dụng** không. Widget ở đây không hiện ngoài website. Kéo về đúng khu vực.
4. **Bước 4:** Bấm mũi tên của widget để mở. Nút **Lưu thay đổi** phải là nút xám **Đã lưu**. Nếu nút còn xanh, bấm để lưu.
5. **Bước 5:** Kiểm tra thanh bên có đang bật. Trang chủ xem Bài 15.01; trang chuyên mục và trang bài xem Bài 15.12, Bài 15.13.
6. **Bước 6:** Với widget cuối trang, kiểm tra **Hiển thị cột Widget** đang bật và **Số cột Widget** đủ (Bài 14.08).
7. **Bước 7:** Mở website, tải lại trang (F5) và kiểm tra.

#### Ảnh minh họa

Xem Hình 18.01: số 1 là **Cấu hình cột tiện ích** ở menu trái, số 2 là **Tiện ích sẵn có**, số 3 là các khu vực widget (Trang Chủ, Chuyên Mục, Bài Viết Chi Tiết).

#### Kết quả

Bạn sẽ thấy widget nằm đúng thanh bên của loại trang cần hiện. Ví dụ, trang chủ có **Lịch Lời Chúa**, **Liên kết** và **Bài xem nhiều**.

#### Lưu ý

- Widget đặt ở **Cuối Trang - Cột 4** sẽ không hiện khi **Số cột Widget** chỉ là 3.
- Một bài hoặc một chuyên mục có thể bật tùy chỉnh riêng và tắt thanh bên (Bài 20.04, Bài 08.02).
- Trên điện thoại, thanh bên không nằm bên cạnh mà thường xuống dưới nội dung. Kéo xuống để tìm.

#### Không nên làm

- Không thêm một widget thứ hai giống hệt trước khi tìm kỹ widget cũ ở mọi khu vực.
- Không kéo widget về khung **Tiện ích sẵn có** khi chỉ muốn kiểm tra. Làm vậy là xoá widget cùng các ô đã cài.

#### Nếu gặp lỗi

- Widget hiện ở trang chủ nhưng không có ở trang bài → widget chỉ nằm ở khu vực **Trang Chủ**. Thêm widget vào **Bài Viết Chi Tiết** nếu cần (Bài 18.03).
- Widget hiện nhưng trống, không có bài → kiểm tra ô chuyên mục trong widget (Bài 18.04, Bài 18.07).
- Lỡ kéo nhầm widget ra ngoài → tìm trong **Tiện ích không sử dụng** và kéo về chỗ cũ. Kiểm tra lại các ô rồi lưu.

### Bài 24.04 Video hoặc audio không phát

**Nhãn:** Nên biết · 🟡 Cẩn thận

#### Mục đích

Tìm lý do trình phát không hiện hoặc không phát trong bài **Video (audio) Lời Chúa**. Kiểm tra loại bài, đường link, rồi tới quyền của video.

#### Trước khi bắt đầu

- Mở bài video trong trình soạn bài.
- Có sẵn đường link gốc của video (YouTube, Vimeo…).

#### Các bước thực hiện

1. **Bước 1:** Kiểm tra hộp **Phân loại bài viết**. Ô **Loại bài viết** phải là **Video (audio) Lời Chúa** (Hình 19.07, số 1).
2. **Bước 2:** Kiểm tra ô **Link video/audio** (số 2) có đường link đầy đủ, bắt đầu bằng `https://`.
3. **Bước 3:** Kiểm tra nguồn link. Trình phát chỉ hiện với link từ trang chia sẻ video như YouTube, Vimeo hoặc SoundCloud.
4. **Bước 4:** Mở link gốc trong cửa sổ ẩn danh. Nếu video báo riêng tư hoặc đã bị gỡ, website cũng không phát được.
5. **Bước 5:** Hỏi chủ kênh video có cho phép nhúng (đặt video lên website khác) không. Video bị tắt nhúng sẽ báo không phát được.
6. **Bước 6:** Sửa link nếu cần và bấm **Lưu thay đổi**.
7. **Bước 7:** Mở bài ngoài website, tải lại (F5) và bấm nút phát.

#### Ảnh minh họa

Xem Hình 19.07: số 1 là ô **Loại bài viết** đang chọn **Video (audio) Lời Chúa**, số 2 là ô **Link video/audio**.

#### Kết quả

Bạn sẽ thấy trình phát ở đầu trang bài, ngay chỗ ảnh đại diện. Bấm nút phát thì video hoặc audio chạy.

#### Lưu ý

- Trình phát chỉ hiện trong trang bài. Ở trang chủ, thẻ bài vẫn dùng ảnh đại diện.
- Video YouTube để chế độ “Không công khai” vẫn nhúng được. Video “Riêng tư” thì không.
- Trình duyệt thường chặn tự phát. Người xem cần bấm nút phát.

#### Không nên làm

- Không dán link Facebook, Google Drive hay link tệp `.mp3`. Các link này thường không hiện trình phát.
- Không dán đoạn mã nhúng lạ vào thân bài để “ép” video chạy.

#### Nếu gặp lỗi

- Không có trình phát, chỉ thấy ảnh đại diện → link không nhúng được. Mở video trên YouTube, bấm **Chia sẻ**, chép link và dán lại vào ô **Link video/audio**.
- Trình phát hiện nhưng báo video không xem được → video riêng tư, bị gỡ hoặc bị tắt nhúng. Báo người quản lý kênh video.
- Phát được trên máy tính nhưng không phát trên điện thoại → thử trình duyệt khác. Còn lỗi thì gửi địa chỉ bài và tên máy cho người phụ trách kỹ thuật.

### Bài 24.05 Khối Lời Chúa hôm nay không đổi sang bài mới

**Nhãn:** Nên biết · 🟢 An toàn

#### Mục đích

Tìm lý do khối **Lời Chúa hôm nay** ở trang chủ vẫn hiện bài của hôm qua. Khối lấy bài theo **ngày đăng**, nên hầu hết lỗi nằm ở ngày đăng hoặc danh mục của bài hôm nay.

#### Trước khi bắt đầu

- Biết hôm nay là ngày nào, và tên bài Lời Chúa của hôm nay.
- Mở **Bài viết → Tất cả bài viết** và tìm bài đó.

#### Các bước thực hiện

1. **Bước 1:** Kiểm tra bài của hôm nay có tồn tại không. Nếu chưa có, khối sẽ hiện bài gần nhất trước đó. Đây là điều bình thường.
2. **Bước 2:** Mở bài. Kiểm tra **Trạng thái** (Hình 19.06, số 1). **Bản nháp** là chưa đăng. **Đã lên lịch** là chưa tới giờ hẹn.
3. **Bước 3:** Kiểm tra **Xuất bản** (Hình 19.06, số 2) đúng ngày hôm nay. Xem kỹ cả tháng và năm.
4. **Bước 4:** Kiểm tra bảng **Danh mục** có tích **Suy niệm** (chuyên mục mà khối đang dùng).
5. **Bước 5:** Kiểm tra trong cùng ngày không có bài khác cũng thuộc **Suy niệm**. Khối hiện bài đăng muộn nhất trong ngày, nên bài lạ sẽ chiếm chỗ.
6. **Bước 6:** Sửa chỗ sai, bấm nút lưu, rồi tải lại trang chủ (F5).
7. **Bước 7:** Nếu bạn là Quản lý, kiểm tra ô **Chuyên mục** của khối (Hình 15.07a, số 3). Chỉ chọn một chuyên mục.

#### Ảnh minh họa

Xem Hình 19.06: số 1 là **Trạng thái**, số 2 là ngày giờ đăng. Xem thêm Hình 15.07a: số 1 là mẫu **Lời Chúa hôm nay (bài theo ngày)**, số 3 là nhóm **Nguồn bài viết** với ô **Chuyên mục** của khối.

#### Kết quả

Bạn sẽ thấy khối **Lời Chúa hôm nay** hiện đúng bài của hôm nay, với ngày dd/mm/yyyy và Ngày phụng vụ của hôm nay.

#### Lưu ý

- Bài hẹn 5:00 sáng thì trước 5:00 sáng khối vẫn hiện bài hôm qua. Sau giờ hẹn, khối tự đổi.
- Khối chỉ dùng **chuyên mục đầu tiên** được chọn. Chọn nhiều chuyên mục dễ lấy nhầm bài.
- Widget **Lịch Lời Chúa** nên chọn cùng chuyên mục với khối (Bài 18.07).

#### Không nên làm

- Không bấm **Bây giờ** trong lịch của bài Lời Chúa. Ngày đăng sẽ đổi thành lúc bấm, không còn đúng ngày của bài.
- Không đăng bài thường (thông báo, tin tức) vào danh mục **Suy niệm**.

#### Nếu gặp lỗi

- Khối hiện bài của hôm qua dù bài hôm nay đã đăng → ngày đăng của bài hôm nay bị sai. Sửa lại ngày ở mục **Xuất bản** (Bài 19.06).
- Khối hiện một bài không phải Lời Chúa → bài đó thuộc **Suy niệm**, đăng cùng ngày. Đổi danh mục cho bài đó (Bài 23.03).
- Bấm ngày trên **Lịch Lời Chúa** lại mở sang trang khác → lịch và khối đang khác chuyên mục. Báo Quản lý chọn lại cho giống nhau.

### Bài 24.06 Không đăng nhập được

**Nhãn:** Bắt buộc học · 🟢 An toàn

#### Mục đích

Tự kiểm tra và xử lý khi không vào được trang quản trị. Tránh thử sai quá nhiều lần làm tài khoản bị khoá tạm.

#### Trước khi bắt đầu

Biết tên đăng nhập hoặc email của tài khoản. Có thể mở được hộp thư email đó.

#### Các bước thực hiện

1. **Bước 1:** Kiểm tra địa chỉ trang. Đúng tên website, có thêm `/wp-admin` ở cuối (Bài 01.02).
2. **Bước 2:** Kiểm tra phím Caps Lock đang tắt.
3. **Bước 3:** Tắt bộ gõ tiếng Việt (Unikey, EVKey…) rồi gõ lại mật khẩu.
4. **Bước 4:** Bấm hình con mắt trong ô **Mật khẩu** để xem lại mật khẩu vừa gõ.
5. **Bước 5:** Thử đăng nhập bằng email thay cho tên người dùng (hoặc ngược lại).
6. **Bước 6:** Nếu vẫn sai, bấm **Bạn quên mật khẩu?** và làm theo Bài 21.04.
7. **Bước 7:** Nếu trang đăng nhập không hiện hoặc báo lỗi lạ, thử cửa sổ ẩn danh hoặc trình duyệt khác.
8. **Bước 8:** Vẫn không vào được thì báo Quản lý. Quản lý có thể đặt mật khẩu mới cho bạn.

#### Ảnh minh họa

Xem Hình 01.02: số 1 là ô **Tên người dùng hoặc địa chỉ email**, số 2 là ô **Mật khẩu**, số 4 là nút **Đăng nhập**, số 5 là liên kết **Bạn quên mật khẩu?**. Xem thêm Hình 21.04 cho màn hình **Lấy mật khẩu mới**.

#### Kết quả

Bạn sẽ vào được **Bảng Điều Khiển** với dòng “Xin chào, <tên của bạn>!”.

#### Lưu ý

- Dòng báo lỗi màu đỏ cho biết sai ở đâu: “Mật khẩu bạn đã nhập… không chính xác” là sai mật khẩu; “Địa chỉ email không xác định” là sai email.
- Trình duyệt tự điền mật khẩu cũ có thể làm bạn đăng nhập sai. Xoá ô mật khẩu và gõ lại.

#### Không nên làm

- Không thử đi thử lại quá 5 lần liên tục.
- Không gửi mật khẩu cho Quản lý để “nhờ thử giúp”.
- Không mượn tài khoản của người khác để làm tạm.

#### Nếu gặp lỗi

- Báo “Địa chỉ email không xác định” → email chưa đúng với tài khoản. Thử tên đăng nhập, hoặc hỏi Quản lý email đã đăng ký.
- Gõ đúng mật khẩu mà trang đăng nhập cứ hiện lại → trình duyệt đang chặn cookie (tệp nhỏ giúp website nhớ bạn đã đăng nhập). Thử cửa sổ ẩn danh hoặc trình duyệt khác.
- Mọi người cùng không đăng nhập được, hoặc trang hiện trắng → website có thể đang gặp sự cố. Dừng lại và làm theo Bài 24.07.

### Bài 24.07 Khi nào cần dừng thao tác và liên hệ kỹ thuật

**Nhãn:** Bắt buộc học · 🔴 Không tự thay đổi

#### Mục đích

Biết các dấu hiệu nghiêm trọng để dừng tay đúng lúc. Dừng sớm giúp một lỗi nhỏ không thành mất dữ liệu hay hỏng cả website.

#### Trước khi bắt đầu

Biết số điện thoại hoặc kênh liên lạc của người phụ trách kỹ thuật website. Xem mẫu ghi thông tin ở Phụ lục E.04.

#### Các bước thực hiện

1. **Bước 1:** Dừng ngay khi thấy một trong các dấu hiệu: trang trắng; dòng “Đã có một lỗi nghiêm trọng trên trang web này”; nhiều bài hoặc ảnh biến mất; tài khoản lạ; website tự chuyển sang trang lạ; trình duyệt cảnh báo trang nguy hiểm.
2. **Bước 2:** Không bấm thêm nút nào. Không lưu, không xoá, không cập nhật, không bật hay tắt plugin.
3. **Bước 3:** Ghi lại giờ phát hiện và địa chỉ trang bị lỗi (chép từ thanh địa chỉ).
4. **Bước 4:** Ghi lại việc bạn vừa làm ngay trước khi lỗi xảy ra.
5. **Bước 5:** Chụp màn hình toàn bộ, gồm cả dòng báo lỗi. Che thông tin riêng nếu có.
6. **Bước 6:** Gửi các thông tin trên cho người phụ trách kỹ thuật (theo Phụ lục E.04).
7. **Bước 7:** Chờ hướng dẫn. Chỉ làm tiếp khi được người phụ trách đồng ý.

#### Ảnh minh họa

Bài này không cần ảnh minh họa.

#### Kết quả

Bạn sẽ thấy sự cố được giữ nguyên hiện trạng. Người phụ trách kỹ thuật nhận đủ thông tin để xử lý nhanh.

#### Lưu ý

- Chụp màn hình: Windows bấm **Windows + Shift + S**; Mac bấm **Cmd + Shift + 4**; điện thoại bấm cùng lúc nút nguồn và nút giảm âm lượng.
- Bài đang viết dở mà website lỗi: chép phần chữ ra Word hoặc Notepad để giữ lại.
- Website nên có ít nhất hai Quản lý để thay nhau khi có sự cố.

#### Không nên làm

- Không “thử mọi cách”, cài plugin sửa lỗi hay khôi phục bản sao lưu khi chưa được hướng dẫn.
- Không đăng ảnh lỗi hay thông tin tài khoản lên nhóm chat đông người.
- Không gửi mật khẩu kèm theo tin báo lỗi.

#### Nếu gặp lỗi

- Không liên lạc được người phụ trách kỹ thuật → báo Quản lý thứ hai của website, giữ nguyên hiện trạng.
- Lỗi tự hết sau vài phút → vẫn báo cho người phụ trách kỹ thuật, kèm giờ xảy ra.
- Đã lỡ bấm thêm vài nút sau khi thấy lỗi → ghi rõ đã bấm gì, theo thứ tự, và gửi kèm.
