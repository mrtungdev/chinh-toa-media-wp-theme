// Công cụ dò: mở một trang quản trị (đã đăng nhập) và in danh sách phần tử dễ nhắm
// (tiêu đề, nhãn hàng form, nút, liên kết menu…) kèm vị trí, để viết shots.config.mjs.
//
//   CT_AUTH_DIR=… node probe.mjs admin "/wp-admin/edit.php" [--shot tmp.png] [--click "sel"]…
import { openBrowser, newContext, preparePage, helpers } from './lib.mjs';

const [role, url, ...rest] = process.argv.slice(2);
const shotIdx = rest.indexOf('--shot');
const shot = shotIdx >= 0 ? rest[shotIdx + 1] : null;
const clicks = [];
rest.forEach((a, i) => { if (a === '--click') clicks.push(rest[i + 1]); });
const vpIdx = rest.indexOf('--vp');
const vp = vpIdx >= 0 ? rest[vpIdx + 1].split('x').map(Number) : [1280, 900];

const browser = await openBrowser();
const ctx = await newContext(browser, role, vp, { mobile: rest.includes('--mobile') });
const page = await ctx.newPage();
await preparePage(page, { url, role, keepAdminBar: rest.includes('--bar') });
const h = helpers(page);
for (const c of clicks) await h.click(c);
await page.waitForTimeout(400);
const items = await page.evaluate(() => {
  const out = [];
  const sel = 'h1,h2,h3,.wp-heading-inline,th,label,legend,.button,button,a.ct-tab,a.ct-subtab,#adminmenu > li > a,.page-title-action,.ct-sec-group-title,.components-button[aria-label],.components-panel__body-title';
  document.querySelectorAll(sel).forEach((el) => {
    const r = el.getBoundingClientRect();
    if (!r.width || !r.height || r.top > innerHeight * 3) return;
    const t = (el.innerText || el.getAttribute('aria-label') || el.value || '').trim().replace(/\s+/g, ' ').slice(0, 60);
    if (!t) return;
    const id = el.id ? '#' + el.id : '';
    const cls = (el.className && typeof el.className === 'string') ? '.' + el.className.trim().split(/\s+/).slice(0, 2).join('.') : '';
    out.push(`${Math.round(r.left)},${Math.round(r.top)} ${Math.round(r.width)}x${Math.round(r.height)}  <${el.tagName.toLowerCase()}${id}${cls}> ${t}`);
  });
  return { h: document.documentElement.scrollHeight, items: out };
});
console.log(`scrollHeight=${items.h}`);
console.log(items.items.join('\n'));
if (shot) await page.screenshot({ path: shot, fullPage: rest.includes('--full') });
await browser.close();
