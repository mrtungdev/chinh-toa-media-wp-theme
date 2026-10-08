# Nâng cấp theme Chính Tòa Media

## 1.1.0 → 1.2.0 (Trang tĩnh, SEO, tốc độ)

**Tóm tắt:** không đổi cấu trúc dữ liệu, không cần migrate, không cần build lại CSS.
Khác 1.1.0, bản này **thay đổi hiển thị/hành vi ngay** sau khi chép đè (xem bảng).

### 1. Hành vi thay đổi khi nâng cấp

| Trước (1.1.0) | Sau (1.2.0) |
|---|---|
| Trang tĩnh: nội dung nằm thẳng trên nền trang, không lề, không thanh bên | Thẻ nền trắng, breadcrumb, tiêu đề, nội dung cùng CSS bài viết; thanh bên widget **Bài viết** nếu Thiết lập giao diện → Bài viết đang bật thanh bên |
| Nội dung ngắn: footer dừng giữa màn hình, lộ nền trống bên dưới | Footer sát đáy màn hình |
| Trang không có hộp tuỳ chỉnh giao diện | Có hộp **“Tuỳ chỉnh giao diện trang”** (thanh bên, ảnh, breadcrumb, tiêu đề) |
| Khối trang chủ nạp bằng AJAX sau khi trang tải (1 request/khối) | Khối có sẵn trong HTML — trang chủ hiện đủ ngay, tốt cho SEO |
| Trang chuyên mục chỉ có lưới bài | Thêm khối tiêu đề: breadcrumb, tên + mô tả chuyên mục. Trang tìm kiếm có tiêu đề + ô tìm kiếm |
| Thẻ bài dùng ảnh gốc full-size | Dùng bản 690px (widget 320px) — WordPress đã tự tạo sẵn khi tải ảnh lên |
| Theme in robots/og/keywords cạnh Yoast | Có Yoast thì chỉ Yoast in; không có Yoast thì theme in như cũ + canonical |
| Site có Site Icon vẫn in thêm favicon Chính Tòa | Site Icon được ưu tiên |

**Ảnh cũ thiếu cỡ 690/320/960** (tải lên trước khi theme đăng ký các cỡ này) sẽ rơi về ảnh
gốc — chạy `wp media regenerate --only-missing` một lần để tạo bù.

**Muốn một trang rộng hết khung (không thanh bên):** mở trang → hộp “Tuỳ chỉnh giao diện
trang” → bật “Tuỳ chỉnh riêng” → tắt “Thanh bên”.

### 2. Danh sách file thay đổi

| File | Thay đổi |
|---|---|
| `page.php` | khung `#ct-content.ct-single.ct-page` + thanh bên `ct-widget-single` (giống `single.php`) |
| `template-parts/page/content-page.php` | cấu trúc `.post-header` / `.post-content` như `template-parts/post/content.php`; ảnh đầu trang tải ngay |
| `inc/options/admin/term-post-meta.php` | đăng ký hộp tuỳ chỉnh cho `page` (ẩn Tác giả / Ngày & lượt xem) |
| `sidebar.php` | sửa ID sidebar (`ct-widget-homepage`) |
| `page-homepage.php` | render khối phía server + `<h1>` ẩn |
| `archive.php`, `search.php`, `404.php`, `searchform.php` | khối tiêu đề, `<h1>`, ô tìm kiếm; guard queried object |
| `template-parts/category/content-none.php` | **mới** — thông báo không có bài |
| `header.php`, `footer.php`, `index.php` | meta không trùng Yoast, favicon theo Site Icon, skip link, `<main>` |
| `functions.php`, `inc/utilities/action.php` | không gỡ `rel_canonical` và `feed_links` nữa |
| `inc/utilities/filter.php` | `getPostImage($id, $size)`, bỏ tiền tố tiêu đề archive |
| `template-parts/homepage/c_post-item*.php`, `featured-post.php`, `inc/shortcodes/ct_shortcode_post.php`, `inc/widget/ct_postlist_widget.php` | tiêu đề `<h3>`, cỡ ảnh, sắp xếp lượt xem dạng số |
| `template-parts/post/content.php` | ảnh đầu bài `fetchpriority="high"` + srcset |
| `inc/utilities/enqueue.php` | `ct_layout_enqueue()`; JS `defer`; Swipebox chỉ ở trang chi tiết; font có điều kiện + preconnect; bỏ emoji, jQuery Migrate; `ct_dequeue_unused_styles()` |
| `inc/blocks/loader.php`, `inc/widget/ct_loichua_card_widget.php`, `inc/post/post-kind.php` | Lora 2 kiểu, CSS thẻ Lời Chúa chỉ khi dùng; cache oEmbed |
| `inc/query/common.php` | `ct_prime_post_caches()` |
| `inc/utilities/type.php` | phân trang `<nav>`, tham số nullable |
| `assets/js/ct-media.js` | chỉ gọi Swipebox khi có |
| `assets/css/layout.css` | **mới** — footer sát đáy, khối tiêu đề chuyên mục, `h3.post-title`, 404, skip link |
| `style.css`, `package.json` | `Version: 1.2.0` |
| `languages/chinhtoa.pot` | sinh lại |

### 3. Rủi ro

- Trang dựng bằng **Elementor** nhưng để template **“Mặc định”** sẽ nằm trong thẻ trắng +
  thanh bên → bố cục có thể chật. Chọn template **Elementor Full Width / Canvas** cho trang
  đó (không đi qua `page.php`), hoặc tắt thanh bên trong hộp tuỳ chỉnh của trang.
- Trang chủ dùng template **“Trang Chủ”** (`page-homepage.php`) không bị ảnh hưởng.
- CSS riêng của site nhắm vào `#primary`, `.site-main`, `.entry-content`, `.entry-header`
  của trang tĩnh sẽ không còn tác dụng — đổi sang `#ct-content.ct-page .post-content`.
- CSS/JS riêng nhắm vào `.homepage-dynamic-ajax` (khối trang chủ) → đổi sang `.homepage-section`.
  `div.post-title` của thẻ bài nay là `h3.post-title` (dùng class thì không ảnh hưởng).
- Plugin cũ cần jQuery Migrate ở trang ngoài (hiếm) sẽ báo lỗi console → bật lại bằng cách gỡ
  hàm `wp_default_scripts` trong `inc/utilities/enqueue.php`.

### 4. Thiết lập site khuyến nghị (Yoast, tiếng Việt, tắt bình luận)

Chạy `tools/setup-site-vi.php` (ngoài gói theme) cho từng site — xem chú thích đầu file.
Script bỏ `/category/` khỏi URL (Yoast tự 301 link cũ), Việt hoá tiêu đề/breadcrumb Yoast, đặt
tổ chức + logo + ảnh chia sẻ, tiêu đề/mô tả trang chủ, tắt bình luận và trang tác giả/ngày.

---

## 1.0.0 → 1.1.0 (“Lời Chúa hôm nay”)

**Tóm tắt:** bản 1.1.0 chỉ **thêm** tính năng, không đổi cấu trúc dữ liệu, không cần
migrate, không cần build lại CSS (`theme-*.css` giữ nguyên). Mọi tính năng mới đều
**tắt mặc định** — site đang chạy 1.0.0 chép đè theme mới lên sẽ hiển thị như cũ cho đến
khi quản trị viên chủ động bật. Ngoại lệ duy nhất có thể thấy ngay: sửa lỗi nút “Xem
thêm” (mục 3).

---

### 1. Tính năng mới (bật trong admin mới có tác dụng)

| Tính năng | Bật ở đâu | Khoá lưu trữ |
|---|---|---|
| Header **“Logo + khẩu hiệu”**: logo trái, câu khẩu hiệu + trích dẫn phải, nền gradient 2 màu | Thiết lập giao diện → Giao diện → Header → Kiểu Header | `ct_settings[header_data][action_show] = c_brand`, `ct_settings[header_data][c_brand][logo\|slogan\|slogan_ref\|bg_from\|bg_to\|slogan_color]` |
| Kiểu menu **c4 “Thanh chia đều”**: thanh màu đặc, mục chia đều, gạch nhấn mục đang chọn, menu con thẻ trắng | Giao diện → Màu sắc & hiển thị → Kiểu thanh menu (ô cuối) | `ct_settings[gen_data][nav_style] = c4` (dùng lại `nav_bg_color`, `nav_text_color`, `nav_accent_color`) |
| Khối trang chủ **temp7 “Lời Chúa hôm nay”**: bài có ngày đăng = hôm nay trong chuyên mục chọn (chưa có thì lấy bài gần nhất trước đó) | Trang chủ & Bài viết → Khối nội dung trang chủ → mẫu “Lời Chúa hôm nay (bài theo ngày)” | `ct_settings[home_sec][i][content_type][picker] = temp7`, `...[temp7][title\|cats\|is_display\|is_admin_only\|default_style]` |
| Widget **“Lịch Lời Chúa”**: lịch tháng; bấm ngày → đổi bài trong khối temp7 cùng chuyên mục (AJAX), trang khác → mở bài; ‹ › đổi tháng | Giao diện → Widget | `widget_ct_lc_calendar` (`title`, `category`) |
| Khối trang chủ **temp8 “Lưới ảnh mosaic”**: bài đầu ô 2×2, các bài sau ô nhỏ | Khối nội dung trang chủ → mẫu “Lưới ảnh mosaic” | `...[picker] = temp8`, cùng khoá với temp1–5 |
| 3 ô mới trong metabox **Phân loại bài viết → Lời Chúa**: Ngày phụng vụ, Lễ/kính thánh, Đoạn Tin Mừng | Màn hình soạn bài | post meta `_ct_lc_day_title`, `_ct_lc_saint`, `_ct_lc_gospel_ref` (string, `sanitize_text_field`) |

Bài hướng dẫn mới trong trang Giới thiệu của theme: **“Lời Chúa hôm nay & Lịch”**.

### 2. Danh sách file thay đổi

**Sửa (chỉ thêm nhánh/khoá, không đổi hành vi cũ):**

| File | Thay đổi |
|---|---|
| `functions.php` | `require` 2 file mới trong `inc/loichua/` |
| `header.php` | thêm nhánh `c_brand` → `template-parts/header/header-brand.php` |
| `inc/options/defaults.php` | thêm default `header_data.c_brand` (rỗng) |
| `inc/options/flatteners/helper-general.php` | `gen_GetHeader()` thêm nhánh `c_brand`; nhánh cũ giữ nguyên |
| `inc/options/admin/sections.php` | radio Header thêm “Logo + khẩu hiệu” + 6 field; menu thêm `c4`; danh sách mẫu khối thêm `temp7`, `temp8` (temp7 chỉ có ô Chuyên mục) |
| `inc/post/ajax.php` | map `temp7`, `temp8` → template |
| `inc/post/post-kind.php` | đăng ký + hiển thị + lưu 3 meta thông tin ngày (nhóm “Lời Chúa”) |
| `inc/utilities/enqueue.php` | `ct_header_brand_enqueue()` (priority 20) — chỉ nạp `header-brand.css` khi header `c_brand` hoặc menu `c4` |
| `assets/css/home-sections.css` | thêm luật lưới `.ct__post-mosaic` (không đụng khối khác) |
| `inc/widget/ct_postlist_widget.php` | tuỳ chọn `show_meta` (mặc định 0); class `ct-postlist--with-thumb`; `render_numbered()` thêm 2 tham số tuỳ chọn (có giá trị mặc định) |
| `assets/css/widget-postlist.css` | làm lại phần kiểu số thứ tự (xem mục 3) |
| `template-parts/homepage/c_post-item.php`, `c_post-item-image.php` | bài “Lời Chúa” không ảnh → khối câu Lời Chúa thay `<img>` (trong cùng `figure.image`) |
| `template-parts/post/content.php` | bài “Lời Chúa” không ảnh → bỏ khối ảnh giữ chỗ |
| `inc/post/post-kind.php` | thêm `ct_loichua_thumb_fallback_html()`, `ct_loichua_thumb_enqueue()` |
| `assets/js/ct-media.js` | menu mobile theo sự kiện Bootstrap (sửa `.is-active`), đóng khi chạm nền / Esc, khoá cuộn, nút mở/thu menu con, menu con mở sang trái khi sát mép phải |
| `inc/utilities/enqueue.php` | thêm `ct_nav_menu_enqueue()` (nạp `nav-menu.css` mọi trang) |
| `template-parts/homepage/_section-header.php`, `c_static.php` | **sửa lỗi** `readmore_blank` (xem mục 3) |
| `inc/admin/theme/guide-helpers.php`, `docs/07-*.md`, `docs/08-*.md` | đăng ký bài hướng dẫn mới; cập nhật mô tả Header/Menu |
| `inc/blocks/loichua-card/render.php`, `inc/widget/ct_loichua_card_widget.php`, `functions.php` | chỉ sửa chú thích (trước ghi “CPT ct_loichua” chưa từng tồn tại) |
| `style.css`, `package.json` | `Version: 1.1.0` |
| `languages/chinhtoa.pot` | sinh lại (376 chuỗi) |

**Thêm mới:**

```
inc/loichua/daily.php                     # ct_get_loichua_for_date(), lịch tháng, AJAX, enqueue
inc/loichua/calendar-widget.php           # CT_LoiChua_Calendar_Widget
template-parts/header/header-brand.php
template-parts/homepage/c_daily-word.php  # temp7
template-parts/homepage/c_post-mosaic.php # temp8
template-parts/loichua/daily-card.php
template-parts/loichua/calendar.php
assets/css/header-brand.css               # header c_brand + menu c4
assets/css/daily-word.css                 # khối temp7 + lịch
assets/css/loichua-thumb.css              # câu Lời Chúa thay ảnh (trang chủ, chuyên mục, tìm kiếm)
assets/css/nav-menu.css                   # menu mobile (lớp phủ, menu con) + mũi tên/bàn phím desktop — mọi kiểu menu
assets/js/daily-word.js                   # vanilla JS, không phụ thuộc jQuery
assets/imgs/layouts/nav-style-5.png, assets/imgs/options/home-template-7.png, -8.png
inc/admin/theme/docs/15-loi-chua-hom-nay.md
```

Ngoài thư mục theme: `tests/*.php` (thêm case), `CHANGELOG.md`, `tools/demo/` (script nạp
dữ liệu mẫu cho 5plc — **không** thuộc gói theme).

### 3. Hành vi thay đổi khi nâng cấp (không cần bật gì)

1. **Nút “Xem thêm” của khối trang chủ** — trước đây chọn “Mở tab mới = Có” vẫn mở cùng
   tab (lưu `'y'` nhưng so sánh với `1`). Từ 1.1.0 mở **tab mới** đúng như đã chọn. Site
   nào lỡ chọn “Có” mà quen hành vi cũ thì nên đổi lại “Không”.
2. **Bài loại “Lời Chúa” không có ảnh đại diện:** trước đây danh sách/lưới hiện ảnh trống
   (`ct-no-image.svg`) và trang chi tiết có ảnh giữ chỗ dưới thẻ câu ghi nhớ. Từ 1.1.0, ở
   chỗ ảnh hiện **câu Lời Chúa + trích dẫn** (nền theo biến `--ct-lc-*` của thẻ Lời Chúa),
   trang chi tiết chỉ còn thẻ câu ghi nhớ. Bài có ảnh đại diện, hoặc loại bài khác: không đổi.
3. **Widget “Danh Sách Bài Viết” (kiểu số thứ tự) đổi giao diện:** cột số hẹp lại, số
   chữ đậm căn theo dòng đầu tiêu đề, top 3 màu nhấn – hạng 4 trở đi màu xám, tiêu đề cắt ở 2
   dòng. Không đổi nội dung/tuỳ chọn cũ; có thêm ô “Hiện lượt xem / ngày đăng” (mặc định
   tắt). Kiểu audio không đổi.
4. **Menu trên điện thoại/tablet — mọi kiểu menu:** giao diện mới (thanh trên gọn với nút
   ☰/✕; menu mở dạng lớp phủ: thẻ trắng trên nền mờ; menu con thu gọn có nút mũi tên;
   đóng bằng chạm nền / Esc). Trước đây menu xổ ra đẩy nội dung xuống do lỗi `.is-active`
   (xem CHANGELOG). Khi đăng nhập, thanh menu không còn che phần đầu trang.
   Desktop: thêm mũi tên ▾/› cho mục có menu con, mở được bằng bàn phím, menu cấp 3 sát mép
   phải mở sang trái. Kiểu menu desktop c1–c3 giữ nguyên màu sắc/bố cục.
5. **Cache trình duyệt:** mọi CSS/JS của theme dùng `?ver=1.1.0` → trình duyệt tự tải bản mới.
6. **Admin có thêm lựa chọn** (radio Header, ô menu c4, 2 mẫu khối, widget “Lịch Lời
   Chúa”, 3 ô trong metabox Lời Chúa). Không ô nào tự bật.

Không có: migrate DB, đổi tên option/meta/hook, xoá tính năng, đổi markup front-end của
khối/temp cũ, thêm thư viện bên ngoài.

### 4. Breaking changes & rủi ro

**Không có breaking change về API/dữ liệu.** Các điểm cần lưu ý:

- **Trùng tên hàm/lớp (fatal “Cannot redeclare”)** nếu child theme hoặc plugin đã tự định
  nghĩa cùng tên. Tên mới (global): `ct_loichua_today`, `ct_loichua_sanitize_date`,
  `ct_loichua_sanitize_month`, `ct_loichua_query_base`, `ct_get_loichua_for_date`,
  `ct_loichua_month_map`, `ct_loichua_first_cat`, `ct_loichua_daily_card_html`,
  `ct_loichua_calendar_html`, `ct_loichua_calendar_month_url`, `ct_ajax_daily_word`,
  `ct_ajax_daily_calendar`, `ct_loichua_daily_enqueue`, `ct_header_brand_enqueue`,
  `ct_post_kind_day_fields`, `ct_loichua_thumb_fallback_html`, `ct_loichua_thumb_enqueue`; lớp `CT_LoiChua_Calendar_Widget`; hằng `CT_LC_META_DAY_TITLE`,
  `CT_LC_META_SAINT`, `CT_LC_META_GOSPEL_REF` (có `defined()` guard). Riêng
  `ct_get_loichua_for_date()` từng được nhắc trong chú thích 1.0.0 như một hàm dự kiến —
  site nào đã tự viết hàm này theo chú thích đó sẽ bị trùng.
- **Lớp con kế thừa `CT_PostList_Widget` và ghi đè `render_numbered()`:** chữ ký hàm
  thêm 2 tham số tuỳ chọn → PHP 8 cảnh báo nếu bản ghi đè khai báo ít tham số hơn. Rất
  hiếm; thêm `$show_meta = false, $orderby = 'date'` vào bản ghi đè.
- **Child theme ghi đè `c_post-item.php` / `c_post-item-image.php` / `post/content.php`:**
  bản ghi đè sẽ không có khối câu Lời Chúa thay ảnh (vẫn hiện ảnh trống như 1.0.0).
- **CSS tự viết cho menu mobile cũ** (Mã chèn cuối trang, child theme): `nav-menu.css` dùng
  selector `#site-nav#site-nav…` để thắng luật cũ của từng kiểu menu → CSS tự viết cho menu
  mobile có thể không còn tác dụng; cần viết lại theo cấu trúc mới (thẻ `#ct-main-menu`).
- **Trình duyệt cũ chưa hỗ trợ `:has()`** (trước Safari 15.4 / Firefox 121): mục cha của
  trang đang xem cũng có gạch “—” (chỉ khác về thẩm mỹ).
- **Child theme ghi đè `header.php`:** sẽ không có nhánh `c_brand` → chọn “Logo + khẩu
  hiệu” sẽ rơi về nhánh ảnh banner (rỗng). Cần chép thêm nhánh `elseif` vào bản ghi đè.
- **2 endpoint AJAX công khai mới** (`admin-ajax.php?action=ct_daily_word|ct_daily_calendar`,
  GET, không nonce): chỉ trả bài **đã đăng** (dữ liệu vốn công khai), input lọc chặt
  (regex ngày/tháng, `absint`). Bỏ nonce có chủ đích để không lỗi trên trang bị cache.
  Plugin bảo mật chặn `admin-ajax` cho khách sẽ làm lịch không đổi bài tại chỗ — JS tự
  chuyển sang link thường (mở trang bài / tải lại trang với `?ct_cal=`), không bị kẹt.
- **Transient mới** `trans_lcday_*`, `trans_lcmonth_*`, `trans_mosaic_*` — tự xoá theo
  `ct_flush_post_caches()` như các cache cũ.
- **Hạ cấp về 1.0.0** sau khi đã dùng tính năng mới: khối temp7/temp8 hiển thị trống (không
  lỗi), header `c_brand` thành header ảnh rỗng, menu `c4` mất kiểu riêng (về kiểu cơ bản),
  widget lịch biến mất. Dữ liệu (option/meta) vẫn còn, nâng lại 1.1.0 là hiện lại.
- **Trình duyệt cũ:** CSS mới dùng `color-mix()` (có fallback), `aspect-ratio`,
  `text-wrap: balance` (bỏ qua nếu không hỗ trợ) — hiển thị kém đẹp hơn nhưng không vỡ.
- Yêu cầu tối thiểu **không đổi**: WordPress 5.4+, PHP 7.4+.

### 5. Chép theme sang site khác / thư mục khác

Giao diện của 5plc.local = **code theme** + **cấu hình trong DB** + **nội dung**. Chép thư
mục theme chỉ mang theo phần đầu.

| Thành phần | Nằm ở đâu | Đi theo khi chép theme? |
|---|---|---|
| Tính năng header c_brand, menu c4, temp7/8, lịch, 3 meta | thư mục theme | ✅ có |
| Cấu hình (màu, header, menu c4, danh sách khối trang chủ) | option `ct_settings` | ❌ — đặt lại trong admin, hoặc `wp option get ct_settings --format=json` rồi `wp option update ct_settings --format=json` ở site mới (sửa ID chuyên mục `cats`, URL logo) |
| **CSS riêng của 5plc** (bo góc thẻ, danh sách Liên kết, thẻ câu Lời Chúa tông xanh/chữ đứng, hỏi–đáp) | `ct_settings[tech_data][footerscripts]` (“Mã chèn cuối trang”) | ❌ — chép đoạn `<style>` sang site mới nếu muốn cùng giao diện |
| Định dạng ngày `d/m/Y` | Cài đặt → Tổng quan | ❌ |
| Widget, menu, chuyên mục, bài, ảnh | DB / uploads | ❌ — hoặc chạy `tools/demo/seed-5plc.php` (**xoá sạch nội dung** rồi nạp mẫu) |

**Đổi tên thư mục theme** (5plc đang symlink theme thành `church`): `ct_settings` là option
chung nên giữ nguyên; nhưng **vị trí menu** nằm trong `theme_mods_{tên-thư-mục}` → kích hoạt
dưới tên thư mục khác phải gán lại “Menu Chính” (Giao diện → Menu → Vị trí). Widget thường
được WordPress tự chuyển theo ID sidebar — kiểm tra mục “Tiện ích không sử dụng”.

**Bản white-label** (`REBRAND.md`, `features.loichua = false`): các tính năng Lời Chúa mới
**không** gắn với cờ này — chúng chỉ không chạy khi không bật, nhưng admin vẫn liệt kê mẫu
khối “Lời Chúa hôm nay” và widget “Lịch Lời Chúa”.

### 6. Checklist nâng cấp một site đang chạy 1.0.0

1. Sao lưu DB (`wp db export`) và thư mục theme cũ.
2. Chép đè thư mục theme 1.1.0 (giữ nguyên tên thư mục đang dùng).
3. Nếu có child theme: kiểm tra trùng tên hàm (mục 4) và bản ghi đè `header.php`.
4. Mở trang chủ, một bài, một chuyên mục — giao diện phải như trước.
5. Rà các khối trang chủ có “Mở tab mới = Có” (mục 3.1), bài loại “Lời Chúa” không ảnh
   (mục 3.2), widget “Danh Sách Bài Viết” (mục 3.3) và menu trên điện thoại (mục 3.4).
6. (Tuỳ chọn) bật tính năng mới theo bài hướng dẫn “Lời Chúa hôm nay & Lịch”.
