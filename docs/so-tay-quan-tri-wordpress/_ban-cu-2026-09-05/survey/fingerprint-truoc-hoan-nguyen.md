# Dấu vân tay trạng thái trước hoàn nguyên

Ngày ghi nhận: 05 tháng 09 năm 2026

Các giá trị SHA-256 chuẩn hóa dưới đây được tạo từ dữ liệu WordPress đã tuần tự hóa sau khi sắp khóa đệ quy. Chúng dùng để xác nhận thao tác xóa dữ liệu mẫu không làm đổi nội dung gốc, menu, widget, plugin hoặc tùy chọn theme.

Trạng thái kèm theo:

- `WPLANG`: `vi`.
- Múi giờ: `Asia/Ho_Chi_Minh`.
- Trang chủ tĩnh: ID 8.
- Trang chính sách riêng tư: ID 3.
- `Menu 1`: vị trí `primary`, 5 mục.
- Sidebar trang chủ: `search-2`, `ct_postlist_widget-2`, `ct_postlist_widget-3`.
- Sidebar bài viết: `ct_loichua_card-2`.

Sau khi được xác nhận xóa, phải tạo lại đủ chín checksum bằng cùng thuật toán và đối chiếu từng dòng.

## Bộ dấu vân tay chuẩn hóa để chạy lại

Tệp `scripts/fingerprint_5plc.php` là nguồn duy nhất để tính dấu vân tay trước và sau khi xóa. Tệp chỉ đọc dữ liệu; thuật toán là `SHA-256(serialize(giá trị đã sắp khóa đệ quy))`.

Lần chạy trước hoàn nguyên ngày 05 tháng 09 năm 2026 cho kết quả:

| Phạm vi | SHA-256 chuẩn hóa |
|---|---|
| `ct_settings` | `1f7547f05580321e1d7f5d792f264e9d54aa0553ee8d7dde9c1b343d43892c1d` |
| `sidebars_widgets` | `de91115b535572241a4faeb3f9f4cbd390358f3e68dce7af55fe9fa9ecaebc10` |
| `theme_mods_church` | `0e58f42b8d7f743988c43c3d8ea7411b68c21b80eeab44c0c8c5030904726864` |
| Tám bài/trang gốc | `7827eb84fdadb605397e42de3a795491955df080221277b9b6345befc350fb83` |
| Tám chuyên mục gốc | `55fc251172f214b918d1db182094a7247cdcf1696c02c434c4e2234b9c216dbb` |
| Năm mục của `Menu 1` | `e0644d74c35704b86759205449718b8f9e84753c28c52eed0edb859283afa7e5` |
| Danh sách và trạng thái plugin | `fd81d3d5e2c86f0b61df0fc655cecea35d780863f2b5fabb971f24c1647415a9` |
| Giao diện đang dùng | `20eb1d30ca19ffa30cdd31a54fd6a4c0806a2bd2ef1469b6b97bb28533a61300` |
| Thiết lập được phép giữ lại | `93f8b6dbf6dc623bde71300b099bddb70b51a6f502f223a40d2136340b112764` |

Phạm vi chuẩn hóa cố định gồm bài/trang ID `2, 3, 8, 20, 22, 24, 26, 28`; chuyên mục ID `1, 4, 5, 6, 7, 8, 9, 10`; menu term ID `3`; sáu plugin hiện có; giao diện Chính Tòa Media 1.0.0; ngôn ngữ, múi giờ, trang chủ tĩnh và trang chính sách riêng tư.

Đối chiếu chỉ đọc ngay trước bàn giao ngày 05 tháng 09 năm 2026 cho thấy cả chín giá trị đang chạy trên `5plc` vẫn khớp tuyệt đối với bảng trên.
