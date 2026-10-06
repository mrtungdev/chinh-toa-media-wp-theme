#!/usr/bin/env python3
"""Create privacy-clean, annotated PNG screenshots for the handbook.

The source captures are kept outside version control.  Every output is cropped to
the task area, converted to a real PNG, and marked with red rectangles plus blue
step numbers.  Coordinates are expressed in the original 1782 x 1412 capture.
"""

from __future__ import annotations

from pathlib import Path
from typing import Iterable

from PIL import Image, ImageDraw, ImageFilter, ImageFont


ROOT = Path(__file__).resolve().parents[1]
RAW = ROOT / "screenshots" / "raw"
FINAL = ROOT / "screenshots" / "final"
FONT_PATH = Path("/System/Library/Fonts/Supplemental/Arial Bold.ttf")


def spec(
    src: str,
    dst: str,
    crop: tuple[int, int, int, int],
    boxes: Iterable[tuple[int, ...]] = (),
    redacts: Iterable[tuple[int, int, int, int]] = (),
):
    return {
        "src": src,
        "dst": dst,
        "crop": crop,
        "boxes": list(boxes),
        "redacts": list(redacts),
    }


SPECS = [
    spec(
        "public-homepage-desktop.png",
        "P01-C01-B01-01-website-cong-khai.png",
        (0, 38, 1782, 1230),
        [(20, 55, 1760, 190, 1), (215, 220, 1260, 1120, 2)],
        [(0, 38, 225, 115)],
    ),
    spec(
        "login-screen.png",
        "P01-C01-B02-01-man-hinh-dang-nhap.png",
        (575, 155, 1195, 965),
        [(690, 410, 1085, 630, 1), (920, 615, 1085, 680, 2)],
        [(715, 215, 1055, 335)],
    ),
    spec(
        "editor-dashboard.png",
        "P01-C01-B04-01-bang-dieu-khien.png",
        (0, 38, 1540, 930),
        [(0, 60, 205, 840, 1), (210, 225, 1515, 610, 2)],
    ),
    spec(
        "editor-theme-settings-denied.png",
        "P01-C02-B03-01-khu-vuc-bi-tu-choi.png",
        (0, 38, 1420, 700),
        [(190, 120, 1040, 275, 1)],
    ),
    spec(
        "editor-posts-list.png",
        "P02-C03-B01-01-danh-sach-bai-viet.png",
        (0, 38, 1782, 770),
        [(185, 95, 540, 190, 1), (250, 220, 1740, 570, 2), (1430, 110, 1750, 185, 3)],
    ),
    spec(
        "editor-post-content.png",
        "P02-C04-B01-01-soan-thao-gutenberg.png",
        (205, 38, 1782, 880),
        [(320, 155, 1160, 255, 1), (315, 255, 1190, 500, 2), (1420, 40, 1745, 115, 3)],
    ),
    spec(
        "editor-post-content.png",
        "P02-C04-B03-01-thanh-cong-cu-khoi.png",
        (250, 175, 1200, 570),
        [(315, 290, 760, 390, 1), (315, 390, 1100, 475, 2)],
    ),
    spec(
        "editor-featured-image-selected.png",
        "P02-C05-B02-01-chon-anh-dai-dien.png",
        (20, 38, 1765, 1390),
        [(35, 170, 225, 360, 1), (1450, 390, 1745, 785, 2), (1590, 1310, 1740, 1375, 3)],
        [(1555, 725, 1745, 785)],
    ),
    spec(
        "editor-post-categories-panel.png",
        "P02-C05-B03-01-chon-chuyen-muc.png",
        (1160, 130, 1782, 1120),
        [(1485, 390, 1770, 885, 1), (1490, 885, 1765, 955, 2)],
    ),
    spec(
        "editor-post-excerpt.png",
        "P02-C05-B05-01-tom-tat-bai-viet.png",
        (1135, 120, 1782, 845),
        [(1480, 385, 1770, 685, 1)],
    ),
    spec(
        "editor-post-kind-media.png",
        "P02-C05-B06-01-thanh-xem-truoc-va-xuat-ban.png",
        (1300, 38, 1782, 445),
        [(1420, 42, 1605, 105, 1), (1605, 40, 1745, 110, 2), (1500, 185, 1765, 370, 3)],
    ),
    spec(
        "editor-category-add-form.png",
        "P03-C07-B03-01-them-chuyen-muc.png",
        (0, 38, 1515, 1165),
        [(190, 155, 720, 1060, 1), (760, 150, 1490, 690, 2)],
    ),
    spec(
        "editor-category-edit.png",
        "P03-C07-B05-01-sua-chuyen-muc.png",
        (0, 38, 1160, 1270),
        [(200, 130, 1125, 720, 1), (205, 745, 1125, 1250, 2)],
        [(220, 140, 1110, 180)],
    ),
    spec(
        "editor-category-custom-layout-fields-v2.png",
        "P03-C08-B02-01-bo-cuc-rieng-chuyen-muc.png",
        (155, 575, 1015, 1410),
        [
            (380, 615, 975, 685, 1, 930, 605),
            (380, 690, 875, 950, 2, 835, 705),
            (380, 955, 980, 1400, 3, 700, 975),
        ],
    ),
    spec(
        "editor-media-library-with-sample.png",
        "P04-C10-B01-01-thu-vien-media.png",
        (0, 38, 1782, 710),
        [(180, 95, 560, 175, 1), (195, 200, 540, 470, 2), (1460, 105, 1750, 180, 3)],
    ),
    spec(
        "editor-media-upload-form.png",
        "P04-C10-B02-01-tai-tep-media.png",
        (0, 38, 1200, 760),
        [(175, 100, 430, 185, 1), (425, 245, 815, 420, 2)],
    ),
    spec(
        "editor-media-attachment-details.png",
        "P04-C10-B04-01-chi-tiet-tep-dinh-kem.png",
        (0, 38, 1782, 1220),
        [(20, 120, 1170, 1110, 1), (1190, 125, 1745, 1100, 2)],
        [(1350, 555, 1745, 615)],
    ),
    spec(
        "editor-pages-list.png",
        "P05-C11-B02-01-danh-sach-trang.png",
        (0, 38, 1782, 700),
        [(190, 95, 495, 180, 1), (230, 205, 1735, 565, 2)],
    ),
    spec(
        "editor-page-content.png",
        "P05-C11-B04-01-sua-trang-gutenberg.png",
        (205, 38, 1782, 815),
        [(315, 155, 1160, 250, 1), (315, 260, 1190, 470, 2), (1430, 40, 1745, 110, 3)],
    ),
    spec(
        "editor-page-elementor-button.png",
        "PHU-LUC-C-01-nut-edit-with-elementor.png",
        (190, 38, 820, 340),
        [(215, 45, 485, 115, 1), (315, 160, 770, 270, 2)],
    ),
    spec(
        "admin-reading-settings.png",
        "P05-C12-B04-01-cai-dat-doc.png",
        (0, 38, 1280, 980),
        [(175, 120, 1010, 485, 1), (175, 500, 1010, 820, 2)],
    ),
    spec(
        "admin-theme-colors.png",
        "P06-C13-B01-01-thiet-lap-giao-dien.png",
        (0, 38, 1220, 1250),
        [(0, 45, 215, 760, 1), (195, 90, 1180, 230, 2)],
    ),
    spec(
        "admin-theme-colors.png",
        "P06-C14-B01-01-mau-sac-va-menu.png",
        (165, 85, 1180, 1160),
        [(195, 145, 960, 380, 1), (195, 390, 1010, 780, 2), (195, 790, 1010, 1080, 3)],
    ),
    spec(
        "admin-theme-header-banner-v2.png",
        "P06-C14-B02-01-thiet-lap-header.png",
        (165, 85, 1160, 720),
        [
            (190, 285, 1090, 380, 1, 1045, 325),
            (190, 385, 1090, 685, 2, 1045, 420),
        ],
    ),
    spec(
        "admin-theme-header-banner-v2.png",
        "P06-C14-B05-01-truong-anh-va-lien-ket-header.png",
        (165, 350, 1160, 985),
        [
            (190, 385, 1090, 685, 1, 1045, 420),
            (190, 690, 1090, 950, 2, 1045, 725),
        ],
    ),
    spec(
        "admin-theme-footer.png",
        "P06-C14-B06-01-thiet-lap-footer.png",
        (165, 85, 1180, 860),
        [(195, 130, 990, 500, 1), (190, 510, 990, 785, 2)],
    ),
    spec(
        "admin-theme-homepage.png",
        "P06-C15-B04-01-khoi-noi-dung-trang-chu.png",
        (165, 85, 1240, 1390),
        [(190, 130, 1010, 360, 1), (190, 375, 1020, 1020, 2), (190, 1030, 1020, 1360, 3)],
    ),
    spec(
        "admin-theme-default-layouts.png",
        "P06-C15-B09-01-bo-cuc-mac-dinh.png",
        (165, 85, 1210, 920),
        [(190, 130, 1000, 450, 1), (190, 470, 1000, 830, 2)],
    ),
    spec(
        "admin-theme-notification-enabled-v2.png",
        "P06-C16-B01-01-dai-thong-bao.png",
        (165, 85, 1160, 1045),
        [
            (190, 255, 1115, 535, 1, 1075, 285),
            (190, 535, 1115, 770, 2, 1075, 565),
            (190, 775, 1115, 960, 3, 1075, 805),
        ],
    ),
    spec(
        "admin-theme-advanced.png",
        "P06-C16-B04-01-khu-vuc-nang-cao.png",
        (165, 85, 1200, 1120),
        [(190, 125, 1010, 1060, 1)],
        [(425, 395, 1110, 525), (425, 570, 1110, 705)],
    ),
    spec(
        "admin-menus.png",
        "P07-C17-B02-01-menu-chinh.png",
        (0, 38, 1540, 1050),
        [(175, 100, 650, 920, 1), (650, 160, 1510, 775, 2), (650, 785, 1510, 990, 3)],
    ),
    spec(
        "admin-widgets.png",
        "P07-C18-B02-01-widget-co-dien.png",
        (0, 38, 1560, 1330),
        [(175, 95, 720, 1190, 1), (745, 105, 1535, 1250, 2)],
    ),
    spec(
        "editor-post-kind-loichua.png",
        "P08-C19-B03-01-bai-loi-chua.png",
        (1160, 510, 1782, 1390),
        [(1500, 745, 1765, 850, 1), (1490, 855, 1765, 1205, 2), (1490, 1210, 1765, 1370, 3)],
    ),
    spec(
        "editor-post-kind-media.png",
        "P08-C19-B05-01-bai-video-audio.png",
        (1160, 750, 1782, 1395),
        [(1490, 930, 1765, 1045, 1), (1485, 1050, 1765, 1245, 2)],
        [(1510, 1080, 1760, 1140)],
    ),
    spec(
        "admin-post-layout-meta.png",
        "P08-C20-B03-01-bo-cuc-rieng-bai-viet.png",
        (1160, 740, 1782, 1390),
        [(1490, 940, 1765, 1080, 1), (1490, 1085, 1765, 1365, 2)],
    ),
    spec(
        "editor-profile.png",
        "P09-C21-B01-01-ho-so-ca-nhan.png",
        (0, 38, 1220, 1260),
        [(175, 95, 1010, 430, 1), (175, 440, 1010, 1060, 2)],
        [(385, 885, 745, 935), (385, 1095, 745, 1145), (385, 1165, 615, 1215)],
    ),
    spec(
        "lost-password-screen.png",
        "P09-C21-B04-01-quen-mat-khau.png",
        (575, 155, 1195, 830),
        [(690, 395, 1085, 610, 1), (870, 590, 1085, 655, 2)],
        [(715, 215, 1055, 330)],
    ),
    spec(
        "admin-users-list.png",
        "P09-C22-B02-01-quan-ly-nguoi-dung.png",
        (0, 38, 1600, 720),
        [(175, 95, 545, 180, 1), (220, 215, 1560, 525, 2)],
        [(250, 285, 1180, 455)],
    ),
    spec(
        "admin-yoast-dashboard.png",
        "PHU-LUC-B-01-yoast-seo.png",
        (0, 38, 1650, 1320),
        [(0, 40, 215, 1080, 1), (215, 95, 1350, 1120, 2)],
        [(450, 95, 605, 150)],
    ),
    spec(
        "editor-elementor-editor.png",
        "PHU-LUC-C-02-giao-dien-elementor.png",
        (0, 0, 1782, 1300),
        [(0, 70, 520, 1240, 1), (520, 70, 1590, 1230, 2), (1590, 70, 1780, 1230, 3)],
    ),
    spec(
        "admin-updraftplus.png",
        "PHU-LUC-D-01-updraftplus.png",
        (0, 38, 1600, 930),
        [(180, 85, 1540, 520, 1), (180, 530, 1540, 860, 2)],
        [(240, 760, 1510, 860)],
    ),
    spec(
        "admin-plugins.png",
        "PHU-LUC-E-01-plugin-da-cai.png",
        (0, 38, 1782, 930),
        [(180, 90, 620, 180, 1), (220, 205, 1740, 865, 2)],
    ),
    spec(
        "admin-permalinks.png",
        "PHU-LUC-E-02-cau-truc-duong-dan.png",
        (0, 38, 1290, 1130),
        [(175, 95, 1020, 1030, 1)],
        [
            (390, 390, 600, 425),
            (390, 455, 700, 490),
            (390, 520, 675, 555),
            (390, 585, 650, 625),
            (390, 650, 625, 690),
            (390, 725, 545, 775),
            (1135, 950, 1290, 995),
        ],
    ),
]


def redact_region(image: Image.Image, region: tuple[int, int, int, int]) -> None:
    """Pixelate a sensitive region without hiding the surrounding UI."""
    x1, y1, x2, y2 = region
    box = image.crop(region)
    small = box.resize((max(1, (x2 - x1) // 24), max(1, (y2 - y1) // 24)))
    pixelated = small.resize(box.size, Image.Resampling.NEAREST).filter(ImageFilter.GaussianBlur(1.2))
    image.paste(pixelated, (x1, y1))


def draw_marker(draw: ImageDraw.ImageDraw, number: int, x: int, y: int, font: ImageFont.FreeTypeFont) -> None:
    radius = 22
    draw.ellipse((x - radius, y - radius, x + radius, y + radius), fill="#135FA7", outline="white", width=3)
    text = str(number)
    bounds = draw.textbbox((0, 0), text, font=font)
    draw.text(
        (x - (bounds[2] - bounds[0]) / 2, y - (bounds[3] - bounds[1]) / 2 - 2),
        text,
        font=font,
        fill="white",
    )


def process(item: dict) -> None:
    source = RAW / item["src"]
    if not source.exists():
        raise FileNotFoundError(source)
    image = Image.open(source).convert("RGB")
    if image.size != (1782, 1412):
        # Chrome can vary by a few pixels after a navigation; normalize the
        # capture so the reviewed annotation map remains deterministic.
        width_delta = abs(image.width - 1782)
        height_delta = abs(image.height - 1412)
        if width_delta > 20 or height_delta > 20:
            raise ValueError(f"Unexpected source size for {source.name}: {image.size}")
        image = image.resize((1782, 1412), Image.Resampling.LANCZOS)

    for region in item["redacts"]:
        redact_region(image, region)

    draw = ImageDraw.Draw(image)
    font = ImageFont.truetype(str(FONT_PATH), 28)
    for box in item["boxes"]:
        x1, y1, x2, y2, number = box[:5]
        draw.rounded_rectangle((x1, y1, x2, y2), radius=8, outline="#D6222A", width=5)
        if len(box) == 7:
            marker_x, marker_y = box[5:]
        else:
            marker_x = min(x2 - 24, max(x1 + 24, x1 + 28))
            marker_y = min(y2 - 24, max(y1 + 24, y1 + 28))
        draw_marker(draw, number, marker_x, marker_y, font)

    cropped = image.crop(item["crop"])
    if cropped.width > 1600:
        height = round(cropped.height * 1600 / cropped.width)
        cropped = cropped.resize((1600, height), Image.Resampling.LANCZOS)

    FINAL.mkdir(parents=True, exist_ok=True)
    cropped.save(FINAL / item["dst"], "PNG", optimize=True)


def main() -> None:
    FINAL.mkdir(parents=True, exist_ok=True)
    expected = {item["dst"] for item in SPECS}
    for existing in FINAL.glob("*.png"):
        if existing.name not in expected:
            existing.unlink()
    for item in SPECS:
        process(item)
    print(f"Created {len(SPECS)} privacy-clean screenshots in {FINAL}")


if __name__ == "__main__":
    main()
