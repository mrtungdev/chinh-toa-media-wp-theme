#!/usr/bin/env python3
"""Validate handbook source coverage, lesson structure, and image references."""

from __future__ import annotations

import re
import sys
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
SRC = ROOT / "src"
TOC = SRC / "00-muc-luc-de-xuat.md"
REQUIRED = [
    "Mục đích",
    "Trước khi bắt đầu",
    "Các bước thực hiện",
    "Ảnh minh họa",
    "Kết quả",
    "Lưu ý",
    "Không nên làm",
    "Nếu gặp lỗi",
]

PLACEHOLDER_PATTERNS = [
    r"\bTODO\b",
    r"\bTBD\b",
    r"\[SCREENSHOT\b",
    r"\bLorem ipsum\b",
    r"Cần kiểm tra website thực tế",
]


def lesson_ids(text: str, prefix: str) -> list[str]:
    pattern = rf"^{re.escape(prefix)}Bài\s+([A-Z0-9]+\.[0-9]+)\b"
    return re.findall(pattern, text, flags=re.MULTILINE)


def toc_lessons(text: str) -> dict[str, tuple[str, str, str, str]]:
    lessons: dict[str, tuple[str, str, str, str]] = {}
    pattern = re.compile(
        r"^- Bài\s+([A-Z0-9]+\.[0-9]+)\s+(.+?)\s+—\s+"
        r"(Bắt buộc học|Nên biết|Kỹ thuật dành cho quản trị viên)\s+—\s+"
        r"(🟢|🟡|🔴)\s+—\s+(Ảnh|Không ảnh)$",
        flags=re.MULTILINE,
    )
    for lesson_id, title, audience, safety, image_need in pattern.findall(text):
        lessons[lesson_id] = (title, audience, safety, image_need)
    return lessons


def main() -> int:
    errors: list[str] = []
    toc_text = TOC.read_text(encoding="utf-8")
    expected = lesson_ids(toc_text, "- ")
    expected_details = toc_lessons(toc_text)
    actual: list[str] = []

    chapter_files = sorted(
        path
        for path in SRC.glob("*.md")
        if path.name not in {"00-mo-dau.md", "00-muc-luc-de-xuat.md"}
    )
    for path in chapter_files:
        text = path.read_text(encoding="utf-8")
        ids = lesson_ids(text, "### ")
        actual.extend(ids)

        parts = re.split(r"(?m)^### Bài\s+", text)[1:]
        for part in parts:
            first = part.splitlines()[0]
            lesson_id = first.split(maxsplit=1)[0]
            lesson_title = first.split(maxsplit=1)[1] if " " in first else ""
            expected_detail = expected_details.get(lesson_id)
            if expected_detail:
                expected_title, expected_audience, expected_safety, image_need = expected_detail
                if lesson_title != expected_title:
                    errors.append(
                        f"{path.name} Bài {lesson_id}: tiêu đề khác mục lục "
                        f"('{lesson_title}' != '{expected_title}')"
                    )
            else:
                expected_audience = expected_safety = image_need = ""
            heading_positions: list[int] = []
            for heading in REQUIRED:
                if f"#### {heading}" not in part:
                    errors.append(f"{path.name} Bài {lesson_id}: thiếu '{heading}'")
                else:
                    heading_positions.append(part.index(f"#### {heading}"))
            if len(heading_positions) == len(REQUIRED) and heading_positions != sorted(heading_positions):
                errors.append(f"{path.name} Bài {lesson_id}: thứ tự mục không đúng")

            for index, heading in enumerate(REQUIRED):
                next_heading = REQUIRED[index + 1] if index + 1 < len(REQUIRED) else None
                if next_heading:
                    section_pattern = (
                        rf"(?ms)^#### {re.escape(heading)}\s*\n(.*?)"
                        rf"(?=^#### {re.escape(next_heading)}\s*$)"
                    )
                else:
                    section_pattern = rf"(?ms)^#### {re.escape(heading)}\s*\n(.*)\Z"
                section_match = re.search(section_pattern, part)
                if section_match and not section_match.group(1).strip():
                    errors.append(f"{path.name} Bài {lesson_id}: mục '{heading}' để trống")

            for pattern in PLACEHOLDER_PATTERNS:
                if re.search(pattern, part, flags=re.IGNORECASE):
                    errors.append(
                        f"{path.name} Bài {lesson_id}: còn nội dung tạm khớp /{pattern}/"
                    )
            label_match = re.search(r"(?m)^\*\*Nhãn:\*\*\s+(.+)$", part)
            if not label_match:
                errors.append(f"{path.name} Bài {lesson_id}: thiếu nhãn")
            elif expected_detail:
                label = label_match.group(1)
                if expected_audience not in label or expected_safety not in label:
                    errors.append(f"{path.name} Bài {lesson_id}: nhãn khác mục lục")
            numbered_actions = re.findall(
                r"(?m)^(\d+)\. \*\*(?:(?:Bước (\d+))|([^*]+)):\*\*", part
            )
            if not numbered_actions:
                errors.append(f"{path.name} Bài {lesson_id}: thiếu danh sách bước")
            else:
                list_numbers = [int(value[0]) for value in numbered_actions]
                if list_numbers != list(range(1, len(list_numbers) + 1)):
                    errors.append(f"{path.name} Bài {lesson_id}: số bước không liên tục từ 1")
                for list_number, inner_number, _ in numbered_actions:
                    if inner_number and int(list_number) != int(inner_number):
                        errors.append(
                            f"{path.name} Bài {lesson_id}: nhãn Bước {inner_number} "
                            f"không khớp số danh sách {list_number}"
                        )

            error_match = re.search(
                r"(?ms)^#### Nếu gặp lỗi\s*\n(.*?)(?=^### Bài\s+|\Z)", part
            )
            if error_match:
                error_cases = re.findall(r"(?m)^-\s+\S", error_match.group(1))
                if not 2 <= len(error_cases) <= 5:
                    errors.append(
                        f"{path.name} Bài {lesson_id}: cần 2–5 tình huống lỗi, có {len(error_cases)}"
                    )

            image_section = re.search(
                r"(?ms)^#### Ảnh minh họa\s*\n(.*?)(?=^#### Kết quả\s*$)", part
            )
            if image_section and image_need == "Ảnh":
                image_text = image_section.group(1)
                if not re.search(r"!\[.+?\]\(.+?\)|\bHình\s+[A-Z0-9]", image_text):
                    errors.append(
                        f"{path.name} Bài {lesson_id}: mục lục yêu cầu ảnh nhưng bài không có ảnh/tham chiếu"
                    )

            for direct_image in re.finditer(r"(?m)^!\[.+?\]\(.+?\)\s*$", part):
                following = part[direct_image.end() :].lstrip("\n")
                if not re.match(r"\*Hình\s+.+\*", following):
                    errors.append(f"{path.name} Bài {lesson_id}: ảnh trực tiếp thiếu caption")

        for ref in re.findall(r"\]\((\.\./screenshots/final/[^)]+)\)", text):
            image = (path.parent / ref).resolve()
            if not image.is_file():
                errors.append(f"{path.name}: thiếu ảnh {ref}")

    expected_set = set(expected)
    actual_set = set(actual)
    for missing in sorted(expected_set - actual_set):
        errors.append(f"Thiếu bài {missing}")
    for extra in sorted(actual_set - expected_set):
        errors.append(f"Bài ngoài mục lục {extra}")
    if len(actual) != len(actual_set):
        errors.append("Có mã bài trùng trong nguồn")
    if len(expected_details) != len(expected):
        errors.append("Có dòng bài trong mục lục không đúng định dạng chi tiết")

    print(f"Expected lessons: {len(expected)}")
    print(f"Actual lessons:   {len(actual)}")
    print(f"Source files:     {len(chapter_files)}")
    if errors:
        print("\nValidation errors:")
        for error in errors:
            print(f"- {error}")
        return 1
    print("Validation passed")
    return 0


if __name__ == "__main__":
    sys.exit(main())
