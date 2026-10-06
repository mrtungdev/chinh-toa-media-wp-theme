# Ảnh minh hoạ

Toàn bộ ảnh trong `final/` được chụp tự động bằng `scripts/capture/` (Playwright điều khiển
**Google Chrome** của máy) trên website kiểm chứng `http://5plc.local`.

## Tên tệp

`Pxx-Cxx-Bxx-yy-noi-dung.png` — `Pxx` số Phần, `Cxx` số Chương, `Bxx` số Bài, `yy` thứ tự
ảnh trong bài. Phụ lục: `PHU-LUC-X-yy-noi-dung.png`. Ví dụ: `P06-C14-B04-01-header-logo-khau-hieu.png`.

## Cách ảnh được làm

- PNG độ nét **2x** (deviceScaleFactor 2), khung nhìn 1280 px; ảnh điện thoại 390 × 844.
- Khung đỏ `#D92D20` nét 3 px, bo góc, **cách phần tử 6 px**; hai khung liền kề tự chia
  đôi khoảng hở để không chồng viền.
- Số tròn xanh `#1D4ED8` 28 px, viền trắng, đặt **ngoài khung** theo phía khai báo
  (trái/phải/trên/dưới); máy tự né chữ, né số khác và né khung khác.
- Toạ độ lấy từ vị trí thật của phần tử trên trang, nên chụp lại khi giao diện đổi vẫn khớp.
- Ẩn thanh admin bar (trừ ảnh cần), thông báo của plugin, hiệu ứng chuyển động; không có
  con trỏ chuột.
- Tên tài khoản thay bằng nhãn vai trò (“Quản lý”, “Biên tập viên”); email, tên đăng nhập,
  mật khẩu được làm mờ.
- **An toàn:** mọi yêu cầu ghi dữ liệu (lưu, xuất bản, xoá, tự lưu nháp) bị chặn ở mức
  trình duyệt; công tắc bật trong lúc chụp chỉ là tạm. Riêng mở “Thêm Bài Viết” có thể tạo
  một bản nháp tự động rỗng, WordPress tự dọn sau 7 ngày.
- `manifest.json` ghi bề rộng/chiều cao CSS của từng ảnh — `build_docx.py` dùng để in ảnh
  đúng cỡ.
- Ảnh có khai báo `guide` được xuất thêm bản WebP vào `chinhtoa/assets/imgs/guide/` cho
  trang Hướng dẫn trong WordPress.

## Chụp lại

```bash
cd docs/so-tay-quan-tri-wordpress/scripts/capture
npm install                                   # lần đầu (chỉ playwright-core)
export CT_AUTH_DIR=/thư-mục/an-toàn/ngoài-repo
node login.mjs admin                          # cửa sổ Chrome mở ra, bạn tự đăng nhập Quản lý
node login.mjs editor                         # đăng nhập tài khoản Biên tập viên
node shots.mjs                                # chụp tất cả
node shots.mjs --only P06-C14-B04-01-header-logo-khau-hieu   # chụp lại một ảnh
node shots.mjs --grep P06                     # chụp lại cả Phần 6
```

Danh sách ảnh, vùng cắt, vị trí khung và số nằm trong `scripts/capture/shots.config.mjs`.
`probe.mjs` và `evalpage.mjs` giúp dò phần tử khi thêm ảnh mới. Tệp phiên đăng nhập chứa
cookie — luôn để ngoài repo.

## Trước khi dùng ảnh

- Số trên ảnh khớp số bước trong bài (xem `huong-dan-bien-soan.md`, mục 6).
- Không còn tên tài khoản, email, mật khẩu, địa chỉ chứa mã bảo mật.
- Chữ trên ảnh đọc được khi in A4.
