// Bộ máy chụp ảnh minh hoạ: mở trang bằng Chrome hệ thống (Playwright), chặn mọi thao
// tác ghi dữ liệu, vẽ khung đỏ + số thứ tự NGOÀI khung theo vị trí thật của phần tử,
// rồi chụp PNG độ nét 2x.
import { chromium } from 'playwright-core';
import fs from 'node:fs';
import path from 'node:path';
import { pathToFileURL } from 'node:url';

export const SITE = process.env.CT_SITE || 'http://5plc.local';
export const AUTH_DIR = process.env.CT_AUTH_DIR || path.resolve('.auth');

// Nhãn hiển thị thay cho tên tài khoản thật trên thanh công cụ.
const ROLE_LABEL = { admin: 'Quản lý', editor: 'Biên tập viên' };

// CSS dùng chung khi chụp: tắt hiệu ứng, ẩn thông báo plugin không liên quan.
const BASE_CSS = `
  *, *::before, *::after { transition: none !important; animation: none !important; caret-color: transparent !important; }
  html { scroll-behavior: auto !important; }
  .update-nag, .notice:not(.ct-keep-notice), .error:not(.ct-keep-notice), .e-notice, .elementor-message,
  #yoast-indexation-warning, .yoast-notification, .wpseo-review-notice, #wp-admin-bar-updates,
  #wp-admin-bar-comments, #wp-admin-bar-wpseo-menu, #wp-admin-bar-elementor_inspector,
  .components-notice-list .is-dismissible:not(.ct-keep-notice), .ct-shot-hide { display: none !important; }
  /* Địa chỉ website mẫu trong cột phải của trình soạn bài: không cần cho người đọc. */
  .editor-post-url__front-page-link, .editor-post-url__panel-toggle { filter: blur(4px); }
`;
const HIDE_ADMIN_BAR_CSS = `
  #wpadminbar { display: none !important; }
  html.wp-toolbar { padding-top: 0 !important; }
  html { margin-top: 0 !important; }
  body.admin-bar .interface-interface-skeleton { top: 0 !important; }
  #adminmenuwrap { top: 0 !important; }
`;

// Request ghi dữ liệu bị chặn tuyệt đối (an toàn: script không bao giờ lưu/xoá gì).
function isWriteRequest(req) {
  const m = req.method();
  if (m === 'GET' || m === 'HEAD' || m === 'OPTIONS') return false;
  const u = new URL(req.url());
  if (u.pathname.endsWith('/admin-ajax.php')) {
    const body = req.postData() || '';
    // Cho phép các lệnh chỉ đọc mà giao diện cần (tải khối trang chủ, lịch, heartbeat).
    if (/action=(homepage_tabs_template_call|ct_daily_word|ct_daily_calendar|heartbeat|query-attachments|get-attachment|wp-compression-test|closed-postboxes|meta-box-order|dashboard-widgets)\b/.test(body)) return false;
    return true;
  }
  if (u.pathname.includes('/wp-json/')) {
    // REST: Gutenberg đọc dữ liệu bằng GET; ghi (lưu nháp tự động, cập nhật…) đều bị chặn.
    // Một số GET được gửi dạng POST kèm _method=GET / X-HTTP-Method-Override.
    const ov = (req.headers()['x-http-method-override'] || '').toUpperCase();
    if (ov === 'GET' || /[?&]_method=GET/i.test(u.search)) return false;
    // Batch API với toàn yêu cầu GET? Không cho, an toàn hơn.
    return true;
  }
  return true; // options.php, post.php, nav-menus.php, widgets.php, edit-tags.php…
}

export async function openBrowser() {
  return chromium.launch({ channel: 'chrome', headless: process.env.CT_HEADED ? false : true });
}

export async function newContext(browser, role, viewport, opts = {}) {
  const statePath = path.join(AUTH_DIR, `${role}.json`);
  const ctxOpts = {
    viewport: { width: viewport[0], height: viewport[1] },
    deviceScaleFactor: 2,
    locale: 'vi-VN',
    timezoneId: 'Asia/Ho_Chi_Minh',
    colorScheme: 'light',
    reducedMotion: 'reduce',
  };
  if (role !== 'guest') {
    if (!fs.existsSync(statePath)) throw new Error(`Chưa có phiên đăng nhập ${role}: chạy "node login.mjs ${role}"`);
    ctxOpts.storageState = statePath;
  }
  if (opts.mobile) {
    ctxOpts.isMobile = true;
    ctxOpts.hasTouch = true;
    ctxOpts.userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Mobile/15E148 Safari/604.1';
  }
  const ctx = await browser.newContext(ctxOpts);
  const blocked = [];
  await ctx.route('**/*', (route) => {
    const req = route.request();
    if (isWriteRequest(req)) {
      blocked.push(`${req.method()} ${req.url()}`);
      return route.abort('blockedbyclient');
    }
    return route.continue();
  });
  ctx.__blocked = blocked;
  return ctx;
}

// Các thao tác "setup" an toàn. Không cho bấm nút có chữ lưu/đăng/xoá.
const DANGER = /^(Lưu|Cập nhật|Xuất bản|Đăng|Đăng bài|Lên lịch|Xoá|Xóa|Xóa vĩnh viễn|Xoá khối|Chuyển vào thùng rác|Bỏ vào thùng rác|Save|Publish|Update|Delete|Trash)/i;

// "canvas:<css>" = phần tử bên trong khung soạn thảo Gutenberg (iframe editor-canvas).
export function loc(page, sel) {
  if (sel.startsWith('canvas:')) {
    const inner = sel.slice(7);
    return page.frameLocator('iframe[name="editor-canvas"]').locator(inner).first();
  }
  return page.locator(sel).first();
}

export function helpers(page) {
  return {
    async click(sel, opt = {}) {
      const loc = locOf(page, sel);
      await loc.waitFor({ state: 'visible', timeout: opt.timeout || 15000 });
      const txt = ((await loc.innerText().catch(() => '')) || (await loc.getAttribute('value').catch(() => '')) || '').trim();
      const id = (await loc.getAttribute('id').catch(() => '')) || '';
      if (!opt.force && (DANGER.test(txt) || /^(submit|publish|save-post|delete-action|doaction)$/.test(id))) {
        throw new Error(`Từ chối bấm nút nguy hiểm: "${txt || id}" (${sel})`);
      }
      await loc.click({ timeout: opt.timeout || 15000, ...(opt.position ? { position: opt.position } : {}) });
      await page.waitForTimeout(opt.wait ?? 250);
    },
    async hover(sel) { await locOf(page, sel).hover(); await page.waitForTimeout(250); },
    async fill(sel, value) { await locOf(page, sel).fill(value); },
    async type(sel, text) { const l = locOf(page, sel); await l.click(); await page.keyboard.type(text, { delay: 5 }); await page.waitForTimeout(200); },
    async press(key) { await page.keyboard.press(key); await page.waitForTimeout(200); },
    async select(sel, value) { await locOf(page, sel).selectOption(value); await page.waitForTimeout(250); },
    async check(sel) { await locOf(page, sel).check({ force: true }); await page.waitForTimeout(250); },
    async uncheck(sel) { await locOf(page, sel).uncheck({ force: true }); await page.waitForTimeout(250); },
    async scrollIntoView(sel, block = 'start') { await locOf(page, sel).evaluate((el, b) => el.scrollIntoView({ block: b }), block); await page.waitForTimeout(250); },
    async css(text) { await page.addStyleTag({ content: text }); },
    async hide(...sels) { await page.addStyleTag({ content: sels.join(',') + '{display:none!important}' }); },
    async wait(ms) { await page.waitForTimeout(ms); },
    async waitFor(sel, timeout = 20000) { await page.locator(sel).first().waitFor({ state: 'visible', timeout }); },
    async scrollTo(sel, offset = 0) {
      await page.locator(sel).first().evaluate((el, off) => {
        const r = el.getBoundingClientRect();
        window.scrollTo(0, Math.max(0, r.top + window.scrollY - off));
      }, offset);
      await page.waitForTimeout(150);
    },
    async eval(fn, arg) { return page.evaluate(fn, arg); },
  };
}

const locOf = (page, sel) => loc(page, sel);

async function rectOf(page, sel) {
  const loc = locOf(page, sel);
  try {
    await loc.waitFor({ state: 'visible', timeout: 15000 });
  } catch (e) {
    throw new Error(`Không thấy phần tử "${sel}"`);
  }
  const b = await loc.boundingBox();
  if (!b) throw new Error(`Không đo được "${sel}"`);
  return { x: b.x, y: b.y, w: b.width, h: b.height };
}

function unionRect(rects) {
  const x1 = Math.min(...rects.map((r) => r.x));
  const y1 = Math.min(...rects.map((r) => r.y));
  const x2 = Math.max(...rects.map((r) => r.x + r.w));
  const y2 = Math.max(...rects.map((r) => r.y + r.h));
  return { x: x1, y: y1, w: x2 - x1, h: y2 - y1 };
}

// Vẽ khung + số trong trang (toạ độ viewport) và trả về vùng chiếm của số để mở rộng vùng cắt.
async function drawMarks(page, marks, clip, fixedClip) {
  return page.evaluate(({ marks, clip, fixedClip }) => {
    const PAD = 6, R = 14, GAP = 5;
    const layer = document.createElement('div');
    layer.id = 'ct-shot-layer';
    layer.style.cssText = 'position:fixed;inset:0;pointer-events:none;z-index:2147483647;';
    document.documentElement.appendChild(layer);

    // Hình chữ nhật chứa chữ trong trang (để số không đè lên chữ).
    const textRects = [];
    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
      acceptNode: (n) => (n.nodeValue.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT),
    });
    const range = document.createRange();
    let node;
    while ((node = walker.nextNode())) {
      const el = node.parentElement;
      if (!el || el.closest('#ct-shot-layer')) continue;
      const cs = getComputedStyle(el);
      if (cs.visibility === 'hidden' || cs.display === 'none' || +cs.opacity === 0) continue;
      range.selectNodeContents(node);
      for (const r of range.getClientRects()) {
        if (r.width > 0 && r.height > 0 && r.bottom > 0 && r.right > 0 && r.top < innerHeight && r.left < innerWidth) {
          textRects.push({ x: r.left, y: r.top, w: r.width, h: r.height });
        }
      }
    }
    // Ô nhập liệu/nút cũng coi là vùng không được đè.
    document.querySelectorAll('input:not([type=hidden]),select,textarea,button,.button,img').forEach((el) => {
      const r = el.getBoundingClientRect();
      if (r.width && r.height) textRects.push({ x: r.left, y: r.top, w: r.width, h: r.height });
    });

    const hit = (a, b) => a.x < b.x + b.w && a.x + a.w > b.x && a.y < b.y + b.h && a.y + a.h > b.y;
    const placed = [];
    const boxes = marks.map((m) => {
      let x1 = m.rect.x - PAD, y1 = m.rect.y - PAD, x2 = m.rect.x + m.rect.w + PAD, y2 = m.rect.y + m.rect.h + PAD;
      // Không để khung tràn khỏi màn hình (mất viền): ép vào trong 3px. Khi vùng cắt không
      // được nới (growClip: false) thì ép vào vùng cắt.
      const lim = fixedClip && clip ? clip : { x: 0, y: 0, w: innerWidth, h: innerHeight };
      const L = Math.max(0, lim.x) + 3, T = Math.max(0, lim.y) + 3;
      const R2 = Math.min(innerWidth, lim.x + lim.w) - 3, B = Math.min(innerHeight, lim.y + lim.h) - 3;
      x1 = Math.max(x1, L); y1 = Math.max(y1, T); x2 = Math.min(x2, R2); y2 = Math.min(y2, B);
      return { x: x1, y: y1, w: x2 - x1, h: y2 - y1 };
    });
    // Hai khung sát nhau (VD hai hàng form liền kề): chia đôi khoảng hở để không chồng viền.
    for (let i = 0; i < boxes.length; i++) {
      for (let j = 0; j < boxes.length; j++) {
        if (i === j || marks[i].noBox || marks[j].noBox) continue;
        const a = boxes[i], b = boxes[j], ra = marks[i].rect, rb = marks[j].rect;
        const overlapX = a.x < b.x + b.w && a.x + a.w > b.x;
        const overlapY = a.y < b.y + b.h && a.y + a.h > b.y;
        if (overlapX && ra.y + ra.h <= rb.y + 1 && a.y + a.h > b.y - 4) {
          const mid = (ra.y + ra.h + rb.y) / 2;
          a.h = mid - 3 - a.y;
          const bottom = b.y + b.h; b.y = mid + 3; b.h = bottom - b.y;
        } else if (overlapY && ra.x + ra.w <= rb.x + 1 && a.x + a.w > b.x - 4) {
          const mid = (ra.x + ra.w + rb.x) / 2;
          a.w = mid - 3 - a.x;
          const right = b.x + b.w; b.x = mid + 3; b.w = right - b.x;
        }
      }
    }
    const out = [];

    marks.forEach((m, i) => {
      const b = boxes[i];
      if (!m.noBox) {
        const box = document.createElement('div');
        box.style.cssText = `position:absolute;left:${b.x}px;top:${b.y}px;width:${b.w}px;height:${b.h}px;` +
          'border:3px solid #D92D20;border-radius:8px;box-sizing:border-box;' +
          'box-shadow:0 0 0 2px rgba(255,255,255,.95), 0 2px 10px rgba(217,45,32,.18);';
        layer.appendChild(box);
      }
      if (m.n == null) return;
      const d = R * 2;
      const cy = b.y + Math.min(b.h / 2, 22);
      const cx = b.x + Math.min(b.w / 2, 22);
      const cand = {
        left: { x: b.x - GAP - d, y: cy - R },
        right: { x: b.x + b.w + GAP, y: cy - R },
        top: { x: cx - R, y: b.y - GAP - d },
        bottom: { x: cx - R, y: b.y + b.h + GAP },
        tl: { x: b.x - R - 2, y: b.y - R - 2 },
        tr: { x: b.x + b.w - R + 2, y: b.y - R - 2 },
        inside: { x: b.x + b.w - d - 6, y: b.y + 6 },
      };
      const order = m.side ? [m.side] : ['left', 'right', 'top', 'bottom', 'tl', 'tr', 'inside'];
      if (m.side && !m.strict) order.push(...['left', 'right', 'top', 'bottom', 'tl', 'tr', 'inside'].filter((s) => s !== m.side));
      let best = null, bestScore = Infinity;
      for (const s of order) {
        const c = { ...cand[s], w: d, h: d };
        let score = 0;
        if (clip && (c.x < clip.x || c.y < clip.y || c.x + d > clip.x + clip.w || c.y + d > clip.y + clip.h)) score += fixedClip ? 60 : 4;
        if (c.x < 0 || c.y < 0 || c.x + d > innerWidth || c.y + d > innerHeight) score += 1000;
        for (const p of placed) if (hit(c, p)) score += 500;
        boxes.forEach((bb, j) => { if (j !== i && !marks[j].noBox && hit(c, bb)) score += 40; });
        // Phía đã chỉ định: chấp nhận chạm chữ nhẹ (giữ đúng vị trí dễ đoán); phía dự phòng: né chữ.
        const textPenalty = m.side && s === m.side ? 3 : 25;
        for (const t of textRects) if (hit(c, t)) score += textPenalty;
        score += order.indexOf(s) * 0.5;
        if (score < bestScore) { bestScore = score; best = { ...c, side: s }; }
        if (score < 1) break;
      }
      placed.push(best);
      const badge = document.createElement('div');
      badge.textContent = String(m.n);
      badge.style.cssText = `position:absolute;left:${best.x}px;top:${best.y}px;width:${d}px;height:${d}px;` +
        'border-radius:50%;background:#1D4ED8;color:#fff;border:2.5px solid #fff;box-sizing:border-box;' +
        'font:700 15px/23px -apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;text-align:center;' +
        'box-shadow:0 1px 4px rgba(0,0,0,.35);';
      layer.appendChild(badge);
      out.push(best);
    });
    return { badges: out, boxes: boxes.filter((_, i) => !marks[i].noBox) };
  }, { marks, clip, fixedClip });
}

// Thay tên tài khoản (tên hiển thị = tên đăng nhập trên site mẫu) bằng nhãn vai trò, ở mọi
// chỗ: cột Tác giả, ô Tác giả trong trình soạn bài… Chạy ngay trước khi chụp.
async function hideAccountNames(page) {
  await page.evaluate(() => {
    const map = { admin: 'Quản lý', btv: 'Biên tập viên', tai_lieu_editor: 'Biên tập viên' };
    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
    let n;
    while ((n = walker.nextNode())) {
      const t = n.nodeValue.trim();
      if (map[t]) n.nodeValue = n.nodeValue.replace(t, map[t]);
    }
  });
}

export async function capture(page, spec, outDir) {
  const h = helpers(page);
  if (spec.setup) await spec.setup(page, h);
  await hideAccountNames(page);
  await page.mouse.move(1, 1);
  await page.waitForTimeout(spec.settle ?? 300);

  // Vùng cắt.
  let clip;
  const vp = page.viewportSize();
  if (!spec.clip || spec.clip === 'viewport') {
    clip = { x: 0, y: 0, w: vp.width, h: vp.height };
  } else if (typeof spec.clip === 'string' || Array.isArray(spec.clip)) {
    const sels = Array.isArray(spec.clip) ? spec.clip : [spec.clip];
    const rs = [];
    for (const s of sels) rs.push(await rectOf(page, s));
    clip = unionRect(rs);
  } else if (spec.clip.sel) {
    const sels = Array.isArray(spec.clip.sel) ? spec.clip.sel : [spec.clip.sel];
    const rs = [];
    for (const s of sels) rs.push(await rectOf(page, s));
    clip = unionRect(rs);
  } else {
    clip = { x: spec.clip.x, y: spec.clip.y, w: spec.clip.w, h: spec.clip.h };
  }
  const pad = spec.clip && spec.clip.pad != null ? spec.clip.pad : 12;
  const padArr = Array.isArray(pad) ? pad : [pad, pad, pad, pad]; // top right bottom left
  clip = { x: clip.x - padArr[3], y: clip.y - padArr[0], w: clip.w + padArr[1] + padArr[3], h: clip.h + padArr[0] + padArr[2] };
  if (spec.clip && spec.clip.maxH) clip.h = Math.min(clip.h, spec.clip.maxH);
  if (spec.clip && spec.clip.minW) { const add = Math.max(0, spec.clip.minW - clip.w); clip.w += add; }

  // Khung + số.
  const marks = [];
  for (const m of spec.marks || []) {
    const sels = Array.isArray(m.sel) ? m.sel : [m.sel];
    const rs = [];
    for (const s of sels) rs.push(await rectOf(page, s));
    marks.push({ rect: m.rect || unionRect(rs), n: m.n, side: m.side, strict: m.strict, noBox: m.noBox });
  }
  let drawn = { badges: [], boxes: [] };
  if (marks.length) drawn = await drawMarks(page, marks, clip, spec.growClip === false);

  // Mở rộng vùng cắt để chứa trọn khung và số (cách mép 8px).
  const extra = [...drawn.badges, ...drawn.boxes.map((b) => ({ x: b.x - 3, y: b.y - 3, w: b.w + 6, h: b.h + 6 }))];
  if (extra.length && spec.growClip !== false) {
    const u = unionRect([clip, ...extra.map((r) => ({ x: r.x - 8, y: r.y - 8, w: r.w + 16, h: r.h + 16 }))]);
    clip = u;
  }
  clip.x = Math.max(0, Math.floor(clip.x));
  clip.y = Math.max(0, Math.floor(clip.y));
  clip.w = Math.min(vp.width - clip.x, Math.ceil(clip.w));
  clip.h = Math.min(vp.height - clip.y, Math.ceil(clip.h));

  const file = path.join(outDir, `${spec.id}.png`);
  await page.screenshot({ path: file, clip: { x: clip.x, y: clip.y, width: clip.w, height: clip.h }, animations: 'disabled', caret: 'hide' });
  await page.evaluate(() => document.getElementById('ct-shot-layer')?.remove());
  return { file, css: { w: clip.w, h: clip.h }, marks: marks.length };
}

export async function preparePage(page, spec) {
  const role = spec.role || 'admin';
  const target = spec.file ? pathToFileURL(path.resolve(spec.file)).href : SITE + spec.url;
  await page.goto(target, { waitUntil: 'domcontentloaded' });
  await page.waitForLoadState('load').catch(() => {});
  if (spec.networkIdle !== false) await page.waitForLoadState('networkidle', { timeout: 15000 }).catch(() => {});
  const hideMenu = spec.hideAdminMenu ? '#adminmenumain{display:none!important}' : '';
  await page.addStyleTag({ content: BASE_CSS + (spec.keepAdminBar ? '' : HIDE_ADMIN_BAR_CSS) + hideMenu + (spec.css || '') });
  // Thay tên tài khoản bằng nhãn vai trò; che email/tên đăng nhập.
  await page.evaluate((label) => {
    document.querySelectorAll('#wp-admin-bar-my-account .display-name, #wp-admin-bar-user-info .display-name').forEach((e) => { e.textContent = label; });
    document.querySelectorAll('#wp-admin-bar-user-info .username').forEach((e) => { e.textContent = ''; });
    const greet = document.querySelector('#wp-admin-bar-my-account > a');
    if (greet && greet.firstChild && greet.firstChild.nodeType === 3) greet.firstChild.nodeValue = 'Xin chào, ';
    document.querySelectorAll('h1, h2').forEach((e) => { if (/^Xin chào, /.test(e.textContent.trim())) e.textContent = `Xin chào, ${label}!`; });
    // Trang Hồ sơ / Người dùng: làm mờ dữ liệu riêng (chỉ trong trang quản trị).
    if (location.pathname.includes('/wp-admin/')) document.querySelectorAll('#email, #user_login, #url, input[name="email"], .column-email, .column-username .row-title, td.email, td.username strong a').forEach((e) => { e.style.filter = 'blur(5px)'; });
  }, ROLE_LABEL[role] || '');
}
