#!/bin/bash
# Ghim theme của một site Local vào một BẢN CỐ ĐỊNH, để sửa code trong repo không làm site đó
# đổi theo. Chỉ site dùng để thử trỏ thẳng vào repo (symlink tới chinhtoa/).
#
#   tools/pin-theme.sh <thư-mục-theme-của-site> [git-ref]
#       Chụp chinhtoa/ hiện tại (kể cả thay đổi chưa commit), hoặc xuất đúng [git-ref]
#       (VD v1.2.0), vào $CT_RELEASES rồi trỏ thư mục theme của site sang bản đó.
#   tools/pin-theme.sh --dev <thư-mục-theme-của-site>
#       Trỏ lại thẳng vào repo (site thử).
#
# VD: tools/pin-theme.sh ~/Local\ Sites/5plc/app/public/wp-content/themes/church v1.2.0
#
# Bản cố định nằm ở $CT_RELEASES (mặc định ~/Local Sites/_theme-releases/):
#   chinhtoa-<git-ref>                       khi xuất từ git
#   chinhtoa-<version>-<YYYYmmdd-HHMMSS>     khi chụp thư mục hiện tại
# Quay lại bản trước (rollback): script in đường dẫn cũ; chạy  ln -sfn "<bản cũ>" "<thư-mục-theme>"
#
# An toàn: chỉ thay thư mục theme khi nó là symlink (không bao giờ xoá thư mục thật). Tên thư
# mục theme của site (VD church, chinhtoa) giữ nguyên — WordPress lưu menu/thiết lập theo tên này.
set -euo pipefail

REPO="$(cd "$(dirname "$0")/.." && pwd)"
RELEASES="${CT_RELEASES:-$HOME/Local Sites/_theme-releases}"

usage() { sed -n '2,21p' "$0" | sed 's/^# \{0,1\}//'; exit 1; }

DEV=0
if [ "${1:-}" = "--dev" ]; then DEV=1; shift; fi
TARGET="${1:-}"; REF="${2:-}"
[ -n "$TARGET" ] || usage
TARGET="${TARGET%/}"

if [ -e "$TARGET" ] && [ ! -L "$TARGET" ]; then
  echo "Lỗi: $TARGET là thư mục thật, không phải symlink — không thay để tránh mất dữ liệu." >&2
  exit 1
fi
OLD="$( [ -L "$TARGET" ] && readlink "$TARGET" || echo '(chưa có)')"

if [ "$DEV" = 1 ]; then
  DEST="$REPO/chinhtoa"
else
  mkdir -p "$RELEASES"
  if [ -n "$REF" ]; then
    DEST="$RELEASES/chinhtoa-$REF"
    if [ ! -d "$DEST" ]; then
      TMP="$(mktemp -d)"
      git -C "$REPO" archive "$REF" chinhtoa | tar -x -C "$TMP"
      mv "$TMP/chinhtoa" "$DEST"
      rm -rf "$TMP"
    fi
  else
    VERSION="$(sed -n 's/^Version: *//p' "$REPO/chinhtoa/style.css" | tr -d '\r')"
    DEST="$RELEASES/chinhtoa-$VERSION-$(date +%Y%m%d-%H%M%S)"
    rsync -a --exclude '.DS_Store' "$REPO/chinhtoa/" "$DEST/"
  fi
fi

ln -sfn "$DEST" "$TARGET"
echo "Theme: $TARGET"
echo "  trước: $OLD"
echo "  nay:   $DEST"
