// In kết quả một biểu thức JS chạy trên trang (đã đăng nhập). Chỉ đọc.
//   node evalpage.mjs admin "/wp-admin/" "[...document.querySelectorAll('#adminmenu a')].map(a=>a.innerText)"
import { openBrowser, newContext, preparePage, helpers } from './lib.mjs';
const [role, url, expr, ...rest] = process.argv.slice(2);
const clicks = []; rest.forEach((a, i) => { if (a === '--click') clicks.push(rest[i + 1]); });
const vpIdx = rest.indexOf('--vp');
const vp = vpIdx >= 0 ? rest[vpIdx + 1].split('x').map(Number) : [1280, 900];
const browser = await openBrowser();
const ctx = await newContext(browser, role, vp, { mobile: rest.includes('--mobile') });
const page = await ctx.newPage();
await preparePage(page, { url, role, keepAdminBar: true });
const h = helpers(page);
for (const c of clicks) await h.click(c);
await page.waitForTimeout(500);
const res = await page.evaluate(expr);
console.log(typeof res === 'string' ? res : JSON.stringify(res, null, 1));
await browser.close();
