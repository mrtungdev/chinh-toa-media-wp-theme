// Chụp toàn bộ (hoặc một phần) ảnh minh hoạ khai báo trong shots.config.mjs.
//
//   CT_AUTH_DIR=… node shots.mjs                 # chụp tất cả
//   CT_AUTH_DIR=… node shots.mjs --only id1,id2  # chụp lại vài ảnh
//   CT_AUTH_DIR=… node shots.mjs --grep P06      # chụp các ảnh có id chứa chuỗi
//
// Ảnh PNG 2x ghi vào ../../screenshots/final/. Ảnh có khai báo `guide` được xuất thêm
// bản WebP (cwebp) vào chinhtoa/assets/imgs/guide/ cho trang Hướng dẫn trong WordPress.
// manifest.json ghi kích thước CSS (px) của từng ảnh để build_docx.py tính độ rộng in.
import fs from 'node:fs';
import path from 'node:path';
import { execFileSync } from 'node:child_process';
import { fileURLToPath } from 'node:url';
import { openBrowser, newContext, preparePage, capture } from './lib.mjs';
import specs from './shots.config.mjs';

const here = path.dirname(fileURLToPath(import.meta.url));
const OUT = path.resolve(here, '../../screenshots/final');
const GUIDE_OUT = path.resolve(here, '../../../../chinhtoa/assets/imgs/guide');
fs.mkdirSync(OUT, { recursive: true });
fs.mkdirSync(GUIDE_OUT, { recursive: true });

const args = process.argv.slice(2);
const only = args.includes('--only') ? args[args.indexOf('--only') + 1].split(',') : null;
const grep = args.includes('--grep') ? args[args.indexOf('--grep') + 1] : null;
let todo = specs;
if (only) todo = specs.filter((s) => only.includes(s.id));
if (grep) todo = todo.filter((s) => s.id.includes(grep) || (s.guide || '').includes(grep));
if (!todo.length) { console.error('Không có ảnh nào khớp.'); process.exit(1); }

const manifestPath = path.join(OUT, 'manifest.json');
const manifest = fs.existsSync(manifestPath) ? JSON.parse(fs.readFileSync(manifestPath, 'utf8')) : {};

const browser = await openBrowser();
let ok = 0, fail = 0;
for (const spec of todo) {
  const vp = spec.viewport || [1280, 900];
  const ctx = await newContext(browser, spec.role || 'admin', vp, { mobile: spec.mobile });
  const page = await ctx.newPage();
  try {
    await preparePage(page, spec);
    const res = await capture(page, spec, OUT);
    manifest[spec.id] = { w: res.css.w, h: res.css.h, scale: 2, role: spec.role || 'admin', url: spec.url };
    if (spec.guide) {
      const webp = path.join(GUIDE_OUT, spec.guide);
      const maxW = Math.min(1400, res.css.w * 2);
      execFileSync('cwebp', ['-quiet', '-q', '80', '-resize', String(maxW), '0', res.file, '-o', webp]);
    }
    console.log(`✓ ${spec.id}  (${res.css.w}×${res.css.h} css)${spec.guide ? '  → guide/' + spec.guide : ''}`);
    ok++;
  } catch (e) {
    console.error(`✗ ${spec.id}: ${e.message.split('\n')[0]}`);
    fail++;
    if (process.env.CT_DEBUG) await page.screenshot({ path: path.join(OUT, `_debug-${spec.id}.png`) });
  } finally {
    if (ctx.__blocked.length && process.env.CT_DEBUG) console.log('   (đã chặn:', ctx.__blocked.length, 'request ghi)');
    await ctx.close();
  }
}
await browser.close();
fs.writeFileSync(manifestPath, JSON.stringify(Object.fromEntries(Object.entries(manifest).sort()), null, 2) + '\n');
console.log(`\nXong: ${ok} ảnh, lỗi ${fail}.`);
process.exit(fail ? 1 : 0);
