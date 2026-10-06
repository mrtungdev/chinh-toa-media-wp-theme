// Mở Google Chrome (có giao diện) tới trang đăng nhập WordPress để NGƯỜI DÙNG tự đăng nhập.
// Sau khi vào được /wp-admin/, lưu phiên đăng nhập (cookie) ra tệp storageState để
// shots.mjs chụp ảnh không cần đăng nhập lại. Script KHÔNG nhập mật khẩu.
//
//   CT_AUTH_DIR=/đường/dẫn/an-toàn node login.mjs admin
//   CT_AUTH_DIR=/đường/dẫn/an-toàn node login.mjs editor
//
// Tệp phiên chứa cookie đăng nhập → để ngoài repo (mặc định ./.auth, đã gitignore).
import { chromium } from 'playwright-core';
import fs from 'node:fs';
import path from 'node:path';

const SITE = process.env.CT_SITE || 'http://5plc.local';
const role = process.argv[2] || 'admin';
if (!['admin', 'editor'].includes(role)) {
  console.error('Vai trò phải là admin hoặc editor');
  process.exit(1);
}
const authDir = process.env.CT_AUTH_DIR || path.resolve('.auth');
fs.mkdirSync(authDir, { recursive: true });
const out = path.join(authDir, `${role}.json`);

const browser = await chromium.launch({ channel: 'chrome', headless: false, args: ['--window-size=1100,860'] });
const context = await browser.newContext({ viewport: null, locale: 'vi-VN' });
const page = await context.newPage();
await page.goto(`${SITE}/wp-login.php?redirect_to=${encodeURIComponent(SITE + '/wp-admin/')}`);
console.log(`[${role}] Hãy đăng nhập trong cửa sổ Chrome vừa mở (chờ tối đa 10 phút)…`);

await page.waitForURL((u) => u.pathname.startsWith('/wp-admin') && !u.pathname.includes('wp-login'), { timeout: 600000 });
await page.waitForLoadState('domcontentloaded');

// Kiểm tra vai trò: Quản lý có menu Plugin; Biên tập viên có menu Bài viết + Trang
// nhưng không có Plugin; còn lại (Người đăng ký, Tác giả…) không dùng để chụp.
const has = async (sel) => (await page.locator(sel).count()) > 0;
let detected = 'khác';
if (await has('#menu-plugins')) detected = 'admin';
else if ((await has('#menu-posts')) && (await has('#menu-pages'))) detected = 'editor';
if (detected !== role) {
  console.error(`Tài khoản vừa đăng nhập có vẻ là "${detected}", không phải "${role}". Không lưu.`);
  await browser.close();
  process.exit(2);
}

await context.storageState({ path: out });
fs.chmodSync(out, 0o600);
console.log(`[${role}] Đã lưu phiên đăng nhập → ${out}`);
await browser.close();
