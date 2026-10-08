# Build & phát triển

## Tổng quan pipeline

```
src/scss/*.scss  ──(webpack + sass + postcss)──►  dist/css/*.css  ──(copyfiles)──►  chinhtoa/assets/css/*.css
src/theme-*.js   ──(webpack)──────────────────►  dist/js/*.js     (không dùng — xem ghi chú)
```

- **Nguồn sự thật** là `src/`. **Không sửa trực tiếp** `chinhtoa/assets/css/theme-*.css`
  (sẽ bị ghi đè ở lần build sau).
- Các file JS front-end khác (`ct-media.js`, …) được viết tay trong
  `chinhtoa/assets/js/` và **không** qua webpack.

## Lệnh

```bash
pnpm install        # cài phụ thuộc (dự án dùng pnpm)
pnpm run build      # build production + copy CSS vào theme
pnpm run start      # build dev + watch
pnpm run pack       # đóng gói theme thành dist/chinh-toa-media-wp-theme.zip
pnpm run pack -- v1.2.0   # đóng gói đúng bản đã gắn tag git
pnpm run pin -- <thư-mục-theme-của-site> [tag]   # ghim site Local vào bản cố định (xem dưới)
```

> Dự án chuẩn hóa dùng **pnpm** (có `pnpm-lock.yaml`). Nếu trước đây bạn dùng npm,
> hãy xóa `package-lock.json` để tránh lệch khóa phụ thuộc.

## Đóng gói phát hành (tên thân thiện)

- **Trong repo / khi phát triển**: thư mục theme là `chinhtoa/`, *text domain* là
  `chinhtoa` (slug nội bộ, giữ ổn định theo `chinhtoa/REBRAND.md`).
- **Khi phát hành**: `pnpm run pack` đóng gói theme dưới **tên thân thiện**
  `chinh-toa-media-wp-theme/` → người dùng cài sẽ thấy theme tên này. Đổi tên thư mục
  lúc đóng gói **không** ảnh hưởng chức năng (theme dùng `get_template_directory()` —
  đường dẫn động; text domain không phụ thuộc tên thư mục).
- Repo GitHub và `package.json` cũng mang tên `chinh-toa-media-wp-theme`.

> File `.zip` đã trong `.gitignore` — không commit; tạo lại bằng `pnpm run pack`.
>
> Nâng cấp site đang chạy: chép đè **nội dung** zip vào thư mục theme hiện có (giữ nguyên tên
> thư mục, VD `chinhtoa/`) — đổi tên thư mục là WordPress coi như theme khác (mất menu, thiết lập).

## Nhiều site dùng chung theme (Local)

Mọi thứ riêng của từng site **nằm ngoài theme** nên thay theme không mất gì: thiết lập
(`ct_settings`, menu, widget trong DB), thương hiệu (mu-plugin `ct-brand-*.php` lọc `ct_brand`),
CSS riêng (Thiết lập giao diện → Nâng cao), script `tools/setup-site-vi.php`, `tools/demo/`.

Để sửa theme mà **không ảnh hưởng site đang dùng**:

1. **Một site thử** trỏ thẳng vào repo: `wp-content/themes/<tên> → …/ChinhToa/chinhtoa`
   (hiện là nutythuasaithanhgia.local).
2. **Các site còn lại ghim bản cố định**: `pnpm run pin -- "<site>/wp-content/themes/<tên>"`
   chụp `chinhtoa/` hiện tại (hoặc `… v1.2.0` để xuất đúng tag) vào
   `~/Local Sites/_theme-releases/` rồi trỏ symlink sang đó (hiện là 5plc.local → `church`).
3. Sửa và kiểm tra trên site thử: `php tests/*.php`, mở các trang chính, đọc lại trang Hướng
   dẫn (nội dung tự đổi theo site — xem `inc/admin/theme/guide-helpers.php`).
4. Ổn rồi: tăng `Version`, ghi `CHANGELOG.md`, commit + gắn tag, chạy `pnpm run pin` cho từng
   site còn lại. Lỗi thì trỏ lại bản cũ (script in đường dẫn cũ): `ln -sfn "<bản cũ>" "<thư mục theme>"`.
5. Lên host: `pnpm run pack -- <tag>` rồi chép đè vào thư mục theme trên host.

Script chỉ thay thư mục theme khi nó là **symlink**, không bao giờ xoá thư mục thật.

## Thêm một bộ màu mới

1. Khai biến màu trong `src/scss/shared/_var.scss`, ví dụ `$cg-teal: #0f766e;`.
2. Tạo `src/theme-teal.js`:
   ```js
   import "./scss/theme-teal.scss";
   ```
3. Tạo `src/scss/theme-teal.scss`:
   ```scss
   @import "./shared/var";
   $theme-colors: ("primary": $cg-teal);
   $theme-colors: map-remove($theme-colors, "info", "light", "dark", "warning", "success", "danger");
   @import "./global.scss";
   ```
4. Thêm entry vào `webpack.config.js` (`"theme-teal": "./src/theme-teal.js"`).
5. `pnpm run build` → có `chinhtoa/assets/css/theme-teal.css`.
6. Thêm lựa chọn màu trong UI options (xem `inc/options/admin/sections.php`).

## Màu tùy chọn (custom color)

`theme-custom.css` được build với màu "sentinel" `#c0ffee`. Lúc chạy,
`ct_custom_theme_css_url()` (trong `inc/utilities/enqueue.php`) thay sentinel bằng
màu admin chọn và cache file đã nhuộm vào `wp-uploads/chinhtoa-theme/`.

## Ghi chú kiến trúc CSS (quan trọng)

Hiện mỗi `theme-{color}.css` là một **bản build đầy đủ** (Bootstrap + theme, chỉ
khác màu primary), nên 9 file gần như trùng nhau (~229 KB/file). Mỗi trang chỉ nạp
**một** file nên không ảnh hưởng tốc độ tải trang, nhưng làm phình repo.

**Vì sao chưa gộp thành 1 base + override màu?** Bootstrap 5 tính các sắc độ
hover/active/border của màu primary ở **thời điểm compile** (ví dụ `.btn-primary:hover`
ra một mã hex tối hơn cố định). Cách "nhuộm runtime" như màu custom chỉ thay được
màu nền cơ bản, **không** thay đúng các sắc độ này → nút bấm khi hover sẽ sai màu
ở các bộ màu preset. Vì vậy việc gộp base+override cần:

1. hoặc viết lại các thành phần dùng `var(--ct-primary)` + `color-mix()` (CSS hiện đại),
2. hoặc sinh override đúng sắc độ ở build-time,

và **bắt buộc kiểm thử trực quan**. Đây là việc nên làm trong một nhánh riêng có QA,
không nên làm "mù". Xem `docs/AUDIT.md` mục PERF-CSS.

## Sinh file dịch (.pot)

Khi không có WP-CLI:

```bash
php tools/make-pot.php   # quét chuỗi i18n → chinhtoa/languages/chinhtoa.pot
```

Nếu có WP-CLI thì ưu tiên: `wp i18n make-pot chinhtoa chinhtoa/languages/chinhtoa.pot`.

## Kiểm thử engine options

```bash
php tests/options-test.php
php tests/migrate-test.php
php tests/admin-test.php
```

(Trên máy không có `php` trong PATH, dùng PHP của Local by Flywheel — xem ghi chú dev.)
