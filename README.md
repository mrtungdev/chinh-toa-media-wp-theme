# Chính Tòa Media — Theme WordPress cho giáo xứ Công giáo

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
![WordPress](https://img.shields.io/badge/WordPress-5.4%2B-21759b)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4)

Theme WordPress **mã nguồn mở**, nhẹ và dễ tùy biến, dành cho **website giáo xứ
Công giáo**: tin tức, **Lời Chúa** hằng ngày, **Giờ Thánh Lễ**, thư viện ảnh,
thanh thông báo… Được xây dựng và sử dụng thực tế tại **Giáo xứ Chính Tòa Đà Nẵng**.

> Theme dùng giấy phép **MIT** — bạn được tự do dùng, sửa, phân phối cho giáo xứ
> của mình. Rất hoan nghênh đóng góp (xem [CONTRIBUTING.md](CONTRIBUTING.md)).

> 📌 **Nguồn gốc:** theme này vốn xuất phát từ dự án
> [mrtungdev/CongGiaoWordpressTheme](https://github.com/mrtungdev/CongGiaoWordpressTheme),
> sau đó được **viết lại và tối ưu** toàn diện (bảo mật, tốc độ, chất lượng mã, i18n)
> rồi phát hành mã nguồn mở dưới tên **chinh-toa-media-wp-theme**.

## Tính năng

- 🎨 **9 bộ màu** dựng sẵn + **màu tùy chọn** (tự đổi màu chủ đạo trong trang quản trị).
- 📰 **Trang chủ dạng khối** (sections) kéo-thả: tin nổi bật, danh sách bài theo
  chuyên mục, tab nhiều chuyên mục, khối nội dung tĩnh… nạp động qua AJAX.
- ✝️ **Box "5 phút Lời Chúa"** — hiển thị bài suy niệm/Tin Mừng mỗi ngày.
- ⛪ **Widget Giờ Thánh Lễ** (đa ngôn ngữ).
- 🔔 **Thanh thông báo** (hot bar) bật/tắt theo thời gian.
- ⚡ Tải ảnh lười (lazy-load), bộ nhớ đệm truy vấn (transient) tự làm mới khi đăng bài.
- 🌐 **Sẵn sàng dịch thuật** (text domain `chinhtoa`, có `languages/chinhtoa.pot`).
- 🏷️ **White-label**: đổi thương hiệu cho giáo xứ khác chỉ bằng một file cấu hình.

## Yêu cầu

| Thành phần | Tối thiểu |
|-----------|-----------|
| WordPress | 5.4+ |
| PHP | 7.4+ (đã kiểm thử tới PHP 8.3) |
| Plugin khuyến nghị | WP-PostViews (đếm lượt xem), Yoast SEO, Elementor — theme tự gợi ý cài qua TGMPA |

## Cài đặt

1. Tải file phát hành **`chinh-toa-media-wp-theme.zip`** (tạo bằng `pnpm run pack`)
   rồi vào **Giao diện → Themes → Thêm mới → Tải theme lên**; hoặc giải nén vào
   `wp-content/themes/`.
2. Vào **Giao diện → Themes**, kích hoạt **Chính Tòa Media**.
3. Khi được nhắc, cài plugin khuyến nghị (nhất là **WP-PostViews**).
4. Cấu hình tại **Giao diện → Thiết lập giao diện** (màu, header, footer, trang chủ, thông báo).

## Cập nhật tự động (qua GitHub Releases)

Theme tự kiểm tra **[GitHub Releases](https://github.com/mrtungdev/chinh-toa-media-wp-theme/releases)**
của repo công khai. Khi có **release mới** (tag phiên bản, ví dụ `v1.0.1`), WordPress sẽ
hiện thông báo cập nhật ở **Giao diện → Themes / Bảng tin → Cập nhật**, và bạn **cập nhật
1-chạm** như theme chính thống — không cần tải tay.

**Cách phát hành phiên bản mới (cho người bảo trì):**
1. Tăng `Version:` trong `chinhtoa/style.css` (vd `1.0.1`).
2. `pnpm run pack` để tạo `chinh-toa-media-wp-theme.zip`.
3. Tạo **Release** trên GitHub với tag trùng phiên bản (`v1.0.1`) và **đính kèm file
   `.zip`** vừa tạo làm asset.
4. Các site đang dùng theme sẽ thấy cập nhật trong vòng ~12 giờ (hoặc bấm
   *Kiểm tra lại* ở trang Cập nhật).

> White-label: đổi repo nguồn cập nhật bằng filter `ct_github_repo` (trả về `''` để tắt).
> Mặc định: `mrtungdev/chinh-toa-media-wp-theme`.

## Tùy biến thương hiệu (white-label)

Toàn bộ nhận diện (tên, logo, favicon, màu chrome, link hỗ trợ…) tập trung ở
**`chinhtoa/inc/brand/brand-config.php`**. Xem hướng dẫn đầy đủ trong
[chinhtoa/REBRAND.md](chinhtoa/REBRAND.md). Có thể ghi đè không cần sửa file qua
filter `ct_brand` từ child theme / mu-plugin.

## Phát triển & build

Mã nguồn CSS (SCSS + Bootstrap) nằm ở `src/`, build bằng webpack ra `dist/` rồi
copy vào `chinhtoa/assets/css/`. Xem [BUILD.md](BUILD.md) để biết cách build và
thêm bộ màu mới.

```bash
pnpm install      # cài phụ thuộc
pnpm run build    # build production
pnpm run start    # build + watch khi phát triển
```

## Cấu trúc thư mục

```
chinhtoa/            # Theme (phần được phân phối)
  inc/               #   Lõi: options, brand, post types, query, admin, vendor
  template-parts/    #   Mảnh giao diện (homepage, post, header, boxes…)
  assets/            #   CSS/JS/ảnh đã build
  languages/         #   chinhtoa.pot (dịch thuật)
src/                 # Nguồn SCSS/ảnh để build
tools/               # make-pot.php (sinh file .pot khi không có WP-CLI)
tests/               # Test PHP cho engine options (chạy bằng php tests/*.php)
docs/AUDIT.md        # Báo cáo rà soát bảo mật / tốc độ / chất lượng
```

## Bảo mật & chất lượng

Theme đã qua một đợt rà soát toàn diện (bảo mật, tốc độ, tương thích, i18n). Chi
tiết các vấn đề đã xử lý và việc nên làm tiếp: [docs/AUDIT.md](docs/AUDIT.md).
Báo lỗi bảo mật: xem [SECURITY.md](SECURITY.md).

## Giấy phép

[MIT](LICENSE) cho mã nguồn của theme. Các thư viện đóng gói giữ giấy phép gốc —
xem [VENDORS.md](VENDORS.md).

## Ghi công

Phát triển bởi **TRUYỀN THÔNG GIÁO XỨ CHÍNH TÒA ĐÀ NẴNG** (ToiLaTung). Cảm ơn các
dự án mã nguồn mở: Bootstrap, Parsedown, lazysizes, swipebox, Font Awesome, TGMPA.
