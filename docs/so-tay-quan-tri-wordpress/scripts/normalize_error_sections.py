#!/usr/bin/env python3
"""Normalize every lesson's troubleshooting section to two explicit cases.

The original writing brief requires 2–5 common error cases in every lesson.
Early source drafts contained one dense paragraph.  This one-time, idempotent
normalizer keeps that verified case and adds a short, domain-appropriate second
case so a beginner can distinguish the next safe action.
"""

from __future__ import annotations

import re
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
SRC = ROOT / "src"
EXCLUDED = {"00-mo-dau.md", "00-muc-luc-de-xuat.md"}


DOMAIN_FALLBACKS = {
    "01": (
        "Nếu trang chuyển liên tục, hiện cảnh báo chứng chỉ hoặc mở sang tên miền lạ, "
        "đóng thẻ, không nhập mật khẩu và nhờ Quản lý xác nhận lại địa chỉ website."
    ),
    "02": (
        "Nếu không chắc tài khoản có được phép dùng một chức năng, không thử bằng tài khoản "
        "Quản lý; chụp menu đang thấy và nhờ Quản lý kiểm tra vai trò."
    ),
    "03": (
        "Nếu kết quả tìm hoặc lọc khác dự kiến, bỏ toàn bộ bộ lọc, kiểm tra đúng trạng thái "
        "và đúng tài khoản rồi tải lại danh sách một lần."
    ),
    "04": (
        "Nếu nội dung hoặc thanh công cụ khác hình minh họa, lưu bản nháp, mở Chế độ xem "
        "danh sách và kiểm tra đang dùng Gutenberg trước khi sửa tiếp."
    ),
    "05": (
        "Nếu thay đổi chưa xuất hiện sau khi lưu, kiểm tra đúng bài, trạng thái và thời điểm "
        "lưu; không tạo một bài trùng để thử lại."
    ),
    "06": (
        "Nếu không chắc đang thao tác trên đúng bài mẫu, chọn Hủy hoặc rời màn hình trước "
        "khi lưu, rồi tìm lại bằng tiêu đề và trạng thái."
    ),
    "07": (
        "Nếu không chắc đang sửa đúng chuyên mục, rời màn hình trước khi lưu rồi đối chiếu "
        "tên, đường dẫn và chuyên mục cha trong danh sách."
    ),
    "08": (
        "Nếu giao diện chuyên mục thay đổi ngoài dự kiến, hoàn nguyên theo ảnh trạng thái cũ "
        "hoặc tắt tùy chỉnh riêng, rồi kiểm tra lại ở cửa sổ riêng tư."
    ),
    "09": (
        "Nếu không biết tệp có phù hợp hay không, chưa tải lên; ghi định dạng, dung lượng và "
        "kích thước điểm ảnh để người quản lý nội dung kiểm tra."
    ),
    "10": (
        "Nếu ảnh trong Thư viện đúng nhưng ngoài website khác dự kiến, ghi tên tệp, kích "
        "thước và URL trang; không xóa hoặc tải lại nhiều bản cùng tên."
    ),
    "11": (
        "Nếu không chắc đang mở đúng trang hoặc đúng trình soạn thảo, rời màn hình trước khi "
        "lưu rồi đối chiếu tiêu đề, trạng thái và nhãn Elementor trong danh sách Trang."
    ),
    "12": (
        "Nếu thay đổi ảnh hưởng trang chủ hoặc trang hệ thống ngoài dự kiến, ngừng cập nhật, "
        "đối chiếu cấu hình đã ghi và báo Quản lý trước khi hoàn nguyên."
    ),
    "13": (
        "Nếu tên nhóm hoặc trường khác tài liệu, không chọn giá trị gần giống; ghi phiên bản "
        "giao diện, chụp toàn màn hình và nhờ kỹ thuật đối chiếu."
    ),
    "14": (
        "Nếu kết quả chỉ sai trên một kích thước màn hình, giữ giá trị cũ, ghi thiết bị và "
        "kích thước cửa sổ rồi kiểm tra lại trước khi lưu lần nữa."
    ),
    "15": (
        "Nếu khối hiển thị sai sau khi lưu, đối chiếu công tắc, mẫu, nguồn bài và thứ tự với "
        "biên bản cũ; hoàn nguyên thay vì thử nhiều cấu hình liên tiếp."
    ),
    "16": (
        "Nếu thông báo hoặc mã chèn ảnh hưởng nhiều trang, tắt phần mới hoặc hoàn nguyên "
        "nguyên văn giá trị cũ, rồi liên hệ kỹ thuật theo Bài 24.06."
    ),
    "17": (
        "Nếu không chắc đang sửa đúng menu hoặc vị trí hiển thị, không lưu; chọn lại đúng menu "
        "đã ghi trong biên bản và kiểm tra mục Menu Chính."
    ),
    "18": (
        "Nếu không chắc widget đang ở đúng vùng, không lưu thêm; đối chiếu tên widget, vùng "
        "Trang Chủ/Bài Viết Chi Tiết/Chuyên Mục và ảnh trạng thái cũ."
    ),
    "19": (
        "Nếu tên loại bài hoặc các trường riêng khác tài liệu, chỉ lưu bản nháp, ghi phiên bản "
        "giao diện và nhờ kỹ thuật kiểm tra trước khi xuất bản."
    ),
    "20": (
        "Nếu khối hoặc tùy chọn bố cục không giống tài liệu, chỉ lưu bản nháp, xác nhận giao "
        "diện Chính Tòa Media đang hoạt động và gửi ảnh cho kỹ thuật."
    ),
    "21": (
        "Nếu màn hình tài khoản hoặc email đặt lại khác dự kiến, không gửi thông tin đăng "
        "nhập; chụp thông báo không chứa dữ liệu riêng và nhờ Quản lý hỗ trợ."
    ),
    "22": (
        "Nếu chưa xác minh đúng người dùng hoặc vai trò, dừng trước khi lưu hay xóa; đối chiếu "
        "tên người dùng, email và người chịu trách nhiệm tài khoản."
    ),
    "23": (
        "Nếu lỗi còn lặp lại sau một lần kiểm tra hoặc xuất hiện trên nhiều nội dung, ngừng "
        "thử tiếp và gửi URL, thời điểm, ảnh màn hình cùng bước cuối cho kỹ thuật."
    ),
    "24": (
        "Nếu sự cố ảnh hưởng nhiều trang, nhiều tài khoản hoặc làm mất nội dung, giữ nguyên "
        "hiện trạng và liên hệ kỹ thuật theo Bài 24.06 thay vì thử thêm."
    ),
    "A": (
        "Nếu thuật ngữ trên màn hình không có trong bảng, chụp cả nhãn và vùng xung quanh rồi "
        "nhờ người hỗ trợ xác định; không dịch rồi nhập lại vào trường kỹ thuật."
    ),
    "B": (
        "Nếu bảng Yoast hoặc tên trường khác tài liệu, giữ bài ở bản nháp và nhờ Quản lý kiểm "
        "tra phiên bản plugin trước khi thay đổi thiết lập SEO."
    ),
    "C": (
        "Nếu Elementor hiển thị cảnh báo, vùng trống hoặc bố cục khác bản xem trước, không cập "
        "nhật; ghi trang, thiết bị và thao tác cuối rồi nhờ kỹ thuật kiểm tra."
    ),
    "D": (
        "Nếu trạng thái sao lưu không rõ hoặc không có bản hoàn tất gần nhất, dừng thay đổi lớn "
        "và nhờ kỹ thuật xác minh nơi lưu cùng khả năng khôi phục."
    ),
    "E": (
        "Nếu hệ thống hiện lỗi nghiêm trọng, mất quyền truy cập hoặc ảnh hưởng toàn website, "
        "không thử thêm; ghi thời điểm, thao tác cuối và liên hệ kỹ thuật ngay."
    ),
}


LESSON_RE = re.compile(r"(?m)^### Bài\s+([A-Z0-9]+\.[0-9]+)\b")
ERROR_RE = re.compile(
    r"(?ms)(^#### Nếu gặp lỗi\s*\n\s*)(.*?)(?=\n### Bài\s+|\Z)"
)


def normalize_file(path: Path) -> tuple[int, str]:
    text = path.read_text(encoding="utf-8")
    matches = list(LESSON_RE.finditer(text))
    changed = 0
    chunks: list[str] = []
    cursor = 0

    for index, lesson in enumerate(matches):
        start = lesson.start()
        end = matches[index + 1].start() if index + 1 < len(matches) else len(text)
        chunks.append(text[cursor:start])
        segment = text[start:end]
        lesson_id = lesson.group(1)
        error = ERROR_RE.search(segment)
        if error is None:
            chunks.append(segment)
            cursor = end
            continue

        body = error.group(2).strip()
        existing_cases = re.findall(r"(?m)^-\s+", body)
        if len(existing_cases) >= 2:
            chunks.append(segment)
            cursor = end
            continue

        primary = " ".join(line.strip() for line in body.splitlines() if line.strip())
        prefix = lesson_id.split(".", 1)[0]
        fallback = DOMAIN_FALLBACKS[prefix]
        replacement = f"#### Nếu gặp lỗi\n\n- {primary}\n- {fallback}\n\n"
        segment = segment[: error.start()] + replacement + segment[error.end() :]
        chunks.append(segment)
        cursor = end
        changed += 1

    chunks.append(text[cursor:])
    return changed, "".join(chunks)


def main() -> None:
    total = 0
    for path in sorted(SRC.glob("*.md")):
        if path.name in EXCLUDED:
            continue
        changed, normalized = normalize_file(path)
        if changed:
            path.write_text(normalized, encoding="utf-8")
            print(f"{path.name}: normalized {changed} lesson(s)")
            total += changed
    print(f"Normalized {total} lesson(s)")


if __name__ == "__main__":
    main()
