# Rebranding / White-label Checklist

This theme is white-label ready: brand identity is centralized in
**`inc/brand/brand-config.php`** so a new client deployment only needs a few
edits + asset swaps. Internal code identifiers (`chinhtoa` text domain, `CT_*`
constants, `chinhtoa_*` functions) are intentionally kept — they're invisible to
end users and renaming them adds risk with no client-facing benefit.

## 1. Edit the brand config (required)

Open `inc/brand/brand-config.php` and update `ct_brand_defaults()`:

| Key | What it controls |
|-----|------------------|
| `name` | Brand name (front-end nav brand, `<head>` app titles) |
| `author`, `author_uri`, `theme_uri` | Theme attribution |
| `support_url` | Optional support link |
| `admin_menu_title`, `admin_menu_label`, `admin_menu_icon` | Theme admin menu |
| `admin_footer_credit` | wp-admin footer line — set `''` to remove |
| `favicon_dir` | Favicon folder (set `''` to skip favicon output) |
| `logo` | Logo path |
| `keywords`, `og_locale`, `fb_profile_id` | `<head>` meta / social |
| `theme_color`, `tile_color`, `mask_icon_color` | Browser chrome colors |
| `features.loichua` | Religious "Lời Chúa" box — set `false` for a generic build |

> Alternatively, override without editing the file via the `ct_brand` filter from
> a child theme / mu-plugin (see the docblock in `brand-config.php`).

## 2. Swap image assets (required)

- **Favicons** → replace files in `assets/imgs/favicons/`
  (`favicon.ico`, `favicon-16x16.png`, `favicon-32x32.png`, `apple-touch-icon.png`,
  `safari-pinned-tab.svg`, `browserconfig.xml`, …). Generate a set at e.g. realfavicongenerator.net.
- **Logo** → replace `assets/imgs/logo/logo.png` (and `logo-96x96.png`).
- **Theme screenshot** → replace `screenshot.png` (1200×900 recommended).

## 3. Update `style.css` header (required, static per brand)

Edit the theme header block: `Theme Name`, `Author`, `Author URI`, `Theme URI`,
`Description`, `Tags`. (These are WordPress metadata fields and cannot be made
dynamic.)

## 4. Generic (non-religious) build

- Set `features.loichua => false` in the brand config.
- Optionally delete the religious assets if unused: `assets/imgs/loichua/`,
  `assets/imgs/icons/church.svg`, and `template-parts/boxes/loichua.php`.
- In **Giao diện → Thiết lập giao diện → Trang chủ**, the "5 phút Lời Chúa"
  homepage box can be turned off independently (it is option-driven).

## 5. Translations (optional)

The text domain stays `chinhtoa`. To localize, update `languages/` and regenerate
the `.pot`. No source code text-domain changes are needed.

## 6. Verify

Search for any leftover brand strings before shipping:

```bash
grep -rn "giaoxuchinhtoadanang\|ToiLaTung\|toilatung\|bbland\|ImTung" \
  --include='*.php' . | grep -v '/framework/' | grep -v 'inc/brand/brand-config.php'
```

Only `inc/brand/brand-config.php` (and `style.css`) should contain brand values.
