# Sổ tay quản trị website WordPress

Nguồn biên soạn của sổ tay dùng chung cho các website dùng giao diện **Chính Tòa Media**.
Website `5plc` (`http://5plc.local`, chạy bằng ứng dụng Local) là nơi kiểm chứng thao tác
và chụp ảnh; tên này không xuất hiện trong tài liệu phát hành.

## Trạng thái (06/10/2026)

- Kiểm chứng trên **WordPress 7.1** và **Chính Tòa Media 1.1.0**, dữ liệu mẫu “5 phút cho
  Lời Chúa” (nạp bằng `tools/demo/seed-5plc.php`).
- **184 bài** trong 29 tệp chương (`src/`), mục lục ở `src/00-muc-luc-de-xuat.md`.
- **100 ảnh** chụp lại toàn bộ bằng Playwright + Google Chrome, PNG độ nét 2x, khung và số
  đặt tự động theo vị trí thật của nút/ô (`screenshots/final/`).
- Bản phát hành: `So-tay-quan-tri-website-WordPress.docx`.
- Bản 05/09/2026 (theme 1.0.0) cùng ảnh cũ và biên bản khảo sát cũ được lưu ở
  `_ban-cu-2026-09-05/`; các script hoàn nguyên dữ liệu cũ ở `scripts/_cu/` (không còn dùng).

Trang **“Hướng dẫn sử dụng”** trong quản trị (`chinhtoa/inc/admin/theme/docs/`) là bản tóm
tắt 16 mục, dùng chung bộ ảnh này (bản WebP trong `chinhtoa/assets/imgs/guide/`).

## Cấu trúc

- `huong-dan-bien-soan.md`: quy tắc văn phong, định dạng, nhãn thật trên màn hình, dữ liệu
  mẫu, ý nghĩa từng số trên 100 ảnh. **Đọc trước khi sửa bất kỳ chương nào.**
- `src/`: nguồn Markdown (mở đầu, mục lục, 24 chương, 5 phụ lục).
- `screenshots/final/`: ảnh dùng trong DOCX + `manifest.json` (kích thước CSS của từng ảnh).
- `screenshots/README.md`: quy trình chụp lại ảnh.
- `scripts/capture/`: bộ chụp ảnh (Playwright, Chrome hệ thống).
- `scripts/build_docx.py`: dựng DOCX (python-docx + Pillow).
- `scripts/validate_sources.py`: đối chiếu bài với mục lục, kiểm tra cấu trúc 8 mục, bước, ảnh.
- `scripts/validate_docx_structure.py`: kiểm tra liên kết, ảnh, trường số trang, chữ riêng tư.
- `scripts/normalize_error_sections.py`: script cũ chèn câu lỗi chung — **không chạy nữa**
  (mỗi bài nay có tình huống lỗi riêng).
- `scripts/ocr_privacy_sweep.swift`: quét chữ trong ảnh để tìm thông tin riêng.

## Dựng lại DOCX

```bash
cd docs/so-tay-quan-tri-wordpress
python3 scripts/validate_sources.py
uv run --python 3.12 --with python-docx --with pillow python scripts/build_docx.py
python3 scripts/validate_docx_structure.py So-tay-quan-tri-website-WordPress.docx
```

Ảnh được nhúng theo kích thước thật (1 px CSS ≈ 0,0178 cm, tối đa 16 cm), dạng JPEG chất
lượng cao để tệp gọn. Bìa tự ghi phiên bản theme (đọc từ `chinhtoa/style.css`) và ngày dựng.

## Nguồn sự thật

Thứ tự ưu tiên khi viết: giao diện đang chạy trên `5plc.local` → mã nguồn theme
`chinhtoa/` → giao diện plugin đang cài. Khi theme đổi: cập nhật `huong-dan-bien-soan.md`,
chụp lại ảnh liên quan (`scripts/capture`), sửa chương, chạy hai script kiểm tra rồi dựng DOCX.
