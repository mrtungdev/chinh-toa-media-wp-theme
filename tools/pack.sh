#!/bin/bash
# Đóng gói theme để cài lên host: dist/chinh-toa-media-wp-theme.zip, bên trong là thư mục
# chinh-toa-media-wp-theme/ (tên thân thiện — xem BUILD.md).
#
#   pnpm run pack              # đóng gói thư mục chinhtoa/ hiện tại
#   pnpm run pack -- v1.2.0    # đóng gói đúng bản đã gắn tag (git archive)
#
# Nâng cấp site đang chạy: chép đè NỘI DUNG zip vào thư mục theme hiện có của site (giữ nguyên
# tên thư mục, VD chinhtoa/) — đổi tên thư mục là WordPress coi như theme khác (mất menu).
set -euo pipefail

REPO="$(cd "$(dirname "$0")/.." && pwd)"
NAME="chinh-toa-media-wp-theme"
REF="${1:-}"

TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT

if [ -n "$REF" ]; then
  git -C "$REPO" archive "$REF" chinhtoa | tar -x -C "$TMP"
else
  rsync -a --exclude '.DS_Store' "$REPO/chinhtoa/" "$TMP/chinhtoa/"
fi
mv "$TMP/chinhtoa" "$TMP/$NAME"

VERSION="$(sed -n 's/^Version: *//p' "$TMP/$NAME/style.css" | tr -d '\r')"
mkdir -p "$REPO/dist"
OUT="$REPO/dist/$NAME.zip"
rm -f "$OUT"
(cd "$TMP" && zip -rqX "$OUT" "$NAME" -x '*.DS_Store')

echo "Đã đóng gói $NAME $VERSION${REF:+ (từ $REF)} → $OUT"
