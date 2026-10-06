#!/usr/bin/env python3
"""Validate navigation, accessibility, image, and privacy invariants in the DOCX."""

from __future__ import annotations

import re
import sys
import zipfile
from pathlib import Path
from xml.etree import ElementTree as ET

ROOT = Path(__file__).resolve().parents[1]
SRC = ROOT / "src"
TOC = SRC / "00-muc-luc-de-xuat.md"


def expected_counts() -> tuple[int, int, int]:
    """Số bài, số liên kết phần/chương và số ảnh nhúng — lấy từ mục lục và nguồn."""
    toc = TOC.read_text(encoding="utf-8")
    lessons = len(re.findall(r"(?m)^- Bài\s+[A-Z0-9]+\.[0-9]+\b", toc))
    links = len(re.findall(r"(?m)^(# Phần |## Chương |## Phụ lục )", toc))
    images = 0
    for path in SRC.glob("*.md"):
        images += len(re.findall(r"(?m)^!\[.+?\]\(.+?\)\s*$", path.read_text(encoding="utf-8")))
    return lessons, links, images


W = "http://schemas.openxmlformats.org/wordprocessingml/2006/main"
WP = "http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
A = "http://schemas.openxmlformats.org/drawingml/2006/main"


def main() -> int:
    if len(sys.argv) != 2:
        print("Usage: validate_docx_structure.py <document.docx>", file=sys.stderr)
        return 2

    path = Path(sys.argv[1])
    errors: list[str] = []
    with zipfile.ZipFile(path) as archive:
        document = ET.fromstring(archive.read("word/document.xml"))
        settings = ET.fromstring(archive.read("word/settings.xml"))
        field_roots = [document]
        for name in archive.namelist():
            if name.startswith("word/header") or name.startswith("word/footer"):
                if name.endswith(".xml"):
                    field_roots.append(ET.fromstring(archive.read(name)))

    bookmarks = {
        node.get(f"{{{W}}}name", "")
        for node in document.iter(f"{{{W}}}bookmarkStart")
        if node.get(f"{{{W}}}name")
    }
    anchors = [
        node.get(f"{{{W}}}anchor", "")
        for node in document.iter(f"{{{W}}}hyperlink")
        if node.get(f"{{{W}}}anchor")
    ]
    lesson_bookmarks = {name for name in bookmarks if name.startswith("lesson_")}
    missing_anchors = sorted(set(anchors) - bookmarks)

    descriptions = [
        node.get("descr", "").strip()
        for node in document.iter(f"{{{WP}}}docPr")
    ]
    image_refs = list(document.iter(f"{{{A}}}blip"))
    text = "\n".join((node.text or "") for node in document.iter(f"{{{W}}}t"))
    field_text = " ".join(
        node.text or ""
        for root in field_roots
        for node in root.iter(f"{{{W}}}instrText")
    )
    update_fields = settings.find(f"{{{W}}}updateFields")

    exp_lessons, exp_links, exp_images = expected_counts()
    if len(lesson_bookmarks) != exp_lessons:
        errors.append(f"Expected {exp_lessons} lesson bookmarks, found {len(lesson_bookmarks)}")
    if len(anchors) != exp_links:
        errors.append(f"Expected {exp_links} part/chapter links in the quick contents, found {len(anchors)}")
    if missing_anchors:
        errors.append(f"Broken internal anchors: {', '.join(missing_anchors)}")
    if len(descriptions) != exp_images or any(not value for value in descriptions):
        errors.append(f"Expected {exp_images} images with non-empty alternative descriptions, found {len(descriptions)}")
    if len(image_refs) != exp_images:
        errors.append(f"Expected {exp_images} embedded image references, found {len(image_refs)}")
    if "PAGE" not in field_text or "NUMPAGES" not in field_text:
        errors.append("Missing PAGE or NUMPAGES field")
    if update_fields is None or update_fields.get(f"{{{W}}}val", "").lower() not in {"true", "1"}:
        errors.append("Word field refresh on open is not enabled")

    sensitive = [
        value
        for value in ("5plc.local", "tai_lieu_editor", "editor-tai-lieu@5plc.local", "[TÀI LIỆU MẪU]", "Môi trường kiểm chứng")
        if value.lower() in text.lower()
    ]
    if sensitive:
        errors.append(f"Sensitive text remains in document text: {', '.join(sensitive)}")

    print(f"Lesson bookmarks: {len(lesson_bookmarks)}")
    print(f"Quick contents links: {len(anchors)}")
    print(f"Image references with alt text: {len(descriptions)}")
    print("Page fields: PAGE + NUMPAGES")
    print("Sensitive document text: none")
    if errors:
        for error in errors:
            print(f"ERROR: {error}", file=sys.stderr)
        return 1
    print("DOCX structure validation passed")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
