// Danh sách ảnh minh hoạ. Mỗi mục:
//   id        tên tệp (không đuôi) — Pxx-Cxx-Bxx-yy-noi-dung cho Sổ tay
//   role      'admin' | 'editor' | 'guest'
//   url       đường dẫn trên site (bắt đầu bằng /)   — hoặc file: tệp HTML cục bộ
//   viewport  [rộng, cao] CSS px (mặc định 1280×900)
//   setup     async (page, h) => {...}  thao tác KHÔNG lưu (mở tab, bật công tắc tạm…)
//   clip      selector | [selector…] | {sel, pad, maxH} | {x,y,w,h} | 'viewport'
//   marks     [{ sel, n, side?, noBox? }]  khung đỏ + số (side: left|right|top|bottom|tl|tr|inside)
//   guide     tên tệp .webp cho trang Hướng dẫn trong WordPress (tuỳ chọn)
//   keepAdminBar / hideAdminMenu / css / mobile
//
// Quy ước: SỐ TRÊN ẢNH = SỐ THỨ TỰ BƯỚC trong bài tương ứng.
// An toàn: mọi request ghi dữ liệu bị chặn (lib.mjs) — bật công tắc, gõ chữ… chỉ là tạm.

const SETTINGS = '/wp-admin/admin.php?page=ct-theme-settings';
const POST_LC = 1433;      // bài Lời Chúa hôm nay (Suy niệm)
const POST_FUTURE = 1458;  // bài Lời Chúa đã hẹn giờ
const POST_TV = 1469;      // bài thường (Từ vựng) có ảnh đại diện
const PAGE_CONTACT = 1398; // trang Liên hệ
const PAGE_HOME = 1397;    // trang Trang chủ
const CAT_GIAOLY = 86;
const MENU_MAIN = 89;
const ATTACH = 1500;

const YOAST_COLS = '.column-wpseo-score,.column-wpseo-score-readability,.column-wpseo-links,.column-wpseo-linked,.column-wpseo-title,.column-wpseo-metadesc,.column-wpseo-focuskw,select[name=seo_filter],select[name=readability_filter]{display:none!important}';
const NO_SNACK = '.components-snackbar-list,.components-notice-list,.editor-post-publish-panel__prepublish .components-notice{display:none!important}';

// Đặt chuỗi vào dấu nháy phù hợp cho :text-is() (nhãn có dấu " như  Bật khối "Tin Hot").
const q = (t) => (t.includes('"') ? `'${t}'` : `"${t}"`);
// Hàng trong bảng thiết lập (Thiết lập giao diện, Cài đặt WordPress…).
const row = (label) => `tr:has(> th:text-is(${q(label)}))`;
// Hàng có nhãn BẮT ĐẦU bằng chuỗi (nhãn kèm "(bắt buộc)", khoảng trắng lạ…).
const rowStarts = (label) => `tr:has(> th:text-matches("^${label.replace(/[()]/g, '\\$&')}"))`;
// Hàng trong hộp tuỳ chỉnh (chuyên mục / bài viết).
const mbRow = (label) => `.ct-meta-box .ct-field-row:has(.ct-mb-label:text-is(${q(label)}))`;
// Nhóm trong một khối trang chủ (khối thứ i, mẫu đang chọn).
const secGroup = (i, title) => `.ct-sec-row[data-index="${i}"] .ct-tpl-group:not(.is-hidden) .ct-sec-group:has(.ct-sec-group-title:text-is(${q(title)}))`;
const reEsc = (t) => t.replace(/[.*+?^${}()|[\]\\"]/g, '\\$&');
const secField = (i, label) => `.ct-sec-row[data-index="${i}"] .ct-tpl-group:not(.is-hidden) .ct-sec-field:has(> label:text-matches("^${reEsc(label)}"))`;

// Mở tab/tab con trên trang Thiết lập giao diện.
const openTab = (group, sub) => async (page, h) => {
  await h.click(`a.ct-tab[data-tab="${group}"]`);
  if (sub) await h.click(`.ct-tab-panel[data-tab="${group}"] a.ct-subtab[data-subtab="${sub}"]`);
};
// Bật một công tắc trong Thiết lập giao diện (chỉ tạm, không lưu).
const switchOn = (label) => async (page, h) => { await h.check(`${row(label)} input[type=checkbox]`); };
// Thu gọn mọi khối trang chủ, chỉ mở các khối trong danh sách `keep`.
const onlyBlocks = (keep = []) => async (page) => {
  await page.evaluate((keep) => {
    document.querySelectorAll('.ct-sec-row').forEach((r) => {
      const open = keep.includes(+r.dataset.index);
      const isCollapsed = r.classList.contains('is-collapsed');
      if (open === isCollapsed) r.querySelector('.ct-sec-toggle').click();
    });
  }, keep);
  await page.waitForTimeout(300);
};

// Gutenberg: bỏ hộp chào mừng, chờ khung soạn thảo sẵn sàng, thay tên tác giả.
const gutenberg = (fn) => async (page, h) => {
  await page.locator('.editor-header').first().waitFor({ timeout: 45000 });
  await page.evaluate(() => {
    try {
      const p = window.wp && wp.data && wp.data.dispatch('core/preferences');
      if (p) { p.set('core/edit-post', 'welcomeGuide', false); p.set('core', 'fixedToolbar', false); p.set('core', 'distractionFree', false); }
    } catch (e) { /* ignore */ }
  });
  await h.css('.components-modal__screen-overlay{display:none!important}' + NO_SNACK);
  await page.frameLocator('iframe[name="editor-canvas"]').locator('body').waitFor({ timeout: 30000 }).catch(() => {});
  await h.wait(900);
  await page.evaluate(() => {
    document.querySelectorAll('.editor-post-author__panel-toggle, .editor-post-author__panel .components-button').forEach((b) => {
      if (b.textContent.trim() === 'admin') b.textContent = 'Quản lý';
    });
  });
  if (fn) await fn(page, h);
};
// Bảng (panel) trong cột cài đặt bên phải Gutenberg, theo tiêu đề.
const panel = (title) => `.components-panel__body:has(.components-panel__body-toggle:has-text("${title}"))`;
// Mở bảng nếu đang đóng.
const openPanel = async (page, h, title) => {
  const btn = page.locator(`.components-panel__body-toggle:has-text("${title}")`).first();
  if ((await btn.getAttribute('aria-expanded')) === 'false') { await btn.click(); await h.wait(300); }
};
// Hiện các liên kết thao tác (Chỉnh sửa | Sửa nhanh | Xóa tạm | Xem) của một hàng.
const showRowActions = (rowSel) => `${rowSel} .row-actions{left:0!important;position:static!important}`;

export default [
  // =================================================================== PHẦN 1
  {
    id: 'P01-C01-B01-01-website-cong-khai', role: 'guest', url: '/', viewport: [1280, 960],
    setup: async (page, h) => { await h.waitFor('.ct__daily'); await h.wait(800); },
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '.header-brand', n: 1, side: 'inside' },
      { sel: '#site-nav', n: 2, side: 'inside' },
      { sel: '.ct__daily', n: 3, side: 'inside' },
      { sel: '#ct-sidebar #ct_lc_calendar-2', n: 4, side: 'inside' },
    ],
    guide: 'website.webp',
  },
  {
    id: 'P01-C01-B02-01-man-hinh-dang-nhap', role: 'guest', url: '/wp-login.php', viewport: [1100, 760],
    clip: { sel: '#login', pad: [10, 60, 10, 60] },
    marks: [
      { sel: ['label[for=user_login]', '#user_login'], n: 1, side: 'left' },
      { sel: ['label[for=user_pass]', '.wp-pwd'], n: 2, side: 'left' },
      { sel: '.forgetmenot', n: 3, side: 'left' },
      { sel: '#wp-submit', n: 4, side: 'right' },
      { sel: '#nav', n: 5, side: 'right' },
    ],
    guide: 'dang-nhap.webp',
  },
  {
    id: 'P01-C01-B04-01-bang-dieu-khien', role: 'editor', url: '/wp-admin/admin.php?page=chinhtoa-intro', viewport: [1280, 800],
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '#adminmenu', n: 1, side: 'right' },
      { sel: '.ct-intro-cards', n: 2, side: 'tr' },
    ],
    guide: 'bang-dieu-khien.webp',
  },
  {
    id: 'P01-C01-B05-01-menu-trai-thanh-cong-cu', role: 'editor', url: '/wp-admin/admin.php?page=chinhtoa-intro', viewport: [1280, 560], keepAdminBar: true,
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '#wpadminbar', n: 1, side: 'inside' },
      { sel: '#adminmenu', n: 2, side: 'inside' },
    ],
  },
  {
    id: 'P01-C01-B06-01-xem-website', role: 'editor', url: '/wp-admin/admin.php?page=chinhtoa-intro', viewport: [1280, 500], keepAdminBar: true,
    css: '#wp-admin-bar-site-name .ab-sub-wrapper{display:block!important}',
    clip: { x: 0, y: 0, w: 560, h: 150 },
    marks: [
      { sel: '#wp-admin-bar-site-name > a', n: 1, side: 'right' },
      { sel: '#wp-admin-bar-view-site', n: 2, side: 'right' },
    ],
  },
  {
    id: 'P01-C01-B07-01-dang-xuat', role: 'editor', url: '/wp-admin/admin.php?page=chinhtoa-intro', viewport: [1280, 500], keepAdminBar: true,
    css: '#wp-admin-bar-my-account .ab-sub-wrapper{display:block!important}',
    clip: { x: 860, y: 0, w: 420, h: 240 },
    marks: [
      { sel: '#wp-admin-bar-my-account > a', n: 1, side: 'left' },
      { sel: '#wp-admin-bar-logout', n: 2, side: 'left' },
    ],
  },
  {
    id: 'P01-C02-B03-01-khu-vuc-quan-ly', role: 'admin', url: '/wp-admin/edit.php', viewport: [1280, 720],
    css: '#wpbody{visibility:hidden}',
    clip: { x: 0, y: 0, w: 230, h: 700 }, growClip: true,
    marks: [
      { sel: '#menu-appearance', n: 1, side: 'right', strict: true },
      { sel: '#menu-plugins', n: 2, side: 'right', strict: true },
      { sel: '#menu-users', n: 3, side: 'right', strict: true },
      { sel: '#menu-settings', n: 4, side: 'right', strict: true },
    ],
  },

  // =================================================================== PHẦN 2
  {
    id: 'P02-C03-B01-01-danh-sach-bai-viet', role: 'editor', url: '/wp-admin/edit.php', viewport: [1280, 640], css: YOAST_COLS,
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '#menu-posts .wp-submenu a[href="edit.php"]', n: 1, side: 'right' },
      { sel: '.wp-list-table', n: 2, side: 'tr' },
    ],
  },
  {
    id: 'P02-C03-B02-01-cot-va-trang-thai', role: 'editor', url: '/wp-admin/edit.php', viewport: [1280, 700], css: YOAST_COLS, hideAdminMenu: true,
    clip: { sel: ['ul.subsubsub', '#the-list tr:nth-child(3)'], pad: [10, 16, 10, 40] },
    marks: [
      { sel: 'ul.subsubsub', n: 1, side: 'right' },
      { sel: 'th#title', n: 2, side: 'inside' },
      { sel: 'th#categories', n: 3, side: 'inside' },
      { sel: 'th#date', n: 4, side: 'inside' },
      { sel: '#the-list tr:first-child .post-state', n: 5, side: 'right' },
    ],
  },
  {
    id: 'P02-C03-B03-01-tim-kiem', role: 'editor', url: '/wp-admin/edit.php', viewport: [1280, 500], css: YOAST_COLS, hideAdminMenu: true,
    setup: async (page, h) => { await h.fill('#post-search-input', 'Lời Chúa'); },
    clip: { sel: ['h1.wp-heading-inline', '.search-box'], pad: [10, 16, 12, 16] },
    marks: [
      { sel: '#post-search-input', n: 1, side: 'left' },
      { sel: '#search-submit', n: 2, side: 'bottom' },
    ],
  },
  {
    id: 'P02-C03-B04-01-loc-thang-danh-muc', role: 'editor', url: '/wp-admin/edit.php', viewport: [1280, 500], css: YOAST_COLS, hideAdminMenu: true,
    clip: { sel: '.tablenav.top', pad: [40, 16, 16, 16] },
    marks: [
      { sel: '#filter-by-date', n: 1, side: 'top' },
      { sel: '#cat', n: 2, side: 'top' },
      { sel: '#post-query-submit', n: 3, side: 'top' },
    ],
  },
  {
    id: 'P02-C03-B05-01-thao-tac-tren-hang', role: 'editor', url: '/wp-admin/edit.php', viewport: [1280, 600], hideAdminMenu: true,
    css: YOAST_COLS + showRowActions('#the-list tr:nth-child(1)') + '.wp-list-table .column-title{width:46%}',
    clip: { sel: ['table.wp-list-table thead', '#the-list tr:nth-child(1)'], pad: [8, 16, 8, 50] },
    marks: [
      { sel: '#the-list tr:nth-child(1) a.row-title', n: 1, side: 'left' },
      { sel: '#the-list tr:nth-child(1) .row-actions', n: 2, side: 'left' },
    ],
  },
  {
    id: 'P02-C04-B01-01-viet-bai-moi', role: 'editor', url: '/wp-admin/edit.php', viewport: [1280, 500], css: YOAST_COLS,
    clip: { x: 0, y: 0, w: 640, h: 330 },
    marks: [
      { sel: '#menu-posts .wp-submenu a[href="post-new.php"]', n: 1, side: 'right' },
      { sel: '.page-title-action', n: 2, side: 'right' },
    ],
    guide: 'viet-bai-moi.webp',
  },
  {
    id: 'P02-C04-B02-01-tieu-de-noi-dung', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 720],
    setup: gutenberg(async (page, h) => {
      await h.type('canvas:.editor-post-title', 'Thông báo lịch Thánh lễ tuần này');
      await h.press('Enter');
      await page.keyboard.type('Kính mời cộng đoàn tham dự Thánh lễ lúc 5 giờ sáng và 6 giờ chiều các ngày trong tuần.', { delay: 2 });
      await h.wait(500);
    }),
    clip: 'viewport', growClip: false,
    marks: [
      { sel: 'canvas:.editor-post-title', n: 1, side: 'top' },
      { sel: 'canvas:p.block-editor-rich-text__editable', n: 2, side: 'bottom' },
    ],
  },
  {
    id: 'P02-C04-B03-01-thanh-cong-cu', role: 'editor', url: '/wp-admin/post-new.php', viewport: [960, 500],
    setup: gutenberg(async (page, h) => { await h.type('canvas:.editor-post-title', 'Thông báo lịch Thánh lễ tuần này'); await page.mouse.click(640, 400); await h.wait(500); }),
    clip: { sel: '.editor-header', pad: [4, 4, 56, 4] },
    marks: [
      { sel: '.editor-document-tools__inserter-toggle', n: 1, side: 'bottom' },
      { sel: ['.editor-history__undo', '.editor-history__redo'], n: 2, side: 'bottom' },
      { sel: '.editor-document-tools__document-overview-toggle', n: 3, side: 'bottom' },
      { sel: '.editor-preview-dropdown__toggle', n: 4, side: 'bottom' },
      { sel: '.editor-header__settings button[aria-label="Cài đặt"]', n: 5, side: 'bottom' },
      { sel: '.editor-post-save-draft', n: 6, side: 'bottom' },
      { sel: '.editor-post-publish-button__button', n: 7, side: 'bottom' },
    ],
    guide: 'thanh-cong-cu-soan-bai.webp',
  },
  {
    id: 'P02-C04-B03-02-thu-vien-khoi', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 820],
    setup: gutenberg(async (page, h) => { await h.click('.editor-document-tools__inserter-toggle'); await h.wait(600); }),
    clip: { x: 0, y: 0, w: 760, h: 820 }, growClip: false,
    marks: [
      { sel: '.editor-document-tools__inserter-toggle', n: 1, side: 'right' },
      { sel: '.block-editor-inserter__search', n: 2, side: 'right' },
      { sel: '.block-editor-block-types-list__item:has-text("Văn bản")', n: 3, side: 'right' },
      { sel: '.block-editor-block-types-list__item:has-text("Hình ảnh")', n: 4, side: 'right' },
    ],
  },
  {
    id: 'P02-C04-B04-01-thanh-cong-cu-khoi', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 600],
    setup: gutenberg(async (page, h) => {
      await h.type('canvas:.editor-post-title', 'Bài viết thử');
      await h.press('Enter');
      await h.press('Enter');
      await h.press('Enter');
      await page.keyboard.type('Đây là một đoạn văn. Chọn chữ rồi bấm nút trên thanh công cụ để in đậm.', { delay: 2 });
      await page.keyboard.down('Shift');
      for (let i = 0; i < 6; i++) await page.keyboard.press('ArrowLeft');
      await page.keyboard.up('Shift');
      await h.wait(600);
    }),
    clip: { sel: ['.block-editor-block-contextual-toolbar', 'canvas:p.block-editor-rich-text__editable >> nth=-1'], pad: [50, 30, 20, 30] },
    marks: [
      { sel: '.block-editor-block-toolbar button[aria-label="Văn bản"]', n: 1, side: 'top' },
      { sel: '.block-editor-block-toolbar button[aria-label="Căn lề văn bản"]', n: 2, side: 'top' },
      { sel: ['.block-editor-block-toolbar button[aria-label="Đậm"]', '.block-editor-block-toolbar button[aria-label="Nghiêng"]'], n: 3, side: 'top' },
      { sel: '.block-editor-block-toolbar button[aria-label="Liên kết"]', n: 4, side: 'top' },
      { sel: '.block-editor-block-toolbar button[aria-label="Tùy chọn"]', n: 5, side: 'top' },
    ],
  },
  {
    id: 'P02-C04-B09-01-bang-cai-dat', role: 'editor', url: `/wp-admin/post.php?post=${POST_TV}&action=edit`, viewport: [1280, 820],
    setup: gutenberg(),
    clip: { sel: ['.editor-header__settings', '.interface-complementary-area'], pad: [4, 4, 4, 30], maxH: 820 },
    marks: [
      { sel: '.editor-header__settings button[aria-label="Cài đặt"]', n: 1, side: 'left' },
      { sel: '.editor-sidebar__panel-tabs', n: 2, side: 'left' },
      { sel: '.interface-complementary-area button[aria-label="Đóng cài đặt"]', n: 3, side: 'bottom' },
    ],
  },
  {
    id: 'P02-C05-B01-01-chen-anh', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 760],
    setup: gutenberg(async (page, h) => {
      await h.click('.editor-document-tools__inserter-toggle');
      await h.wait(500);
      await page.locator('.block-editor-block-types-list__item:has-text("Hình ảnh")').first().click();
      await h.wait(800);
      await h.click('.editor-document-tools__inserter-toggle').catch(() => {});
      await h.wait(400);
    }),
    clip: { sel: ['canvas:.wp-block-image', 'canvas:.editor-post-title'], pad: [16, 24, 40, 24] },
    marks: [
      { sel: 'canvas:.wp-block-image button:has-text("Tải lên")', n: 1, side: 'bottom' },
      { sel: 'canvas:.wp-block-image button:has-text("Tất cả tập tin")', n: 2, side: 'bottom' },
      { sel: 'canvas:.wp-block-image button:has-text("Chèn từ URL")', n: 3, side: 'bottom' },
    ],
  },
  {
    id: 'P02-C05-B02-01-nut-anh-dai-dien', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 600],
    setup: gutenberg(),
    clip: { sel: '.interface-complementary-area', maxH: 460 },
    marks: [{ sel: '.editor-post-featured-image__toggle', n: 1, side: 'left' }],
  },
  {
    id: 'P02-C05-B02-02-chon-anh-dai-dien', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 860],
    css: '.setting[data-setting="url"] input, .attachment-details-copy-link, #attachment-details-two-column-copy-link, #attachment-details-copy-link{filter:blur(4px)}',
    setup: gutenberg(async (page, h) => {
      await h.click('.editor-post-featured-image__toggle');
      await h.waitFor('.media-modal');
      await page.locator('.media-modal .media-router .media-menu-item:has-text("Tất cả tập tin")').first().click();
      await h.waitFor('.media-modal .attachments .attachment');
      await page.locator('.media-modal .attachments .attachment').first().click();
      await h.wait(900);
    }),
    clip: '.media-modal', growClip: false,
    marks: [
      { sel: '.media-modal .media-router .media-menu-item:has-text("Tất cả tập tin")', n: 1, side: 'right' },
      { sel: '.media-modal .attachments .attachment.selected', n: 2, side: 'inside' },
      { sel: '.media-modal .attachment-details .setting[data-setting="alt"]', n: 3, side: 'inside' },
      { sel: '.media-modal .media-button-select', n: 4, side: 'left' },
    ],
    guide: 'anh-dai-dien.webp',
  },
  {
    id: 'P02-C05-B03-01-chon-danh-muc', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 900],
    setup: gutenberg(async (page, h) => {
      await openPanel(page, h, 'Danh mục');
      await h.scrollIntoView(`${panel("Danh mục")}`, 'center');
    }),
    clip: { sel: `${panel("Danh mục")}`, pad: [10, 10, 10, 40] },
    marks: [
      { sel: '.components-panel__body-toggle:has-text("Danh mục")', n: 1, side: 'left' },
      { sel: '.editor-post-taxonomies__hierarchical-terms-list', n: 2, side: 'left' },
      { sel: '.editor-post-taxonomies__hierarchical-terms-add', n: 3, side: 'left' },
    ],
    guide: 'chon-danh-muc.webp',
  },
  {
    id: 'P02-C05-B04-01-the', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 900],
    setup: gutenberg(async (page, h) => {
      await openPanel(page, h, 'Thẻ');
      await h.scrollIntoView(`${panel("Thẻ")}`, 'center');
    }),
    clip: { sel: `${panel("Thẻ")}`, pad: [10, 10, 10, 40] },
    marks: [{ sel: `${panel("Thẻ")} .components-form-token-field`, n: 1, side: 'left' }],
  },
  {
    id: 'P02-C05-B05-01-mo-ta-ngan', role: 'editor', url: `/wp-admin/post.php?post=${POST_TV}&action=edit`, viewport: [1280, 820],
    setup: gutenberg(async (page, h) => {
      await page.locator('.interface-complementary-area button:has-text("mô tả ngắn")').first().click();
      await h.wait(600);
    }),
    clip: { sel: ['.interface-complementary-area .editor-post-card-panel', '.components-popover:has(textarea)'], pad: [10, 10, 10, 30] },
    marks: [
      { sel: '.components-popover textarea', n: 1, side: 'bottom' },
    ],
  },
  {
    id: 'P02-C05-B06-01-xem-truoc', role: 'editor', url: `/wp-admin/post.php?post=${POST_TV}&action=edit`, viewport: [1280, 520],
    setup: gutenberg(async (page, h) => { await h.click('.editor-preview-dropdown__toggle'); await h.wait(500); }),
    clip: { sel: ['.editor-preview-dropdown__toggle', '.components-dropdown-menu__menu'], pad: [10, 20, 14, 40] },
    marks: [
      { sel: '.editor-preview-dropdown__toggle', n: 1, side: 'bottom' },
      { sel: '.components-dropdown-menu__menu .components-menu-group >> nth=0', n: 2, side: 'left' },
      { sel: '.components-dropdown-menu__menu button:has-text("Xem trước ở tab mới"), .components-dropdown-menu__menu a:has-text("Xem trước ở tab mới")', n: 3, side: 'left' },
    ],
  },
  {
    id: 'P02-C05-B09-01-luu-thay-doi', role: 'editor', url: `/wp-admin/post.php?post=${POST_TV}&action=edit`, viewport: [1280, 400],
    setup: gutenberg(),
    clip: { sel: ['.editor-header__settings'], pad: [8, 8, 50, 16] },
    marks: [
      { sel: '.editor-header__settings a[aria-label="Xem bài viết"]', n: 1, side: 'bottom' },
      { sel: '.editor-post-publish-button', n: 2, side: 'bottom' },
    ],
  },
  {
    id: 'P02-C05-B10-01-hen-gio', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 900],
    setup: gutenberg(async (page, h) => { await h.click('.editor-post-schedule__dialog-toggle'); await h.wait(600); }),
    clip: { sel: ['.editor-post-schedule__dialog-toggle', '.components-popover:has(.components-datetime)'], pad: [12, 16, 12, 40] },
    marks: [
      { sel: '.editor-post-schedule__dialog-toggle', n: 1, side: 'right' },
      { sel: '.components-datetime__time', n: 2, side: 'left' },
      { sel: '.components-datetime__date', n: 3, side: 'left' },
    ],
    guide: 'hen-gio.webp',
  },
  {
    id: 'P02-C06-B01-01-sua-nhanh', role: 'editor', url: '/wp-admin/edit.php', viewport: [1280, 760], hideAdminMenu: true,
    css: YOAST_COLS + showRowActions('#the-list tr:nth-child(2)'),
    setup: async (page, h) => { await h.click('#the-list tr:nth-child(2) button.editinline'); await h.wait(500); },
    clip: { sel: 'tr.inline-edit-row', pad: [10, 16, 10, 16] },
    marks: [
      { sel: 'tr.inline-edit-row input.ptitle', n: 1, side: 'tl' },
      { sel: 'tr.inline-edit-row .inline-edit-categories', n: 2, side: 'top' },
      { sel: 'tr.inline-edit-row select[name="_status"]', n: 3, side: 'right' },
      { sel: 'tr.inline-edit-row .inline-edit-save .save', n: 4, side: 'bottom' },
    ],
  },

  // =================================================================== PHẦN 3
  {
    id: 'P03-C07-B02-01-danh-sach-danh-muc', role: 'editor', url: '/wp-admin/edit-tags.php?taxonomy=category', viewport: [1280, 760], css: YOAST_COLS,
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '#menu-posts .wp-submenu a[href="edit-tags.php?taxonomy=category"]', n: 1, side: 'right' },
      { sel: '#col-right .wp-list-table', n: 2, side: 'tr' },
      { sel: '#col-left .form-wrap', n: 3, side: 'tr' },
    ],
  },
  {
    id: 'P03-C07-B03-01-them-danh-muc', role: 'editor', url: '/wp-admin/edit-tags.php?taxonomy=category', viewport: [1280, 1100], css: YOAST_COLS, hideAdminMenu: true,
    setup: async (page, h) => { await h.fill('#tag-name', 'Thông báo giáo xứ'); },
    clip: { sel: '#col-left', pad: [10, 20, 10, 50] },
    marks: [
      { sel: '.term-name-wrap', n: 1, side: 'left' },
      { sel: '.term-slug-wrap', n: 2, side: 'left' },
      { sel: '.term-parent-wrap', n: 3, side: 'left' },
      { sel: '.term-description-wrap', n: 4, side: 'left' },
      { sel: '#col-left #submit', n: 5, side: 'right' },
    ],
    guide: 'them-danh-muc.webp',
  },
  {
    id: 'P03-C07-B05-01-sua-danh-muc', role: 'editor', url: `/wp-admin/term.php?taxonomy=category&tag_ID=${CAT_GIAOLY}`, viewport: [1280, 1000], hideAdminMenu: true,
    clip: { sel: ['h1', 'tr.term-description-wrap'], pad: [10, 20, 10, 50] },
    marks: [
      { sel: 'tr.term-name-wrap', n: 1, side: 'left' },
      { sel: 'tr.term-slug-wrap', n: 2, side: 'left' },
      { sel: 'tr.term-parent-wrap', n: 3, side: 'left' },
      { sel: 'tr.term-description-wrap', n: 4, side: 'left' },
    ],
  },
  {
    id: 'P03-C07-B07-01-xoa-danh-muc', role: 'editor', url: '/wp-admin/edit-tags.php?taxonomy=category', viewport: [1280, 800], hideAdminMenu: true,
    css: YOAST_COLS + showRowActions('#the-list tr'),
    clip: { sel: '#col-right .wp-list-table', pad: [10, 16, 10, 50] },
    marks: [
      { sel: '#the-list tr:has(a.row-title:text-is("Giáo lý")) .row-actions', n: 1, side: 'left' },
      { sel: '#the-list tr:has(a.row-title:text-is("Suy niệm")) .row-actions', n: 2, side: 'left' },
    ],
  },
  {
    id: 'P03-C08-B01-01-tuy-chinh-chuyen-muc', role: 'admin', url: `/wp-admin/term.php?taxonomy=category&tag_ID=${CAT_GIAOLY}`, viewport: [1280, 1500], hideAdminMenu: true,
    setup: async (page, h) => {
      await h.click(`${mbRow('Tuỳ chỉnh riêng chuyên mục này')} .ct-switch`);
      await h.wait(400);
      await h.scrollTo('.ct-meta-box', 60);
    },
    clip: { sel: '.ct-meta-box', pad: [12, 20, 12, 50] },
    marks: [
      { sel: mbRow('Tuỳ chỉnh riêng chuyên mục này'), n: 1, side: 'left' },
      { sel: mbRow('Số cột danh sách'), n: 2, side: 'left' },
      { sel: mbRow('Kiểu trình bày'), n: 3, side: 'left' },
      { sel: [mbRow('Hiển thị thanh bên (sidebar)')], n: 4, side: 'left' },
      { sel: [mbRow('Hình đại diện'), mbRow('Ngày & lượt xem')], n: 5, side: 'left' },
    ],
    guide: 'tuy-chinh-chuyen-muc.webp',
  },

  // =================================================================== PHẦN 4
  {
    id: 'P04-C10-B01-01-thu-vien', role: 'editor', url: '/wp-admin/upload.php?mode=grid', viewport: [1280, 760],
    setup: async (page, h) => { await h.waitFor('.attachments .attachment'); await h.wait(600); },
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '#menu-media', n: 1, side: 'right' },
      { sel: '.page-title-action', n: 2, side: 'right' },
      { sel: '.media-toolbar', n: 3, side: 'inside' },
    ],
    guide: 'thu-vien.webp',
  },
  {
    id: 'P04-C10-B02-01-tai-anh-moi', role: 'editor', url: '/wp-admin/media-new.php', viewport: [1280, 560], hideAdminMenu: true,
    clip: { sel: ['h1', '.max-upload-size'], pad: [10, 20, 16, 40] },
    marks: [
      { sel: '#drag-drop-area', n: 1, side: 'left' },
      { sel: '#plupload-browse-button', n: 2, side: 'right' },
      { sel: '.max-upload-size', n: 3, side: 'right' },
    ],
  },
  {
    id: 'P04-C10-B04-01-chi-tiet-anh', role: 'editor', url: `/wp-admin/upload.php?item=${ATTACH}`, viewport: [1280, 900],
    css: '.setting[data-setting="url"] input, .attachment-details-copy-link, #attachment-details-two-column-copy-link, #attachment-details-copy-link{filter:blur(4px)}',
    setup: async (page, h) => { await h.waitFor('.media-modal .attachment-info'); await h.wait(900); },
    clip: '.media-modal', growClip: false,
    marks: [
      { sel: '.media-modal .setting[data-setting="alt"]', n: 1, side: 'inside' },
      { sel: '.media-modal .setting[data-setting="title"]', n: 2, side: 'inside' },
      { sel: '.media-modal .setting[data-setting="caption"]', n: 3, side: 'inside' },
      { sel: '.media-modal .setting[data-setting="description"]', n: 4, side: 'inside' },
      { sel: '.media-modal .edit-attachment', n: 5, side: 'right' },
      { sel: '.media-modal .delete-attachment', n: 6, side: 'right' },
    ],
    guide: 'chi-tiet-anh.webp',
  },

  // =================================================================== PHẦN 5
  {
    id: 'P05-C11-B02-01-danh-sach-trang', role: 'editor', url: '/wp-admin/edit.php?post_type=page', viewport: [1280, 520], css: YOAST_COLS + '.column-views{display:none!important}',
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '#menu-pages .wp-submenu a[href="edit.php?post_type=page"]', n: 1, side: 'right' },
      { sel: '.page-title-action', n: 2, side: 'right' },
      { sel: '#the-list tr:has-text("Trang chủ") .post-state', n: 3, side: 'right' },
    ],
  },
  {
    id: 'P05-C11-B04-01-sua-trang', role: 'editor', url: `/wp-admin/post.php?post=${PAGE_CONTACT}&action=edit`, viewport: [1280, 720],
    setup: gutenberg(),
    clip: 'viewport', growClip: false,
    marks: [
      { sel: 'canvas:.editor-post-title', n: 1, side: 'bottom' },
      { sel: '.editor-post-publish-button', n: 2, side: 'bottom' },
    ],
  },
  {
    id: 'P05-C12-B01-01-mau-trang-chu', role: 'admin', url: `/wp-admin/post.php?post=${PAGE_HOME}&action=edit`, viewport: [1280, 820],
    css: '.editor-post-url__panel-toggle, .editor-post-url__panel-dropdown .components-button{filter:blur(4px)}',
    setup: gutenberg(),
    clip: { sel: '.interface-complementary-area', maxH: 720, pad: [4, 4, 4, 30] },
    marks: [{ sel: '.editor-post-panel__row:has(.editor-post-panel__row-label:text-is("Mẫu trang"))', n: 1, side: 'left' }],
  },
  {
    id: 'P05-C12-B04-01-cai-dat-doc', role: 'admin', url: '/wp-admin/options-reading.php', viewport: [1280, 700],
    clip: { sel: ['h1', row('Trang chủ của bạn hiển thị')], pad: [10, 20, 10, 16] },
    hideAdminMenu: true,
    marks: [
      { sel: row('Trang chủ của bạn hiển thị'), n: 1, side: 'right' },
    ],
  },

  // =================================================================== PHẦN 6
  {
    id: 'P06-C13-B01-01-mo-thiet-lap', role: 'admin', url: SETTINGS, viewport: [1280, 500],
    clip: { x: 0, y: 0, w: 760, h: 200 },
    marks: [
      { sel: '#toplevel_page_theme-options > a', n: 1, side: 'right' },
      { sel: '#toplevel_page_theme-options .wp-submenu a[href*="ct-theme-settings"]', n: 2, side: 'right' },
    ],
  },
  {
    id: 'P06-C13-B02-01-bon-nhom', role: 'admin', url: SETTINGS, viewport: [1280, 500], hideAdminMenu: true,
    css: '.ct-options-wrap > h1{visibility:hidden}',
    clip: { sel: ['.ct-options-wrap .ct-tabs', '.ct-subtabs'], pad: [40, 20, 16, 16] },
    marks: [
      { sel: 'a.ct-tab[data-tab="giaodien"]', n: 1, side: 'top' },
      { sel: 'a.ct-tab[data-tab="noidung"]', n: 2, side: 'top' },
      { sel: 'a.ct-tab[data-tab="tienich"]', n: 3, side: 'top' },
      { sel: 'a.ct-tab[data-tab="nangcao"]', n: 4, side: 'top' },
      { sel: '.ct-tab-panel[data-tab="giaodien"] .ct-subtabs', n: 5, side: 'right' },
    ],
  },
  {
    id: 'P06-C13-B04-01-da-luu', role: 'admin', url: SETTINGS + '&settings-updated=true', viewport: [1280, 500], hideAdminMenu: true,
    css: '.ct-options-wrap > .notice-success{display:block!important}',
    clip: { sel: ['.ct-options-wrap h1', '.ct-tabs'], pad: [10, 20, 10, 16] },
    marks: [{ sel: '.ct-options-wrap > .notice-success', n: 1, side: 'left' }],
  },
  {
    id: 'P06-C14-B01-01-mau-giao-dien', role: 'admin', url: SETTINGS, viewport: [1280, 900], hideAdminMenu: true,
    clip: { sel: [row('Màu giao diện'), row('Màu tự chọn')], pad: [12, 20, 12, 50] },
    marks: [
      { sel: row('Màu giao diện'), n: 1, side: 'left' },
      { sel: row('Màu tự chọn'), n: 2, side: 'left' },
    ],
    guide: 'mau-giao-dien.webp',
  },
  {
    id: 'P06-C14-B01-02-nen-trang', role: 'admin', url: SETTINGS, viewport: [1280, 1600], hideAdminMenu: true,
    clip: { sel: [row('Nền trang'), row('Màu nền')], pad: [12, 20, 12, 50] },
    marks: [
      { sel: row('Nền trang'), n: 1, side: 'left' },
      { sel: row('Màu nền'), n: 2, side: 'left' },
    ],
  },
  {
    id: 'P06-C14-B02-01-kieu-thanh-menu', role: 'admin', url: SETTINGS, viewport: [1280, 1500], hideAdminMenu: true,
    clip: { sel: [row('Kiểu thanh menu'), row('Màu nhấn thanh menu')], pad: [12, 20, 12, 50] },
    marks: [
      { sel: row('Kiểu thanh menu'), n: 1, side: 'left' },
      { sel: row('Màu nền thanh menu'), n: 2, side: 'left' },
      { sel: row('Màu chữ thanh menu'), n: 3, side: 'left' },
      { sel: row('Màu nhấn thanh menu'), n: 4, side: 'left' },
    ],
    guide: 'kieu-thanh-menu.webp',
  },
  {
    id: 'P06-C14-B02-02-bon-kieu-menu', role: 'guest', file: 'gallery/kieu-menu.html', viewport: [940, 700],
    clip: { sel: '#gallery', pad: 0 },
    guide: 'bon-kieu-menu.webp',
  },
  {
    id: 'P06-C14-B03-01-chon-kieu-header', role: 'admin', url: SETTINGS, viewport: [1280, 800], hideAdminMenu: true,
    setup: openTab('giaodien', 'header'),
    clip: { sel: ['.ct-tab-panel[data-tab="giaodien"] .ct-subtabs', row('Kiểu Header')], pad: [10, 20, 10, 50] },
    marks: [
      { sel: 'a.ct-subtab[data-subtab="header"]', n: 1, side: 'right' },
      { sel: row('Kiểu Header'), n: 2, side: 'left' },
    ],
  },
  {
    id: 'P06-C14-B04-01-header-logo-khau-hieu', role: 'admin', url: SETTINGS, viewport: [1280, 1100], hideAdminMenu: true,
    css: '.ct-media-url{filter:blur(4px)}',
    setup: openTab('giaodien', 'header'),
    clip: { sel: ['.ct-options-wrap h1', 'input#submit'], pad: [10, 16, 14, 16] },
    marks: [
      { sel: 'a.ct-subtab[data-subtab="header"]', n: 1 },
      { sel: row('Kiểu Header'), n: 2 },
      { sel: row('Logo'), n: 3 },
      { sel: [row('Câu khẩu hiệu'), row('Trích dẫn')], n: 4 },
      { sel: [row('Màu nền phía trên'), row('Màu chữ khẩu hiệu')], n: 5 },
      { sel: 'input#submit', n: 6 },
    ],
    guide: 'header-logo-khau-hieu.webp',
  },
  {
    id: 'P06-C14-B04-02-header-ngoai-website', role: 'guest', url: '/', viewport: [1280, 700],
    clip: { sel: ['.header-brand', '#site-nav'], pad: 0 },
    marks: [
      { sel: '.header-brand .header-brand__logo, .header-brand img', n: 1, side: 'inside' },
      { sel: '.header-brand__slogan, .header-brand .slogan', n: 2, side: 'inside' },
      { sel: '#site-nav', n: 3, side: 'inside' },
    ],
    guide: 'header-ngoai-website.webp',
  },
  {
    id: 'P06-C14-B05-01-header-tu-nhap', role: 'admin', url: SETTINGS, viewport: [1280, 1100], hideAdminMenu: true,
    setup: async (page, h) => { await openTab('giaodien', 'header')(page, h); await h.click(`${row('Kiểu Header')} label:has-text("Tự nhập nội dung")`); await h.wait(500); },
    clip: { sel: [row('Kiểu Header'), 'input#submit'], pad: [10, 20, 14, 50] },
    marks: [
      { sel: row('Kiểu Header'), n: 1, side: 'left' },
      { sel: row('Nội dung Header'), n: 2, side: 'left' },
      { sel: row('Màu nền Header'), n: 3, side: 'left' },
      { sel: 'input#submit', n: 4, side: 'right' },
    ],
  },
  {
    id: 'P06-C14-B06-01-header-banner', role: 'admin', url: SETTINGS, viewport: [1280, 1200], hideAdminMenu: true,
    setup: async (page, h) => { await openTab('giaodien', 'header')(page, h); await h.click(`${row('Kiểu Header')} label:has-text("Ảnh banner rộng")`); await h.wait(500); },
    clip: { sel: [row('Kiểu Header'), 'input#submit'], pad: [10, 20, 14, 50] },
    marks: [
      { sel: row('Kiểu Header'), n: 1, side: 'left' },
      { sel: [row('Ảnh cho Máy tính'), row('Ảnh cho Điện thoại')], n: 2, side: 'left' },
      { sel: row('Mô tả ảnh'), n: 3, side: 'left' },
      { sel: [row('Liên kết khi bấm'), row('Mở liên kết ở tab mới')], n: 4, side: 'left' },
      { sel: 'input#submit', n: 5, side: 'right' },
    ],
    guide: 'header-banner.webp',
  },
  {
    id: 'P06-C14-B07-01-footer', role: 'admin', url: SETTINGS, viewport: [1280, 1100], hideAdminMenu: true,
    setup: openTab('giaodien', 'footer'),
    clip: { sel: ['.ct-tab-panel[data-tab="giaodien"] .ct-subtabs', 'input#submit'], pad: [10, 20, 14, 50] },
    marks: [
      { sel: 'a.ct-subtab[data-subtab="footer"]', n: 1, side: 'top' },
      { sel: row('Nội dung cuối trang'), n: 2, side: 'left' },
      { sel: [row('Màu nền cuối trang'), row('Màu chữ cuối trang')], n: 3, side: 'left' },
      { sel: 'input#submit', n: 4, side: 'right' },
    ],
    guide: 'footer.webp',
  },
  {
    id: 'P06-C14-B08-01-cot-widget-footer', role: 'admin', url: SETTINGS, viewport: [1280, 900], hideAdminMenu: true,
    setup: async (page, h) => { await openTab('giaodien', 'footer')(page, h); await switchOn('Hiển thị cột Widget')(page, h); },
    clip: { sel: [row('Hiển thị cột Widget'), row('Số cột Widget')], pad: [12, 20, 12, 50] },
    marks: [
      { sel: row('Hiển thị cột Widget'), n: 1, side: 'left' },
      { sel: row('Số cột Widget'), n: 2, side: 'left' },
    ],
  },
  {
    id: 'P06-C15-B01-01-thanh-ben-trang-chu', role: 'admin', url: SETTINGS, viewport: [1280, 900], hideAdminMenu: true,
    setup: openTab('noidung', 'homepage'),
    clip: { sel: ['.ct-options-wrap .ct-tabs', row('Vị trí thanh bên')], pad: [10, 20, 12, 50] },
    marks: [
      { sel: 'a.ct-tab[data-tab="noidung"]', n: 1, side: 'top' },
      { sel: '.ct-tab-panel[data-tab="noidung"] a.ct-subtab[data-subtab="homepage"]', n: 2, side: 'right' },
      { sel: `.ct-subtab-panel[data-subtab="homepage"] ${row('Hiển thị thanh bên')}`, n: 3, side: 'left' },
      { sel: `.ct-subtab-panel[data-subtab="homepage"] ${row('Vị trí thanh bên')}`, n: 4, side: 'left' },
    ],
  },
  {
    id: 'P06-C15-B02-01-khoi-noi-bat', role: 'admin', url: SETTINGS, viewport: [1280, 1600], hideAdminMenu: true,
    setup: async (page, h) => {
      await openTab('noidung', 'homepage')(page, h);
      await switchOn('Hiển thị khối Nổi bật')(page, h);
      await switchOn('Bật khối "Tin Hot"')(page, h);
    },
    clip: { sel: [row('Hiển thị khối Nổi bật'), row('Tiêu điểm — Số bài')], pad: [12, 20, 12, 50] },
    marks: [
      { sel: row('Hiển thị khối Nổi bật'), n: 1, side: 'left' },
      { sel: row('Kiểu khối Nổi bật'), n: 2, side: 'left' },
      { sel: [row('Bật khối "Tin Hot"'), row('Tin Hot — Trong vòng (ngày)')], n: 3, side: 'left' },
      { sel: [row('Tiêu điểm — Chuyên mục'), row('Tiêu điểm — Số bài')], n: 4, side: 'left' },
    ],
    guide: 'khoi-noi-bat.webp',
  },
  {
    id: 'P06-C15-B03-01-box-5-phut', role: 'admin', url: SETTINGS, viewport: [1280, 1600], hideAdminMenu: true,
    setup: async (page, h) => { await openTab('noidung', 'homepage')(page, h); await switchOn('Hiển thị box "5 phút"')(page, h); },
    clip: { sel: [row('Hiển thị box "5 phút"'), row('Hiện ở trang Chuyên mục')], pad: [12, 20, 12, 50] },
    marks: [
      { sel: row('Hiển thị box "5 phút"'), n: 1, side: 'left' },
      { sel: row('Tiêu đề box'), n: 2, side: 'left' },
      { sel: row('Chuyên mục nguồn'), n: 3, side: 'left' },
      { sel: [row('Hiện ở Trang chủ'), row('Hiện ở trang Chuyên mục')], n: 4, side: 'left' },
    ],
    guide: 'box-5-phut.webp',
  },
  {
    id: 'P06-C15-B04-01-danh-sach-khoi', role: 'admin', url: SETTINGS, viewport: [1280, 1600], hideAdminMenu: true,
    setup: async (page, h) => { await openTab('noidung', 'homepage')(page, h); await onlyBlocks([])(page); await h.scrollTo('#ct-home-sec', 120); },
    clip: { sel: ['#ct-home-sec'], pad: [70, 20, 12, 50] },
    marks: [
      { sel: '.ct-sec-row[data-index="0"] .ct-sec-drag', n: 1, side: 'left' },
      { sel: '.ct-sec-row[data-index="0"] .ct-sec-toggle', n: 2, side: 'top' },
      { sel: '.ct-sec-row[data-index="0"] .ct-sec-picker', n: 3, side: 'top' },
      { sel: '.ct-sec-row[data-index="0"] .ct-sec-remove', n: 4, side: 'right' },
      { sel: '.ct-sec-add', n: 5, side: 'right' },
    ],
    guide: 'trang-chu-cac-khoi.webp',
  },
  {
    id: 'P06-C15-B05-01-chin-mau-khoi', role: 'guest', file: 'gallery/mau-khoi.html', viewport: [1000, 900],
    clip: { sel: '#gallery', pad: 0 },
    guide: 'mau-khoi.webp',
  },
  {
    id: 'P06-C15-B06-01-chi-tiet-khoi', role: 'admin', url: SETTINGS, viewport: [1280, 2000], hideAdminMenu: true,
    setup: async (page, h) => { await openTab('noidung', 'homepage')(page, h); await onlyBlocks([2])(page); await h.scrollTo('.ct-sec-row[data-index="2"]', 20); },
    clip: { sel: ['.ct-sec-row[data-index="2"] .ct-sec-head', secGroup(2, 'Hiển thị trên thẻ bài')], pad: [12, 20, 12, 50] },
    marks: [
      { sel: '.ct-sec-row[data-index="2"] .ct-sec-picker', n: 1, side: 'left' },
      { sel: secGroup(2, 'Thông tin khối'), n: 2, side: 'left' },
      { sel: secGroup(2, 'Nguồn bài viết'), n: 3, side: 'left' },
      { sel: secGroup(2, 'Hiển thị trên thẻ bài'), n: 4, side: 'left' },
    ],
    guide: 'khoi-chi-tiet.webp',
  },
  {
    id: 'P06-C15-B07-01-khoi-loi-chua-hom-nay', role: 'admin', url: SETTINGS, viewport: [1280, 1800], hideAdminMenu: true,
    setup: async (page, h) => { await openTab('noidung', 'homepage')(page, h); await onlyBlocks([0])(page); await h.scrollTo('.ct-sec-row[data-index="0"]', 20); },
    clip: { sel: ['.ct-sec-row[data-index="0"] .ct-sec-head', secGroup(0, 'Nguồn bài viết')], pad: [12, 20, 12, 50] },
    marks: [
      { sel: '.ct-sec-row[data-index="0"] .ct-sec-picker', n: 1, side: 'left' },
      { sel: secField(0, 'Tiêu đề khối'), n: 2, side: 'left' },
      { sel: secGroup(0, 'Nguồn bài viết'), n: 3, side: 'left' },
    ],
    guide: 'khoi-loi-chua-hom-nay.webp',
  },
  {
    id: 'P06-C15-B07-02-loi-chua-hom-nay-ngoai-website', role: 'guest', url: '/', viewport: [1280, 960],
    setup: async (page, h) => { await h.waitFor('.ct__daily'); await h.wait(600); },
    clip: { sel: ['.ct__daily', '#ct_lc_calendar-2'], pad: 10 },
    marks: [
      { sel: '.ct__daily', n: 1, side: 'inside' },
      { sel: '#ct_lc_calendar-2', n: 2, side: 'inside' },
    ],
    guide: 'loi-chua-hom-nay-ngoai-website.webp',
  },
  {
    id: 'P06-C15-B08-01-mosaic-ngoai-website', role: 'guest', url: '/', viewport: [1280, 1200],
    setup: async (page, h) => { await h.waitFor('.ct__post-mosaic'); await h.wait(600); },
    clip: { sel: '.ct__post-mosaic', pad: 10 },
  },
  {
    id: 'P06-C15-B10-01-mau-va-xem-them', role: 'admin', url: SETTINGS, viewport: [1280, 2200], hideAdminMenu: true,
    setup: async (page, h) => {
      await openTab('noidung', 'homepage')(page, h);
      await onlyBlocks([2])(page);
      await h.select(`${secField(2, 'Dùng màu mặc định')} select`, 'n');
      await h.select(`${secField(2, 'Hiện nút')} select`, 'y');
      await h.scrollTo(secGroup(2, 'Màu sắc khối'), 20);
    },
    clip: { sel: [secGroup(2, 'Màu sắc khối'), secGroup(2, 'Nút "Xem thêm"')], pad: [12, 20, 12, 50] },
    marks: [
      { sel: secField(2, 'Dùng màu mặc định'), n: 1, side: 'left' },
      { sel: '.ct-sec-row[data-index="2"] .ct-tpl-group:not(.is-hidden) .ct-sec-colorbody', n: 2, side: 'left' },
      { sel: secField(2, 'Hiện nút'), n: 3, side: 'left' },
      { sel: [secField(2, 'Chữ trên nút'), secField(2, 'Mở tab mới')], n: 4, side: 'left' },
    ],
    guide: 'khoi-mau-xem-them.webp',
  },
  {
    id: 'P06-C15-B12-01-bo-cuc-chuyen-muc', role: 'admin', url: SETTINGS, viewport: [1280, 1200], hideAdminMenu: true,
    setup: openTab('noidung', 'default'),
    clip: { sel: ['.ct-subtab-panel[data-subtab="default"] h2 >> nth=0', `.ct-subtab-panel[data-subtab="default"] table >> nth=0`], pad: [10, 20, 12, 50] },
    marks: [
      { sel: `.ct-subtab-panel[data-subtab="default"] ${row('Số cột danh sách')}`, n: 1, side: 'left' },
      { sel: `.ct-subtab-panel[data-subtab="default"] ${row('Kiểu trình bày')}`, n: 2, side: 'left' },
      { sel: `.ct-subtab-panel[data-subtab="default"] table >> nth=0 >> ${row('Hiển thị thanh bên')}`, n: 3, side: 'left' },
    ],
  },
  {
    id: 'P06-C15-B13-01-bo-cuc-bai-viet', role: 'admin', url: SETTINGS, viewport: [1280, 1600], hideAdminMenu: true,
    setup: openTab('noidung', 'default'),
    clip: { sel: ['.ct-subtab-panel[data-subtab="default"] h2 >> nth=1', `.ct-subtab-panel[data-subtab="default"] table >> nth=1`], pad: [10, 20, 12, 50] },
    marks: [
      { sel: `.ct-subtab-panel[data-subtab="default"] table >> nth=1 >> ${row('Hiển thị thanh bên')}`, n: 1, side: 'left' },
    ],
  },
  {
    id: 'P06-C16-B01-01-thanh-thong-bao', role: 'admin', url: SETTINGS, viewport: [1280, 1200], hideAdminMenu: true,
    setup: async (page, h) => { await openTab('tienich')(page, h); await switchOn('Bật thanh thông báo')(page, h); },
    clip: { sel: ['.ct-options-wrap .ct-tabs', 'input#submit'], pad: [10, 20, 14, 50] },
    marks: [
      { sel: 'a.ct-tab[data-tab="tienich"]', n: 1, side: 'top' },
      { sel: row('Bật thanh thông báo'), n: 2, side: 'left' },
      { sel: [row('Bắt đầu hiển thị'), row('Kết thúc hiển thị')], n: 3, side: 'left' },
      { sel: row('Tiêu đề'), n: 4, side: 'left' },
      { sel: row('Nội dung'), n: 5, side: 'left' },
      { sel: row('Đang phát trực tiếp'), n: 6, side: 'left' },
      { sel: 'input#submit', n: 7, side: 'right' },
    ],
    guide: 'thanh-thong-bao.webp',
  },
  {
    id: 'P06-C16-B04-01-nang-cao', role: 'admin', url: SETTINGS, viewport: [1280, 1300], hideAdminMenu: true,
    setup: openTab('nangcao'),
    clip: { sel: ['.ct-options-wrap .ct-tabs', 'input#submit'], pad: [10, 20, 14, 50] },
    marks: [
      { sel: row('Mã Google Analytics'), n: 1, side: 'left' },
      { sel: [row('Mã chèn vào <head>'), row('Mã chèn cuối trang')], n: 2, side: 'left' },
      { sel: [row('Shortcode cho Quản trị viên'), row('Shortcode cho vai trò khác')], n: 3, side: 'left' },
    ],
  },

  // =================================================================== PHẦN 7
  {
    id: 'P07-C17-B01-01-vi-tri-menu', role: 'admin', url: '/wp-admin/nav-menus.php?action=locations', viewport: [1280, 500], hideAdminMenu: true,
    clip: { sel: ['h1', '#nav-menu-locations'], pad: [10, 20, 14, 16] },
    marks: [
      { sel: '.nav-tab-wrapper a:nth-child(2)', n: 1, side: 'right' },
      { sel: '#locations-primary', n: 2, side: 'right' },
    ],
  },
  {
    id: 'P07-C17-B02-01-cau-truc-menu', role: 'admin', url: `/wp-admin/nav-menus.php?menu=${MENU_MAIN}`, viewport: [1280, 1500], hideAdminMenu: true,
    clip: { sel: ['.manage-menus', '#menu-to-edit > li:nth-child(5)'], pad: [10, 40, 10, 16] },
    marks: [
      { sel: '.manage-menus', n: 1, side: 'right' },
      { sel: '#menu-settings-column', n: 2, side: 'right' },
      { sel: '#menu-name', n: 3, side: 'right' },
      { sel: ['#menu-to-edit > li:nth-child(1)', '#menu-to-edit > li:nth-child(5)'], n: 4, side: 'right' },
    ],
    guide: 'menu-cau-truc.webp',
  },
  {
    id: 'P07-C17-B03-01-them-muc-menu', role: 'admin', url: `/wp-admin/nav-menus.php?menu=${MENU_MAIN}`, viewport: [1280, 1100], hideAdminMenu: true,
    setup: async (page, h) => { await h.click('#add-category .accordion-section-title button, li#add-category > h3 button'); await h.wait(500); },
    clip: { sel: '#menu-settings-column', pad: [10, 50, 10, 16] },
    marks: [
      { sel: '#add-category h3', n: 1, side: 'right' },
      { sel: '#categorychecklist-most-used, #tabs-panel-category-all, #add-category .tabs-panel-active', n: 2, side: 'right' },
      { sel: '#submit-taxonomy-category', n: 3, side: 'right' },
    ],
    guide: 'menu-them-muc.webp',
  },
  {
    id: 'P07-C17-B04-01-sua-muc-menu', role: 'admin', url: `/wp-admin/nav-menus.php?menu=${MENU_MAIN}`, viewport: [1280, 1300], hideAdminMenu: true,
    setup: async (page, h) => { await h.click('#menu-to-edit li.menu-item:has(.menu-item-title:text-is("Suy niệm")) .item-edit'); await h.wait(500); },
    clip: { sel: '#menu-to-edit li.menu-item:has(.menu-item-title:text-is("Suy niệm"))', pad: [16, 60, 16, 16] },
    marks: [
      { sel: '#menu-to-edit li.menu-item:has(.menu-item-title:text-is("Suy niệm")) .item-edit', n: 1, side: 'right' },
      { sel: '#menu-to-edit li.menu-item:has(.menu-item-title:text-is("Suy niệm")) p:has(.edit-menu-item-title)', n: 2, side: 'right' },
      { sel: '#menu-to-edit li.menu-item:has(.menu-item-title:text-is("Suy niệm")) .item-delete', n: 3, side: 'bottom' },
    ],
  },
  {
    id: 'P07-C17-B06-01-menu-con', role: 'admin', url: `/wp-admin/nav-menus.php?menu=${MENU_MAIN}`, viewport: [1280, 1500], hideAdminMenu: true,
    clip: { sel: ['#menu-to-edit li.menu-item:has(.menu-item-title:text-is("Giáo lý"))', '#menu-to-edit li.menu-item-depth-1 >> nth=2'], pad: [12, 60, 12, 16] },
    marks: [
      { sel: '#menu-to-edit li.menu-item:has(.menu-item-title:text-is("Giáo lý")) > .menu-item-bar', n: 1, side: 'right' },
      { sel: ['#menu-to-edit li.menu-item-depth-1 >> nth=0', '#menu-to-edit li.menu-item-depth-1 >> nth=2'], n: 2, side: 'right' },
    ],
  },
  {
    id: 'P07-C17-B07-01-lien-ket-tu-tao', role: 'admin', url: `/wp-admin/nav-menus.php?menu=${MENU_MAIN}`, viewport: [1280, 1100], hideAdminMenu: true,
    setup: async (page, h) => { await h.click('#add-custom-links h3 button, li#add-custom-links > h3 button'); await h.wait(500); },
    clip: { sel: '#menu-settings-column', pad: [10, 50, 10, 16] },
    marks: [
      { sel: '#menu-item-url-wrap', n: 1, side: 'right' },
      { sel: '#menu-item-name-wrap', n: 2, side: 'right' },
      { sel: '#submit-customlinkdiv', n: 3, side: 'right' },
    ],
  },
  {
    id: 'P07-C17-B09-01-thiet-lap-menu', role: 'admin', url: `/wp-admin/nav-menus.php?menu=${MENU_MAIN}`, viewport: [1280, 1500], hideAdminMenu: true,
    setup: async (page, h) => { await h.scrollTo('.menu-settings', 60); },
    clip: { sel: ['.menu-settings-group.auto-add-pages', '#save_menu_footer'], pad: [16, 60, 16, 16] },
    marks: [
      { sel: '.menu-settings-group.auto-add-pages', n: 1, side: 'right' },
      { sel: '.menu-settings-group.menu-theme-locations', n: 2, side: 'right' },
      { sel: '#save_menu_footer', n: 3, side: 'bottom' },
    ],
  },
  {
    id: 'P07-C17-B10-01-menu-dien-thoai', role: 'guest', url: '/', viewport: [390, 844], mobile: true,
    setup: async (page, h) => {
      await h.click('#site-nav .navbar-toggler');
      await h.wait(700);
      await page.locator('#site-nav .ct-submenu-toggle, #site-nav .submenu-toggle, #site-nav button[aria-expanded]').nth(1).click().catch(() => {});
      await h.wait(500);
    },
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '#site-nav .navbar-toggler', n: 1, side: 'left' },
      { sel: '#site-nav .menu-item-has-children >> nth=0', n: 2, side: 'inside' },
    ],
    guide: 'menu-dien-thoai.webp',
  },
  {
    id: 'P07-C17-B10-02-menu-may-tinh', role: 'guest', url: '/', viewport: [1280, 700],
    css: '#site-nav li.menu-item-has-children:has(> a[href$="/category/huan-quyen/"]):not(.sub-menu li) > ul.sub-menu{display:block!important}',
    setup: async (page, h) => { await page.locator('#site-nav li.menu-item-has-children:has(> a:text-is("Huấn quyền")) > a').first().focus(); await h.wait(500); },
    clip: { sel: ['.header-brand', '#site-nav li.menu-item-has-children:has(> a:text-is("Huấn quyền")) > ul.sub-menu'], pad: [0, 0, 16, 0] },
    marks: [
      { sel: '#site-nav li.menu-item-has-children:has(> a:text-is("Huấn quyền")) > a', n: 1, side: 'left' },
      { sel: '#site-nav li.menu-item-has-children:has(> a:text-is("Huấn quyền")) > ul.sub-menu', n: 2, side: 'left' },
    ],
  },
  {
    id: 'P07-C18-B01-01-khu-vuc-widget', role: 'admin', url: '/wp-admin/widgets.php', viewport: [1280, 1000],
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '#menu-appearance .wp-submenu a[href="widgets.php"]', n: 1, side: 'right' },
      { sel: '#widgets-left', n: 2, side: 'inside' },
      { sel: '#widgets-right', n: 3, side: 'inside' },
    ],
    guide: 'widget-man-hinh.webp',
  },
  {
    id: 'P07-C18-B04-01-widget-danh-sach-bai-viet', role: 'admin', url: '/wp-admin/widgets.php', viewport: [1280, 1600], hideAdminMenu: true,
    setup: async (page, h) => { await h.click('#ct-widget-homepage .widget[id*="ct_postlist_widget"] .widget-top'); await h.wait(500); },
    clip: { sel: '#ct-widget-homepage .widget[id*="ct_postlist_widget"]', pad: [12, 50, 12, 50] },
    marks: [
      { sel: '#ct-widget-homepage .widget[id*="ct_postlist_widget"] p:has(label:text-matches("^Tiêu đề"))', n: 1, side: 'left' },
      { sel: '#ct-widget-homepage .widget[id*="ct_postlist_widget"] p:has(label:text-matches("^Kiểu hiển thị"))', n: 2, side: 'left' },
      { sel: ['#ct-widget-homepage .widget[id*="ct_postlist_widget"] p:has(label:text-matches("^Chuyên mục"))', '#ct-widget-homepage .widget[id*="ct_postlist_widget"] p:has(label:text-matches("^Sắp xếp"))'], n: 3, side: 'left' },
      { sel: '#ct-widget-homepage .widget[id*="ct_postlist_widget"] input[type=submit]', n: 4, side: 'right' },
    ],
    guide: 'widget-danh-sach-bai-viet.webp',
  },
  {
    id: 'P07-C18-B06-01-widget-cau-ghi-nho', role: 'admin', url: '/wp-admin/widgets.php', viewport: [1280, 2400], hideAdminMenu: true,
    setup: async (page, h) => {
      await page.evaluate(() => { const w = document.querySelector('#ct-widget-archive').closest('.widgets-holder-wrap'); w.classList.remove('closed'); });
      await h.wait(300);
      await h.click('#ct-widget-archive .widget[id*="loichua"] .widget-top');
      await h.wait(500);
      await h.scrollTo('#ct-widget-archive .widget[id*="loichua"]', 20);
    },
    clip: { sel: '#ct-widget-archive .widget[id*="loichua"]', pad: [12, 50, 12, 50] },
    marks: [
      { sel: '#ct-widget-archive .widget[id*="loichua"] p:has(label:text-matches("^Chế độ"))', n: 1, side: 'left' },
      { sel: ['#ct-widget-archive .widget[id*="loichua"] p:has(label:text-matches("^Nhãn"))', '#ct-widget-archive .widget[id*="loichua"] p:has(label:text-matches("^Trích dẫn"))'], n: 2, side: 'left' },
      { sel: '#ct-widget-archive .widget[id*="loichua"] p:has(label:text-matches("^Dynamic"))', n: 3, side: 'left' },
      { sel: '#ct-widget-archive .widget[id*="loichua"] input[type=submit]', n: 4, side: 'right' },
    ],
    guide: 'widget-cau-ghi-nho.webp',
  },
  {
    id: 'P07-C18-B07-01-widget-lich', role: 'admin', url: '/wp-admin/widgets.php', viewport: [1280, 1200], hideAdminMenu: true,
    setup: async (page, h) => { await h.click('#ct-widget-homepage .widget[id*="ct_lc_calendar"] .widget-top'); await h.wait(500); },
    clip: { sel: '#ct-widget-homepage .widget[id*="ct_lc_calendar"]', pad: [12, 50, 12, 50] },
    marks: [
      { sel: '#ct-widget-homepage .widget[id*="ct_lc_calendar"] p:has(label:text-matches("^Tiêu đề"))', n: 1, side: 'left' },
      { sel: '#ct-widget-homepage .widget[id*="ct_lc_calendar"] p:has(label:text-matches("^Chuyên mục"))', n: 2, side: 'left' },
      { sel: '#ct-widget-homepage .widget[id*="ct_lc_calendar"] input[type=submit]', n: 3, side: 'right' },
    ],
    guide: 'widget-lich.webp',
  },
  {
    id: 'P07-C18-B08-01-thanh-ben-ngoai-website', role: 'guest', url: '/', viewport: [1280, 1500],
    setup: async (page, h) => { await h.waitFor('#ct-sidebar .ct-lc-cal'); await h.wait(500); },
    clip: { sel: '#ct-sidebar', pad: 10, maxH: 1300 },
    marks: [
      { sel: '#ct_lc_calendar-2', n: 1, side: 'left' },
      { sel: '#ct-sidebar .widget_nav_menu, #ct-sidebar .widget-item:nth-child(2)', n: 2, side: 'left' },
      { sel: '#ct_postlist_widget-2', n: 3, side: 'left' },
    ],
  },

  // =================================================================== PHẦN 8
  {
    id: 'P08-C19-B03-01-bai-loi-chua', role: 'editor', url: `/wp-admin/post.php?post=${POST_LC}&action=edit`, viewport: [1280, 1000],
    setup: gutenberg(async (page, h) => { await h.scrollIntoView('#ct_post_kind_box', 'center'); }),
    clip: { sel: ['#ct_post_kind_box'], pad: [8, 6, 8, 40], maxH: 980 },
    marks: [
      { sel: '#ct_post_kind_box select', n: 1, side: 'left' },
      { sel: '#ct_post_kind_box textarea', n: 2, side: 'left' },
      { sel: '#ct_post_kind_box p:has(label:text-is("Trích dẫn")) input, #ct_post_kind_box input[name*="citation"]', n: 3, side: 'left' },
    ],
    guide: 'phan-loai-loi-chua.webp',
  },
  {
    id: 'P08-C19-B05-01-ngay-phung-vu', role: 'editor', url: `/wp-admin/post.php?post=${POST_LC}&action=edit`, viewport: [1280, 1000],
    setup: gutenberg(async (page, h) => { await h.scrollIntoView('#ct_post_kind_box', 'center'); }),
    clip: { sel: ['#ct_post_kind_box'], pad: [8, 6, 8, 40], maxH: 980 },
    marks: [
      { sel: '#ct_post_kind_box input[name*="day_title"]', n: 1, side: 'left' },
      { sel: '#ct_post_kind_box input[name*="saint"]', n: 2, side: 'left' },
      { sel: '#ct_post_kind_box input[name*="gospel"]', n: 3, side: 'left' },
    ],
  },
  {
    id: 'P08-C19-B06-01-hen-gio-loi-chua', role: 'editor', url: `/wp-admin/post.php?post=${POST_FUTURE}&action=edit`, viewport: [1280, 820],
    setup: gutenberg(),
    clip: { sel: ['.editor-header__settings', '.editor-post-panel__row:has(.editor-post-panel__row-label:text-is("Xuất bản"))'], pad: [6, 6, 16, 40] },
    marks: [
      { sel: '.editor-post-panel__row:has(.editor-post-panel__row-label:text-is("Trạng thái"))', n: 1, side: 'left' },
      { sel: '.editor-post-panel__row:has(.editor-post-panel__row-label:text-is("Xuất bản"))', n: 2, side: 'left' },
      { sel: '.editor-post-publish-button', n: 3, side: 'bottom' },
    ],
    guide: 'hen-gio-loi-chua.webp',
  },
  {
    id: 'P08-C19-B07-01-bai-video', role: 'editor', url: `/wp-admin/post.php?post=${POST_TV}&action=edit`, viewport: [1280, 1000],
    setup: gutenberg(async (page, h) => {
      await h.scrollIntoView('#ct_post_kind_box', 'center');
      await h.select('#ct_post_kind_box select', { label: 'Video (audio) Lời Chúa' });
      await h.wait(400);
    }),
    clip: { sel: ['#ct_post_kind_box'], pad: [8, 6, 8, 40], maxH: 700 },
    marks: [
      { sel: '#ct_post_kind_box select', n: 1, side: 'left' },
      { sel: '#ct_post_kind_box input[type=url]:visible, #ct_post_kind_box input[name*="video"]', n: 2, side: 'left' },
    ],
    guide: 'phan-loai-video.webp',
  },
  {
    id: 'P08-C19-B03-02-loi-chua-ngoai-website', role: 'guest', url: `/?p=${POST_LC}`, viewport: [1280, 900],
    clip: { sel: ['.post-header .ct-loichua-card'], pad: [16, 30, 16, 30] },
    marks: [{ sel: '.post-header .ct-loichua-card', n: 1, side: 'left' }],
  },
  {
    id: 'P08-C20-B01-01-tim-khoi-loi-chua', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1280, 760],
    setup: gutenberg(async (page, h) => {
      await h.click('.editor-document-tools__inserter-toggle');
      await h.wait(400);
      await h.type('.block-editor-inserter__search input', 'Lời Chúa');
      await h.wait(700);
    }),
    clip: { x: 0, y: 0, w: 760, h: 520 },
    marks: [
      { sel: '.editor-document-tools__inserter-toggle', n: 1, side: 'right' },
      { sel: '.block-editor-inserter__search', n: 2, side: 'right' },
      { sel: '.block-editor-block-types-list__item:has-text("Câu ghi nhớ")', n: 3, side: 'right' },
    ],
  },
  {
    id: 'P08-C20-B02-01-cai-dat-khoi-loi-chua', role: 'editor', url: '/wp-admin/post-new.php', viewport: [1024, 860],
    setup: gutenberg(async (page, h) => {
      await h.click('.editor-document-tools__inserter-toggle');
      await h.wait(400);
      await h.type('.block-editor-inserter__search input', 'Câu ghi nhớ');
      await h.wait(700);
      await page.locator('.block-editor-block-types-list__item:has-text("Câu ghi nhớ")').first().click();
      await h.wait(900);
    }),
    clip: 'viewport', growClip: false,
    marks: [
      { sel: 'canvas:.wp-block-chinhtoa-loichua-card', n: 1, side: 'inside' },
      { sel: `.interface-complementary-area ${panel("Nguồn nội dung")}`, n: 2, side: 'left' },
      { sel: `.interface-complementary-area ${panel("Nội dung thẻ")}`, n: 3, side: 'left' },
    ],
    guide: 'khoi-cau-ghi-nho.webp',
  },
  {
    id: 'P08-C20-B03-01-tuy-chinh-bai-viet', role: 'admin', url: `/wp-admin/post.php?post=${POST_TV}&action=edit`, viewport: [1280, 1100],
    setup: gutenberg(async (page, h) => {
      await h.scrollIntoView('#ct_post_options', 'start');
      await h.click(`#ct_post_options ${mbRow('Tuỳ chỉnh riêng bài này').replace('.ct-meta-box ', '')} .ct-switch`);
      await h.wait(400);
      await h.scrollIntoView('#ct_post_options', 'start');
    }),
    clip: { sel: '#ct_post_options', pad: [8, 6, 8, 40], maxH: 1080 },
    marks: [
      { sel: '#ct_post_options .ct-field-row:has(.ct-mb-label:text-is("Tuỳ chỉnh riêng bài này"))', n: 1, side: 'left' },
      { sel: '#ct_post_options .ct-field-row:has(.ct-mb-label:text-is("Thanh bên (sidebar)"))', n: 2, side: 'left' },
      { sel: ['#ct_post_options .ct-field-row:has(.ct-mb-label:text-is("Hình đại diện"))', '#ct_post_options .ct-field-row:has(.ct-mb-label:text-is("Ngày & lượt xem"))'], n: 3, side: 'left' },
    ],
  },
  {
    id: 'P08-C20-B06-01-luot-xem', role: 'admin', url: '/wp-admin/edit.php?post_type=page', viewport: [1280, 500], css: YOAST_COLS, hideAdminMenu: true,
    clip: { sel: ['table.wp-list-table thead', '#the-list'], pad: [10, 16, 10, 16] },
    marks: [{ sel: 'th#views', n: 1, side: 'top' }],
  },

  // =================================================================== PHẦN 9
  {
    id: 'P09-C21-B01-01-ho-so', role: 'editor', url: '/wp-admin/profile.php', viewport: [1280, 1500],
    css: '#nickname, #user_login{filter:blur(5px)}',
    setup: async (page, h) => { await h.scrollTo('h2:text-is("Tên")', 10); },
    clip: { sel: ['h2:text-is("Tên")', 'tr:has(#display_name)'], pad: [10, 20, 12, 16] },
    marks: [
      { sel: ['tr:has(#first_name)', 'tr:has(#last_name)'], n: 1, side: 'right' },
      { sel: 'tr:has(#nickname)', n: 2, side: 'right' },
      { sel: 'tr:has(#display_name)', n: 3, side: 'right' },
    ],
  },
  {
    id: 'P09-C21-B03-01-doi-mat-khau', role: 'editor', url: '/wp-admin/profile.php', viewport: [1280, 2600],
    css: '#pass1, #pass1-text, .password-input-wrapper input{filter:blur(4px)}',
    setup: async (page, h) => {
      await h.click('button.wp-generate-pw');
      await h.wait(500);
      await h.scrollTo('h2:text-is("Quản lý tài khoản")', 10);
    },
    clip: { sel: ['h2:text-is("Quản lý tài khoản")', 'tr.user-pass1-wrap, tr#password'], pad: [10, 20, 12, 16] },
    marks: [
      { sel: 'button.wp-generate-pw', n: 1, side: 'right' },
      { sel: '.wp-pwd', n: 2, side: 'right' },
    ],
  },
  {
    id: 'P09-C21-B04-01-quen-mat-khau', role: 'guest', url: '/wp-login.php?action=lostpassword', viewport: [1100, 700],
    clip: { sel: '#login', pad: [10, 60, 10, 60] },
    marks: [
      { sel: ['label[for=user_login]', '#user_login'], n: 1, side: 'left' },
      { sel: '#wp-submit', n: 2, side: 'right' },
    ],
  },
  {
    id: 'P09-C22-B01-01-danh-sach-nguoi-dung', role: 'admin', url: '/wp-admin/users.php', viewport: [1280, 560],
    css: '.column-username strong a, .column-username .row-title, td.column-name, td.column-email a, .column-username img{filter:blur(5px)}',
    clip: 'viewport', growClip: false,
    marks: [
      { sel: '#menu-users .wp-submenu a[href="users.php"]', n: 1, side: 'right' },
      { sel: 'th#role', n: 2, side: 'top' },
      { sel: ['#new_role', '#changeit'], n: 3, side: 'bottom' },
    ],
  },
  {
    id: 'P09-C22-B02-01-them-nguoi-dung', role: 'admin', url: '/wp-admin/user-new.php', viewport: [1280, 1000], hideAdminMenu: true,
    css: '#pass1, #pass1-text{filter:blur(4px)}',
    clip: { sel: ['h1#add-new-user', '#createusersub'], pad: [10, 20, 14, 50] },
    marks: [
      { sel: 'tr:has(#user_login)', n: 1, side: 'left' },
      { sel: 'tr:has(#email)', n: 2, side: 'left' },
      { sel: 'tr:has(#pass1)', n: 3, side: 'left' },
      { sel: 'tr:has(#send_user_notification)', n: 4, side: 'left' },
      { sel: 'tr:has(#role)', n: 5, side: 'left' },
      { sel: '#createusersub', n: 6, side: 'right' },
    ],
  },

  // ================================================================= PHỤ LỤC
  {
    id: 'PHU-LUC-B-01-yoast', role: 'admin', url: `/wp-admin/post.php?post=${POST_TV}&action=edit`, viewport: [1280, 900],
    setup: gutenberg(async (page, h) => {
      await openPanel(page, h, 'Yoast SEO');
      await h.scrollIntoView(`${panel("Yoast SEO")}`, 'center');
    }),
    clip: { sel: `${panel("Yoast SEO")}`, pad: [10, 10, 10, 40] },
    marks: [{ sel: `${panel("Yoast SEO")}`, n: 1, side: 'left' }],
  },
  {
    id: 'PHU-LUC-C-01-nut-elementor', role: 'editor', url: `/wp-admin/post.php?post=${PAGE_CONTACT}&action=edit`, viewport: [1280, 400],
    setup: gutenberg(),
    clip: { sel: ['.editor-document-tools', '#elementor-switch-mode-button'], pad: [12, 12, 12, 12] },
    marks: [{ sel: '#elementor-switch-mode-button', n: 1, side: 'right' }],
  },
  {
    id: 'PHU-LUC-E-01-plugin', role: 'admin', url: '/wp-admin/plugins.php', viewport: [1280, 760],
    clip: 'viewport', growClip: false,
    marks: [{ sel: '#menu-plugins', n: 1, side: 'right' }],
  },
  {
    id: 'PHU-LUC-E-02-cau-truc-duong-dan', role: 'admin', url: '/wp-admin/options-permalink.php', viewport: [1280, 1000], hideAdminMenu: true,
    clip: { sel: ['h1', '.permalink-structure, table.form-table >> nth=0'], pad: [10, 20, 12, 16], maxH: 760 },
    marks: [{ sel: 'table.form-table >> nth=0', n: 1, side: 'right' }],
  },
];
