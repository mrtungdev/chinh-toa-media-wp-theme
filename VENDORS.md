# Thư viện bên thứ ba (Third-party libraries)

Theme **Chính Tòa Media** dùng giấy phép **MIT** cho mã nguồn của theme. Một số
thư viện được đóng gói sẵn (bundled) và **giữ nguyên giấy phép gốc** của chúng.
Tất cả đều tương thích MIT/GPL.

| Thư viện | Phiên bản | Giấy phép | Vị trí trong theme |
|----------|-----------|-----------|--------------------|
| [Bootstrap](https://getbootstrap.com/) | 5.3.x | MIT | `assets/js/bootstrap.bundle.min.js`, biên dịch trong `assets/css/theme-*.css` |
| [Parsedown](https://github.com/erusev/parsedown) | 1.7.x | MIT | `inc/vendor/Parsedown.php` (kèm `LICENSE-parsedown.txt`) |
| [lazysizes](https://github.com/aFarkas/lazysizes) | — | MIT | `assets/js/lazysizes.min.js` |
| [swipebox](https://github.com/brutaldesign/swipebox) | 1.5.x | MIT | `assets/js/jquery.swipebox.min.js` |
| [Font Awesome Free](https://fontawesome.com/) | 4.x | Icons: CC BY 4.0 · Fonts: SIL OFL 1.1 · Code: MIT | `assets/fontawesome/` |
| [TGM Plugin Activation](https://github.com/TGMPA/TGM-Plugin-Activation) | — | **GPL-2.0-or-later** | `inc/tgma/` (kèm `LICENSE-tgma.txt`) |

## Ghi chú

- **TGMPA** là thành phần GPL duy nhất. Nó gợi ý cài plugin **WP-PostViews**
  (cung cấp số lượt xem bài viết mà theme hiển thị). Thành phần này giữ giấy phép
  GPL-2.0-or-later; phần còn lại của theme là MIT. MIT và GPL tương thích khi
  phân phối chung, miễn là các file TGMPA giữ thông báo GPL của chúng.
- **Font Awesome** hiện chỉ dùng ở trang quản trị. Lưu ý: thư mục webfont đang
  thiếu (chỉ còn CSS) — xem `docs/AUDIT.md` mục "Việc nên làm tiếp".
- Front-end **không** dùng icon font; tất cả icon hiển thị là SVG nội tuyến (inline).

## Khi nâng cấp thư viện

Cập nhật phiên bản qua `package.json` (Bootstrap) rồi chạy `pnpm run build`, hoặc
thay file tương ứng trong `assets/` và cập nhật bảng trên + số phiên bản.
