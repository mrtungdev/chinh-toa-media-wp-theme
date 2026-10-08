<?php
/**
 * Dữ liệu mẫu cho site "Hiệp Hội Nữ Tỳ Thừa Sai Thánh Giá" (nutythuasaithanhgia.local).
 * Dùng bởi seed-ntts.php.
 *
 * - Bài theo chuyên mục: [ngày giờ, tiêu đề, tóm tắt, [khối nội dung], [tuỳ chọn]].
 *   Khối nội dung: chuỗi = đoạn văn; ['h' => tiêu đề phụ]; ['list' => [...]];
 *   ['quote' => câu, 'cite' => nguồn]; ['verse' => thơ]; ['note' => true] = dòng "Bài mẫu…".
 *   Tuỳ chọn: 'gallery' => số ảnh (album Media).
 * - Nội dung tự soạn cho bản mẫu. Bài về thông tin riêng của Hiệp hội (lịch sử, cộng đoàn,
 *   tin nội bộ) có dòng ghi chú để thay bằng nội dung chính thức.
 */

return array(
    'site' => array(
        'name'       => 'Hiệp Hội Nữ Tỳ Thừa Sai Thánh Giá',
        'desc'       => 'Đà Nẵng',
        'slogan'     => 'Tôi chỉ hãnh diện về thập giá Đức Kitô',
        'slogan_ref' => 'x. Gl 6,14',
        'email'      => 'lienlac@example.com',
    ),

    'note' => 'Bài mẫu — vui lòng thay bằng nội dung chính thức của Hiệp hội.',

    // Thứ tự = thứ tự menu; chuyên mục cha đứng trước chuyên mục con.
    'categories' => array(
        'hoi-dong'                => array('name' => 'Hội dòng', 'parent' => '', 'desc' => 'Lịch sử, đấng bảo trợ, sứ mạng và cơ cấu của Hiệp hội.'),
        'cac-cong-doan'           => array('name' => 'Các Cộng đoàn', 'parent' => 'hoi-dong', 'desc' => 'Giới thiệu các cộng đoàn và hoạt động tông đồ tại từng nơi.'),
        'linh-dao'                => array('name' => 'Linh đạo', 'parent' => '', 'desc' => 'Linh đạo Thánh Giá và tinh thần thừa sai của Hiệp hội.'),
        'tin-tuc'                 => array('name' => 'Tin Tức', 'parent' => '', 'desc' => 'Tin Giáo Hội và tin hoạt động của Hiệp hội.'),
        'tin-giao-hoi'            => array('name' => 'Tin Giáo Hội', 'parent' => 'tin-tuc', 'desc' => 'Tin tức, lịch phụng vụ và sinh hoạt của Hội Thánh.'),
        'tin-hiep-hoi'            => array('name' => 'Tin Hiệp hội NTTSTG', 'parent' => 'tin-tuc', 'desc' => 'Tin hoạt động của Hiệp hội Nữ Tỳ Thừa Sai Thánh Giá.'),
        'cau-nguyen'              => array('name' => 'Cầu nguyện', 'parent' => '', 'desc' => 'Kinh nguyện, giờ chầu, ý cầu nguyện và các việc đạo đức trong đời sống Hiệp hội.'),
        'on-goi'                  => array('name' => 'Ơn gọi', 'parent' => '', 'desc' => 'Tìm hiểu ơn gọi dâng hiến và hành trình đào tạo.'),
        'tuy-but-chia-se-van-hoa' => array('name' => 'Tuỳ bút/Chia sẻ/Văn Hoá', 'parent' => '', 'desc' => 'Tuỳ bút, chia sẻ, thơ và những nét văn hoá trong đời sống đức tin.'),
        'tu-lieu'                 => array('name' => 'Tư liệu', 'parent' => '', 'desc' => 'Văn kiện Hội Thánh, giáo luật và kinh nguyện dành cho đời sống thánh hiến.'),
        'media'                   => array('name' => 'Media', 'parent' => '', 'desc' => 'Hình ảnh và video sinh hoạt của Hiệp hội.'),
    ),

    // Màu banner ảnh đại diện theo chuyên mục.
    'banners' => array(
        'cau-nguyen'              => array('from' => '#4a1d3f', 'to' => '#8e4a7a', 'glyph' => '☼'),
        'hoi-dong'                => array('from' => '#5a1526', 'to' => '#9b2c48', 'glyph' => '†'),
        'cac-cong-doan'           => array('from' => '#6b4226', 'to' => '#b07a4f', 'glyph' => '⌂'),
        'linh-dao'                => array('from' => '#7a1f35', 'to' => '#c0475f', 'glyph' => '†'),
        'tin-giao-hoi'            => array('from' => '#1f4e5f', 'to' => '#3d8ca3', 'glyph' => '“'),
        'tin-hiep-hoi'            => array('from' => '#8a5a12', 'to' => '#d9a441', 'glyph' => '“'),
        'on-goi'                  => array('from' => '#2f5d50', 'to' => '#5b9a7f', 'glyph' => '♥'),
        'tuy-but-chia-se-van-hoa' => array('from' => '#5e3a6e', 'to' => '#9d6fae', 'glyph' => '¶'),
        'tu-lieu'                 => array('from' => '#3b3a36', 'to' => '#7d786c', 'glyph' => '§'),
        'media'                   => array('from' => '#20253a', 'to' => '#4b5577', 'glyph' => '►'),
    ),
    // Màu xoay vòng cho ảnh trong album Media.
    'gallery_tones' => array(
        array('from' => '#7a1f35', 'to' => '#c0475f', 'glyph' => '†'),
        array('from' => '#8a5a12', 'to' => '#d9a441', 'glyph' => '☼'),
        array('from' => '#2f5d50', 'to' => '#5b9a7f', 'glyph' => '♥'),
        array('from' => '#1f4e5f', 'to' => '#3d8ca3', 'glyph' => '☼'),
        array('from' => '#6b4226', 'to' => '#b07a4f', 'glyph' => '†'),
        array('from' => '#5e3a6e', 'to' => '#9d6fae', 'glyph' => '♥'),
    ),

    // Menu "Liên kết" ở sidebar.
    'links' => array(
        array('title' => 'Tòa Thánh Vatican', 'url' => 'https://www.vatican.va/'),
        array('title' => 'Vatican News tiếng Việt', 'url' => 'https://www.vaticannews.va/vi.html'),
        array('title' => 'Hội đồng Giám mục Việt Nam', 'url' => 'https://hdgmvietnam.com/'),
        array('title' => 'Kinh Thánh – Nhóm CGKPV', 'url' => 'https://ktcgkpv.org/'),
    ),

    // Widget HTML ở sidebar ({icon}, {hoi_dong}, {on_goi}, {lien_lac} được thay bằng URL thật khi seed).
    'widgets' => array(
        'about' => array(
            'title'   => 'Giới thiệu',
            'content' => '<div class="ntts-about"><img src="{icon}" alt="" width="64" height="64">'
                . '<p><strong>Hiệp Hội Nữ Tỳ Thừa Sai Thánh Giá</strong> là cộng đoàn những người nữ dâng hiến đời mình cho Thiên Chúa, sống linh đạo Thánh Giá và tinh thần thừa sai giữa lòng Giáo phận.</p>'
                . '<a class="ntts-btn" href="{hoi_dong}">Tìm hiểu Hội dòng »</a></div>',
        ),
        'vocation' => array(
            'title'   => '',
            'content' => '<div class="ntts-cta"><p class="ntts-cta__kicker">Ơn gọi</p>'
                . '<p class="ntts-cta__title">“Hãy đến mà xem”</p>'
                . '<p>Bạn là thiếu nữ đang tìm hiểu đời sống dâng hiến? Mời bạn đến với sinh hoạt ơn gọi vào Chúa nhật đầu mỗi tháng.</p>'
                . '<a class="ntts-btn ntts-btn--light" href="{on_goi}">Tìm hiểu ơn gọi</a> <a class="ntts-cta__link" href="{lien_lac}">Liên lạc »</a></div>',
        ),
    ),

    'contact' => array(
        'title'   => 'Liên lạc',
        'content' => array(
            'Xin chân thành cảm ơn quý độc giả đã ghé thăm trang thông tin của <strong>Hiệp Hội Nữ Tỳ Thừa Sai Thánh Giá</strong>.',
            '<strong>Địa chỉ:</strong> Nhà Mẹ Hiệp hội, Đà Nẵng (địa chỉ mẫu — vui lòng cập nhật).<br><strong>Điện thoại:</strong> 0236 000 0000 (số mẫu)<br><strong>Email:</strong> <a href="mailto:lienlac@example.com">lienlac@example.com</a> (địa chỉ mẫu)',
            '<strong>Giờ tiếp khách:</strong> sáng 8:00 – 11:00, chiều 14:30 – 17:00 (trừ Chúa nhật và ngày lễ).',
            'Các bạn trẻ muốn tìm hiểu ơn gọi, xin liên lạc với chị phụ trách ơn gọi qua email trên hoặc xem chuyên mục <em>Ơn gọi</em>.',
        ),
    ),

    'posts' => array(
        // ------------------------------------------------------------ Hội dòng
        'hoi-dong' => array(
            array('2026-09-02 08:00', 'Lịch sử hình thành Hiệp hội',
                'Từ một nhóm nhỏ những người nữ khao khát dâng mình cho Chúa và phục vụ người nghèo, Hiệp hội đã từng bước hình thành và lớn lên trong lòng Giáo phận.',
                array(
                    'Như nhiều cộng đoàn thánh hiến khác, Hiệp hội khởi đi từ một nhóm nhỏ những người nữ cùng chung một khát vọng: dâng trọn đời mình cho Thiên Chúa và đem tình thương của Người đến với những người nghèo khổ, bé mọn.',
                    'Những năm đầu, chị em sống đơn sơ trong một ngôi nhà nhỏ, chia sẻ với nhau từng bữa ăn, từng giờ kinh. Ban ngày chị em dạy giáo lý, chăm sóc trẻ em và thăm viếng người bệnh; ban tối cùng nhau quy tụ dưới chân Thánh Giá để cầu nguyện.',
                    array('h' => 'Lớn lên trong lòng Giáo phận'),
                    'Được các vị chủ chăn của Giáo phận nâng đỡ và hướng dẫn, Hiệp hội dần có quy luật sống, chương trình đào tạo và các cộng đoàn tại nhiều nơi. Mỗi bước đi đều là dấu chỉ của Chúa Quan Phòng.',
                    'Hôm nay, chị em tiếp tục hành trình ấy với lòng biết ơn, xác tín rằng chính Thánh Giá Đức Kitô là nguồn sức mạnh và niềm vui của đời dâng hiến.',
                    array('note' => true),
                )),
            array('2026-09-04 08:00', 'Thánh Giá – Đấng bảo trợ và lễ bổn mạng',
                'Danh xưng Thánh Giá nhắc chị em rằng đời dâng hiến là đi theo Đức Kitô chịu đóng đinh. Lễ Suy tôn Thánh Giá (14/9) là ngày lễ bổn mạng của Hiệp hội.',
                array(
                    'Hiệp hội mang danh xưng Thánh Giá như một lời tuyên xưng: Thánh Giá không phải là dấu chỉ thất bại, nhưng là nơi tình yêu Thiên Chúa được tỏ lộ trọn vẹn nhất.',
                    array('quote' => 'Phần tôi, tôi chẳng hãnh diện về điều gì, ngoài thập giá Đức Giêsu Kitô, Chúa chúng ta.', 'cite' => 'x. Gl 6,14'),
                    'Hằng năm, vào ngày 14 tháng 9, lễ Suy tôn Thánh Giá, chị em các cộng đoàn quy tụ về Nhà Mẹ để dâng Thánh lễ tạ ơn, lặp lại lời cam kết dâng hiến và cùng nhau mừng ngày bổn mạng trong niềm vui huynh đệ.',
                    'Bên cạnh Thánh Giá, chị em cũng đặc biệt yêu mến Đức Mẹ Sầu Bi, Đấng đã đứng vững dưới chân Thập Giá, và thánh Têrêsa Hài Đồng Giêsu, bổn mạng các xứ truyền giáo.',
                    array('note' => true),
                )),
            array('2026-09-06 08:00', 'Sứ mạng và hoạt động tông đồ',
                'Cầu nguyện, giáo dục đức tin và phục vụ người nghèo là ba hướng hoạt động chính của chị em tại các cộng đoàn.',
                array(
                    'Là những “nữ tỳ thừa sai”, chị em được mời gọi vừa sống mật thiết với Chúa trong cầu nguyện, vừa ra đi loan báo Tin Mừng bằng chính đời sống phục vụ.',
                    array('h' => 'Các lĩnh vực tông đồ'),
                    array('list' => array(
                        'Giáo dục đức tin: dạy giáo lý thiếu nhi, dự tòng, hôn nhân tại các giáo xứ.',
                        'Giáo dục mầm non và lớp học tình thương cho trẻ em có hoàn cảnh khó khăn.',
                        'Thăm viếng, chăm sóc người già neo đơn và bệnh nhân.',
                        'Đồng hành với các bạn trẻ và sinh viên xa nhà.',
                        'Hỗ trợ mục vụ tại các giáo xứ, giáo họ vùng xa.',
                    )),
                    'Mọi hoạt động đều khởi đi từ Thánh Thể và Thánh Giá, để chị em trở thành chứng nhân khiêm tốn của tình yêu Thiên Chúa giữa đời thường.',
                    array('note' => true),
                )),
            array('2026-09-08 08:00', 'Cơ cấu tổ chức của Hiệp hội',
                'Hiệp hội được tổ chức theo quy luật riêng, gồm Ban Điều hành, các cộng đoàn địa phương và các giai đoạn đào tạo.',
                array(
                    'Đời sống và sứ vụ của Hiệp hội được điều hành theo Quy luật đã được Đấng Bản quyền Giáo phận phê chuẩn.',
                    array('list' => array(
                        'Ban Điều hành: chị Tổng Phụ trách cùng các chị cố vấn, được bầu theo nhiệm kỳ.',
                        'Các cộng đoàn địa phương: mỗi cộng đoàn có một chị phụ trách.',
                        'Ban Đào tạo: đồng hành với các em tìm hiểu, thỉnh sinh, tập sinh và chị em khấn tạm.',
                        'Các ban chuyên trách: tông đồ, truyền thông, kinh tế.',
                    )),
                    'Cơ cấu này nhằm phục vụ đời sống hiệp thông và sứ vụ, để mỗi chị em được nâng đỡ trên hành trình nên thánh.',
                    array('note' => true),
                )),
        ),

        // -------------------------------------------------------- Các Cộng đoàn
        'cac-cong-doan' => array(
            array('2026-09-10 08:00', 'Cộng đoàn Nhà Mẹ',
                'Nơi đào tạo các em thỉnh sinh, tập sinh và là trung tâm sinh hoạt chung của Hiệp hội.',
                array(
                    'Cộng đoàn Nhà Mẹ là nơi các thế hệ chị em được đón nhận và đào tạo, cũng là nơi chị em các cộng đoàn trở về trong những dịp tĩnh tâm, lễ bổn mạng và các kỳ họp chung.',
                    '<strong>Địa chỉ:</strong> Đà Nẵng (địa chỉ mẫu).',
                    array('h' => 'Hoạt động'),
                    array('list' => array('Đào tạo ơn gọi.', 'Tổ chức tĩnh tâm cho giới trẻ.', 'Lớp giáo lý Thêm Sức tại giáo xứ sở tại.')),
                    array('note' => true),
                )),
            array('2026-09-11 08:00', 'Cộng đoàn Thánh Giuse',
                'Chị em phục vụ tại trường mầm non giáo xứ và lớp học tình thương cho trẻ em lao động.',
                array(
                    'Cộng đoàn Thánh Giuse hiện diện giữa một khu dân cư lao động. Mỗi sáng, tiếng cười của các em nhỏ ở trường mầm non giáo xứ là niềm vui của chị em.',
                    '<strong>Địa chỉ:</strong> Đà Nẵng (địa chỉ mẫu).',
                    array('h' => 'Hoạt động'),
                    array('list' => array('Trường mầm non giáo xứ.', 'Lớp học tình thương buổi tối.', 'Giáo lý thiếu nhi Chúa nhật.')),
                    array('note' => true),
                )),
            array('2026-09-12 08:00', 'Cộng đoàn Mân Côi',
                'Cộng đoàn nhỏ tại vùng xa, đồng hành với các gia đình nghèo và người già neo đơn.',
                array(
                    'Giữa vùng đồi núi, ba chị em của cộng đoàn Mân Côi sống đơn sơ như người dân địa phương: trồng rau, nuôi gà và mở cửa đón mọi người đến chia sẻ.',
                    '<strong>Địa chỉ:</strong> (địa chỉ mẫu).',
                    array('h' => 'Hoạt động'),
                    array('list' => array('Thăm viếng người già neo đơn.', 'Hỗ trợ học bổng cho học sinh nghèo.', 'Dạy kinh, dạy giáo lý tại giáo họ.')),
                    array('note' => true),
                )),
            array('2026-09-13 08:00', 'Cộng đoàn Thánh Têrêsa',
                'Chị em đồng hành với sinh viên xa nhà và phục vụ mục vụ bệnh viện.',
                array(
                    'Lấy thánh Têrêsa Hài Đồng Giêsu làm gương mẫu, chị em cộng đoàn sống “con đường thơ ấu thiêng liêng” qua những việc nhỏ bé hằng ngày.',
                    '<strong>Địa chỉ:</strong> Đà Nẵng (địa chỉ mẫu).',
                    array('h' => 'Hoạt động'),
                    array('list' => array('Nhà trọ cho nữ sinh viên.', 'Thăm viếng, cầu nguyện cho bệnh nhân.', 'Sinh hoạt giới trẻ hằng tháng.')),
                    array('note' => true),
                )),
        ),

        // ------------------------------------------------------------ Linh đạo
        'linh-dao' => array(
            array('2026-09-15 07:00', 'Linh đạo Thánh Giá',
                'Nhìn lên Thánh Giá, chị em học biết mình được yêu thương đến tận cùng, và được mời gọi yêu thương như thế.',
                array(
                    'Linh đạo Thánh Giá không tôn vinh đau khổ, nhưng chiêm ngắm một tình yêu dám đi đến cùng. Trên Thập Giá, Đức Giêsu trao ban tất cả: thời gian, sức lực, danh dự và cả mạng sống.',
                    'Người nữ tỳ của Thánh Giá được mời gọi kết hợp những hy sinh nhỏ bé hằng ngày vào hy lễ của Đức Kitô: một nụ cười khi mệt mỏi, một lời tha thứ, một công việc âm thầm không ai biết.',
                    array('quote' => 'Ai muốn theo Thầy, phải từ bỏ chính mình, vác thập giá mình mà theo.', 'cite' => 'x. Mt 16,24'),
                    'Và Thánh Giá luôn dẫn đến Phục Sinh. Vì thế, linh đạo Thánh Giá cũng là linh đạo của niềm vui và hy vọng.',
                )),
            array('2026-09-17 07:00', 'Tinh thần thừa sai',
                'Thừa sai là được sai đi. Mỗi chị em, dù ở đâu, đều mang trong mình lửa truyền giáo của Đức Kitô.',
                array(
                    'Đức Giêsu đến để thắp lửa trên mặt đất. Người thừa sai không giữ ngọn lửa ấy cho riêng mình, nhưng mang đến những nơi lạnh lẽo nhất của cuộc đời.',
                    'Tinh thần thừa sai không chỉ là đi đến những vùng xa xôi, mà trước hết là ra khỏi chính mình, khỏi sự an toàn và thói quen, để gặp gỡ tha nhân.',
                    'Thánh Têrêsa Hài Đồng Giêsu, dù sống trọn đời trong đan viện, vẫn được Hội Thánh tôn phong là bổn mạng các xứ truyền giáo. Ngài nhắc chị em rằng cầu nguyện và hy sinh cũng là truyền giáo.',
                )),
            array('2026-09-19 07:00', 'Ba lời khuyên Phúc Âm: khó nghèo, khiết tịnh, vâng phục',
                'Ba lời khuyên Phúc Âm là con đường để người thánh hiến nên giống Đức Kitô khó nghèo, khiết tịnh và vâng phục.',
                array(
                    array('h' => 'Khó nghèo'),
                    'Sống đơn sơ, chia sẻ của cải và tin tưởng phó thác vào Chúa Quan Phòng. Khó nghèo giúp tâm hồn được tự do để yêu thương.',
                    array('h' => 'Khiết tịnh'),
                    'Dâng trọn trái tim cho Chúa để có thể yêu thương mọi người với tình yêu không chiếm hữu, quảng đại và trong sáng.',
                    array('h' => 'Vâng phục'),
                    'Tìm kiếm và thực thi thánh ý Chúa qua Lời Chúa, Hội Thánh và đời sống cộng đoàn, như Đức Giêsu đã vâng phục cho đến chết trên Thập Giá.',
                )),
            array('2026-09-21 07:00', 'Đời sống cầu nguyện cộng đoàn',
                'Một ngày của chị em được dệt bằng các giờ kinh Phụng vụ, Thánh lễ, giờ chầu và những phút lặng thinh trước Thánh Thể.',
                array(
                    'Cầu nguyện là hơi thở của đời dâng hiến. Không có cầu nguyện, mọi hoạt động tông đồ sẽ trở nên mệt mỏi và trống rỗng.',
                    array('list' => array(
                        'Kinh Sáng và Thánh lễ mở đầu một ngày mới.',
                        'Giờ Kinh Trưa và xét mình ngắn giữa công việc.',
                        'Kinh Chiều, lần hạt Mân Côi và giờ chầu Thánh Thể.',
                        'Kinh Tối, phó dâng một ngày trong tay Chúa.',
                    )),
                    'Mỗi thứ Sáu, chị em cùng nhau suy niệm Đàng Thánh Giá, để luôn ghi nhớ tình yêu cứu độ của Đức Kitô.',
                )),
        ),

        // ---------------------------------------------------------- Cầu nguyện
        'cau-nguyen' => array(
            array('2026-09-03 06:00', 'Giờ chầu Thánh Thể: gợi ý cho một giờ cầu nguyện',
                'Một giờ ở lại với Chúa Giêsu Thánh Thể có thể được chia thành những khoảnh khắc đơn sơ: thờ lạy, lắng nghe, tạ ơn và chuyển cầu.',
                array(
                    'Chầu Thánh Thể là ở lại với Đấng đang ở lại với chúng ta. Không cần nhiều lời; chỉ cần một trái tim hiện diện.',
                    array('list' => array(
                        'Thờ lạy (5 phút): quỳ gối, thinh lặng, nhận ra Chúa đang hiện diện.',
                        'Lắng nghe (20 phút): đọc chậm một đoạn Tin Mừng, dừng lại ở câu chạm đến lòng mình.',
                        'Tạ ơn (10 phút): kể với Chúa những ơn lành trong tuần qua.',
                        'Chuyển cầu (15 phút): dâng lên Chúa Hội Thánh, gia đình, người nghèo, người đau khổ.',
                        'Phó dâng (10 phút): hát một bài thánh ca, xin Chúa chúc lành cho tuần mới.',
                    )),
                    'Mỗi tối thứ Sáu đầu tháng, chị em mở cửa nhà nguyện để các bạn trẻ cùng tham dự giờ chầu.',
                )),
            array('2026-09-18 06:00', 'Suy niệm Đàng Thánh Giá',
                'Mười bốn chặng Đàng Thánh Giá giúp ta đi cùng Đức Giêsu trên con đường tình yêu, từ dinh Philatô đến mồ đá.',
                array(
                    'Đàng Thánh Giá là việc đạo đức quen thuộc của người Công giáo, đặc biệt vào các ngày thứ Sáu. Mỗi chặng là một lời mời gọi dừng lại, nhìn ngắm và để Chúa nói với lòng mình.',
                    array('h' => 'Mười bốn chặng'),
                    array('list' => array(
                        'Chặng I: Đức Chúa Giêsu bị kết án tử.',
                        'Chặng II: Đức Chúa Giêsu vác Thánh Giá.',
                        'Chặng III: Đức Chúa Giêsu ngã xuống lần thứ nhất.',
                        'Chặng IV: Đức Chúa Giêsu gặp Đức Mẹ.',
                        'Chặng V: Ông Simon vác đỡ Thánh Giá.',
                        'Chặng VI: Bà Vêronica lau mặt Đức Chúa Giêsu.',
                        'Chặng VII: Đức Chúa Giêsu ngã xuống lần thứ hai.',
                        'Chặng VIII: Đức Chúa Giêsu gặp các phụ nữ thành Giêrusalem.',
                        'Chặng IX: Đức Chúa Giêsu ngã xuống lần thứ ba.',
                        'Chặng X: Đức Chúa Giêsu bị lột áo.',
                        'Chặng XI: Đức Chúa Giêsu bị đóng đinh vào Thánh Giá.',
                        'Chặng XII: Đức Chúa Giêsu chết trên Thánh Giá.',
                        'Chặng XIII: Xác Đức Chúa Giêsu được tháo xuống.',
                        'Chặng XIV: Xác Đức Chúa Giêsu được mai táng.',
                    )),
                    'Ở mỗi chặng, hãy tự hỏi: hôm nay tôi gặp Đức Giêsu chịu đau khổ nơi ai? Tôi có thể làm gì để trở thành ông Simon, bà Vêronica cho người ấy?',
                )),
            array('2026-09-24 06:00', 'Kinh cầu cho ơn gọi',
                'Lời nguyện xin Chúa ban thêm thợ gặt cho cánh đồng truyền giáo, để chị em và các bạn trẻ cùng đọc mỗi ngày.',
                array(
                    array('quote' => 'Lúa chín đầy đồng, mà thợ gặt lại ít. Vậy anh em hãy xin chủ mùa gặt sai thợ ra gặt lúa về.', 'cite' => 'x. Mt 9,37-38'),
                    'Lạy Chúa Giêsu, Chúa đã gọi các Tông đồ bỏ mọi sự mà theo Chúa. Hôm nay, xin Chúa tiếp tục gọi nhiều bạn trẻ dâng mình phục vụ Chúa và anh chị em.',
                    'Xin cho các gia đình trở thành vườn ươm ơn gọi, nơi con cái được lớn lên trong đức tin và lòng quảng đại.',
                    'Xin ban cho chị em trong Hiệp hội lòng trung tín và niềm vui, để đời sống của chị em trở thành lời mời gọi hấp dẫn cho người trẻ. Amen.',
                )),
            array('2026-10-01 06:00', 'Ý cầu nguyện tháng Mười 2026',
                'Trong tháng Mân Côi và tháng Truyền giáo, chị em xin hiệp ý cầu nguyện cho những ý chỉ sau.',
                array(
                    'Tháng Mười là tháng Mân Côi và cũng là tháng Truyền giáo. Chị em các cộng đoàn mời quý ân nhân và các bạn trẻ hiệp ý cầu nguyện:',
                    array('list' => array(
                        'Cầu cho Hội Thánh và sứ mạng truyền giáo, đặc biệt trong Khánh nhật Truyền giáo 18/10.',
                        'Cầu cho các bạn trẻ đang tìm hiểu ơn gọi.',
                        'Cầu cho các gia đình biết cùng nhau lần hạt Mân Côi.',
                        'Cầu cho người nghèo, người bệnh và người già neo đơn mà chị em đang phục vụ.',
                        'Cầu cho quý ân nhân, thân nhân còn sống cũng như đã qua đời.',
                    )),
                    'Mỗi tối, sau giờ Kinh Chiều, chị em lần một chuỗi Mân Côi theo các ý chỉ trên.',
                    array('note' => true),
                )),
            array('2026-10-07 06:00', 'Lần hạt Mân Côi trong gia đình',
                'Hướng dẫn ngắn để cả nhà cùng lần hạt Mân Côi: các mầu nhiệm theo ngày trong tuần và vài gợi ý cho trẻ em.',
                array(
                    'Chuỗi Mân Côi là lời kinh đơn sơ mà sâu sắc: cùng Mẹ Maria chiêm ngắm cuộc đời Đức Giêsu.',
                    array('h' => 'Các mầu nhiệm theo ngày'),
                    array('list' => array(
                        'Năm sự Vui: thứ Hai và thứ Bảy.',
                        'Năm sự Sáng: thứ Năm.',
                        'Năm sự Thương: thứ Ba và thứ Sáu.',
                        'Năm sự Mừng: thứ Tư và Chúa nhật.',
                    )),
                    array('h' => 'Gợi ý cho gia đình có trẻ nhỏ'),
                    array('list' => array(
                        'Mỗi người trong nhà bắt một chục kinh.',
                        'Trước mỗi chục, đọc một câu Tin Mừng ngắn về mầu nhiệm.',
                        'Dâng mỗi chục cho một người cụ thể: ông bà, bạn bè, người đau ốm.',
                    )),
                )),
        ),

        // -------------------------------------------------------- Tin Giáo Hội
        'tin-giao-hoi' => array(
            array('2026-09-30 09:00', 'Thánh Têrêsa Hài Đồng Giêsu – bổn mạng các xứ truyền giáo',
                'Ngày 1/10, Hội Thánh mừng kính thánh Têrêsa Hài Đồng Giêsu, vị thánh của “con đường thơ ấu thiêng liêng” và là bổn mạng các xứ truyền giáo.',
                array(
                    'Thánh Têrêsa Hài Đồng Giêsu qua đời năm 1897 khi mới 24 tuổi, trong đan viện Cát Minh Lisieux. Ngài chưa bao giờ rời đan viện, nhưng năm 1927 Đức Giáo hoàng Piô XI đã tôn phong ngài làm bổn mạng các xứ truyền giáo.',
                    'Năm 1997, thánh Gioan Phaolô II tuyên phong ngài làm Tiến sĩ Hội Thánh. Con đường thơ ấu thiêng liêng của ngài là làm những việc nhỏ bé với một tình yêu lớn lao.',
                    'Với các cộng đoàn thánh hiến mang tinh thần thừa sai, thánh Têrêsa là một người chị, một người bạn đồng hành gần gũi.',
                )),
            array('2026-10-01 09:00', 'Tháng Mười – tháng Mân Côi',
                'Hội Thánh dành tháng Mười để tôn kính Đức Mẹ Mân Côi. Lễ Đức Mẹ Mân Côi được cử hành ngày 7/10.',
                array(
                    'Lễ Đức Mẹ Mân Côi gắn liền với chiến thắng Lepanto năm 1571, khi Đức Giáo hoàng Piô V kêu gọi toàn thể Kitô hữu lần hạt cầu nguyện. Từ đó, tháng Mười trở thành tháng Mân Côi.',
                    'Chuỗi Mân Côi là bản tóm lược Tin Mừng: chiêm ngắm cuộc đời Đức Giêsu qua đôi mắt của Mẹ Maria trong các mầu nhiệm Vui, Sáng, Thương và Mừng.',
                    'Trong tháng này, các giáo xứ và cộng đoàn tổ chức lần hạt chung, rước kiệu Đức Mẹ, và khuyến khích các gia đình cùng nhau đọc kinh Mân Côi mỗi tối.',
                )),
            array('2026-10-03 09:00', 'Lễ thánh Phanxicô trùng Chúa nhật',
                'Năm 2026, ngày 4/10 rơi vào Chúa nhật XXVII Thường niên, nên lễ nhớ thánh Phanxicô Assisi không được cử hành trong phụng vụ.',
                array(
                    'Theo quy tắc phụng vụ, Chúa nhật Thường niên được ưu tiên hơn các lễ nhớ. Vì thế, năm nay lễ nhớ thánh Phanxicô Assisi (4/10) nhường chỗ cho Chúa nhật XXVII Thường niên.',
                    'Dù vậy, các cộng đoàn vẫn có thể nhắc đến vị thánh của sự khó nghèo và hòa bình trong lời nguyện, giờ kinh hoặc các sinh hoạt ngoài phụng vụ.',
                    'Thánh Phanxicô nhắc mọi người thánh hiến về niềm vui của sự đơn sơ và tình yêu dành cho mọi loài thụ tạo.',
                )),
            array('2026-10-06 09:00', 'Khánh nhật Truyền giáo 18/10/2026',
                'Chúa nhật áp chót tháng Mười là Khánh nhật Truyền giáo. Năm 2026, Khánh nhật được cử hành ngày 18/10.',
                array(
                    'Khánh nhật Truyền giáo được Đức Giáo hoàng Piô XI thiết lập năm 1926. Năm 2026 đánh dấu tròn một thế kỷ Hội Thánh hoàn vũ dành riêng một ngày cầu nguyện và chia sẻ cho sứ mạng truyền giáo.',
                    'Vào ngày này, tiền quyên góp tại các nhà thờ trên toàn thế giới được chuyển về các Hội Giáo hoàng Truyền giáo để nâng đỡ các Giáo hội non trẻ.',
                    'Mỗi tín hữu được mời gọi trở thành người loan báo Tin Mừng bằng lời cầu nguyện, sự hy sinh và chứng tá đời sống.',
                )),
            array('2026-10-08 09:00', 'Chuẩn bị tháng Các Linh hồn',
                'Tháng Mười Một, Hội Thánh mời gọi tín hữu tưởng nhớ và cầu nguyện cho những người đã qua đời.',
                array(
                    'Ngày 1/11, Hội Thánh mừng lễ Các Thánh; ngày 2/11 là lễ Cầu cho các tín hữu đã qua đời. Cả tháng Mười Một được dành để cầu nguyện cho các linh hồn.',
                    'Từ ngày 1 đến ngày 8/11, tín hữu có thể lãnh ơn toàn xá cho các linh hồn khi viếng nghĩa trang và cầu nguyện cho người đã qua đời, với các điều kiện thông thường: xưng tội, rước lễ và cầu nguyện theo ý Đức Giáo hoàng.',
                    'Các giáo xứ thường tổ chức Thánh lễ tại nghĩa trang, đọc kinh cầu hồn và ghi danh xin lễ cho ông bà, cha mẹ, thân nhân.',
                )),
        ),

        // ------------------------------------------------------- Tin Hiệp hội
        'tin-hiep-hoi' => array(
            array('2026-08-20 16:00', 'Tĩnh huấn thường niên 2026',
                'Chị em các cộng đoàn quy tụ về Nhà Mẹ cho tuần tĩnh huấn thường niên với chủ đề “Đứng dưới chân Thập Giá”.',
                array(
                    'Trong bầu khí thinh lặng và cầu nguyện, chị em đã cùng nhau suy niệm về hình ảnh Đức Maria đứng dưới chân Thập Giá, mẫu gương của sự hiện diện trung tín và âm thầm.',
                    'Mỗi ngày tĩnh huấn gồm các bài huấn đức, giờ chầu Thánh Thể, Bí tích Hòa Giải và những giờ chia sẻ trong nhóm nhỏ.',
                    'Khép lại tuần tĩnh huấn, chị em lặp lại lời cam kết dâng hiến trong Thánh lễ tạ ơn, rồi trở về cộng đoàn với tâm hồn được canh tân.',
                    array('note' => true),
                )),
            array('2026-09-07 16:00', 'Khai giảng năm học giáo lý 2026–2027',
                'Các lớp giáo lý do chị em phụ trách tại các giáo xứ đã chính thức khai giảng năm học mới.',
                array(
                    'Sáng Chúa nhật, tại các giáo xứ có chị em phục vụ, các em thiếu nhi đã tựu trường năm học giáo lý mới trong niềm vui và háo hức.',
                    'Năm nay, chị em tiếp tục dạy các lớp Khai tâm, Rước lễ lần đầu, Thêm sức và Bao đồng, đồng thời mở thêm lớp giáo lý dự tòng cho người lớn.',
                    'Xin quý ân nhân và phụ huynh cầu nguyện cho các giáo lý viên và các em thiếu nhi trong năm học mới.',
                    array('note' => true),
                )),
            array('2026-09-14 18:00', 'Mừng lễ bổn mạng Suy tôn Thánh Giá',
                'Ngày 14/9, chị em các cộng đoàn cùng ân nhân và thân nhân đã quy tụ về Nhà Mẹ mừng lễ bổn mạng Hiệp hội.',
                array(
                    'Thánh lễ tạ ơn được cử hành trang trọng lúc 9 giờ sáng. Trong bài giảng, vị chủ tế nhắc chị em rằng Thánh Giá là nơi tình yêu được tỏ lộ, và người nữ tỳ của Thánh Giá được mời gọi trở thành dấu chỉ của tình yêu ấy giữa đời.',
                    'Sau Thánh lễ là bữa cơm thân mật và chương trình văn nghệ do các em tập sinh thực hiện.',
                    'Hiệp hội chân thành cảm ơn quý cha, quý ân nhân và gia đình đã hiện diện, cầu nguyện và nâng đỡ chị em.',
                    array('note' => true),
                )),
            array('2026-09-26 16:00', 'Trung thu yêu thương cho thiếu nhi vùng xa',
                'Nhân dịp Tết Trung thu, chị em đã tổ chức đêm hội trăng rằm và trao quà cho các em thiếu nhi có hoàn cảnh khó khăn.',
                array(
                    'Với sự giúp đỡ của các ân nhân, chị em đã chuẩn bị những phần quà gồm bánh trung thu, sữa, tập vở và lồng đèn cho các em thiếu nhi tại giáo họ vùng xa.',
                    'Đêm hội có rước đèn, múa lân, trò chơi dân gian và phần kể chuyện Kinh Thánh. Nụ cười rạng rỡ của các em là món quà quý giá nhất.',
                    array('note' => true),
                )),
            array('2026-10-02 16:00', 'Giờ chầu mở đầu tháng Mân Côi',
                'Chị em cùng giới trẻ giáo xứ đã có giờ chầu Thánh Thể và lần hạt Mân Côi mở đầu tháng Mười.',
                array(
                    'Tối thứ Sáu đầu tháng, nhà nguyện Nhà Mẹ chật kín các bạn trẻ đến cùng chị em chầu Thánh Thể và lần hạt Mân Côi.',
                    'Mỗi chục kinh được dâng cho một ý chỉ: cho Hội Thánh, cho các gia đình, cho ơn gọi, cho người nghèo và cho các linh hồn.',
                    'Chương trình sẽ được tổ chức vào tối thứ Sáu đầu mỗi tháng. Mời các bạn trẻ đến tham dự.',
                    array('note' => true),
                )),
        ),

        // -------------------------------------------------------------- Ơn gọi
        'on-goi' => array(
            array('2026-09-16 19:00', 'Ơn gọi là gì?',
                'Ơn gọi không phải là một kế hoạch ta tự vạch ra, nhưng là lời mời gọi của Thiên Chúa và câu trả lời tự do của ta.',
                array(
                    'Mỗi người đều có một ơn gọi: được gọi vào hiện hữu, được gọi làm con Thiên Chúa, và được gọi để yêu thương theo một bậc sống cụ thể.',
                    'Ơn gọi dâng hiến là lời mời theo Đức Kitô cách triệt để hơn qua ba lời khuyên Phúc Âm, để thuộc trọn về Chúa và phục vụ anh chị em.',
                    array('h' => 'Làm sao nhận ra ơn gọi?'),
                    array('list' => array(
                        'Cầu nguyện và lắng nghe Lời Chúa mỗi ngày.',
                        'Để ý những khao khát sâu xa nhất trong lòng mình.',
                        'Tìm một người đồng hành thiêng liêng.',
                        'Can đảm “đến mà xem” một cộng đoàn.',
                    )),
                )),
            array('2026-09-23 19:00', 'Hành trình đào tạo của một nữ tu',
                'Từ khi tìm hiểu đến khi khấn trọn đời là một hành trình nhiều năm, được đồng hành từng bước.',
                array(
                    'Đào tạo là một hành trình biến đổi cả con người: trí tuệ, tâm hồn và đời sống thiêng liêng, để ngày càng nên giống Đức Kitô.',
                    array('list' => array(
                        'Tìm hiểu: các bạn trẻ tham gia sinh hoạt hằng tháng, tìm hiểu đời sống cộng đoàn.',
                        'Thỉnh sinh: sống trong cộng đoàn, học hỏi và phân định ơn gọi.',
                        'Tập sinh: thời gian đặc biệt dành cho cầu nguyện, học hỏi linh đạo và quy luật.',
                        'Khấn tạm: bắt đầu sống ba lời khuyên Phúc Âm và tham gia sứ vụ.',
                        'Khấn trọn: dâng hiến trọn đời cho Thiên Chúa.',
                    )),
                    array('note' => true),
                )),
            array('2026-09-29 19:00', 'Chương trình tìm hiểu ơn gọi 2026–2027',
                'Các bạn nữ từ 18 đến 28 tuổi muốn tìm hiểu đời sống dâng hiến được mời tham gia sinh hoạt ơn gọi hằng tháng.',
                array(
                    '<strong>Thời gian:</strong> Chúa nhật thứ nhất mỗi tháng, từ 8:00 đến 15:00.',
                    '<strong>Địa điểm:</strong> Nhà Mẹ Hiệp hội (xem trang Liên lạc).',
                    '<strong>Nội dung:</strong> cầu nguyện, chia sẻ Lời Chúa, tìm hiểu linh đạo Thánh Giá, gặp gỡ chị em và sinh hoạt vui tươi.',
                    'Các bạn có thể đăng ký qua email hoặc gặp trực tiếp chị phụ trách ơn gọi.',
                    array('note' => true),
                )),
            array('2026-10-05 19:00', 'Chứng từ: Tiếng gọi giữa đời thường',
                'Một chị khấn tạm chia sẻ hành trình nhận ra tiếng Chúa gọi giữa những bận rộn của đời sinh viên.',
                array(
                    'Ngày còn là sinh viên, tôi nghĩ ơn gọi là chuyện của những người đặc biệt. Tôi chỉ là một cô gái bình thường, bận rộn với bài vở và công việc làm thêm.',
                    'Một lần đi dạy lớp học tình thương cùng các chị, tôi bắt gặp niềm vui rất lạ nơi các chị khi ngồi giữa đám trẻ lem luốc. Niềm vui ấy cứ đeo đuổi tôi mãi.',
                    'Tôi bắt đầu đến nhà thờ mỗi sáng, rồi tham gia sinh hoạt ơn gọi. Từng bước, tôi nhận ra Chúa không gọi những người hoàn hảo; Người làm cho những người Người gọi trở nên phù hợp.',
                    array('note' => true),
                )),
        ),

        // ------------------------------------------- Tuỳ bút/Chia sẻ/Văn Hoá
        'tuy-but-chia-se-van-hoa' => array(
            array('2026-09-18 20:00', 'Chuỗi hạt của mẹ',
                'Chuỗi hạt cũ đã mòn hạt của mẹ là bài giáo lý đầu tiên và sâu sắc nhất mà tôi từng được học.',
                array(
                    'Chuỗi hạt của mẹ tôi đã sờn dây, vài hạt bị mòn đến mất cả màu gỗ. Mẹ không học nhiều, nhưng mẹ thuộc lòng từng mầu nhiệm.',
                    'Những tối mất điện ở quê, cả nhà ngồi quanh ngọn đèn dầu, mẹ bắt kinh, chúng tôi thưa. Có đứa ngủ gật, mẹ vẫn kiên nhẫn đọc tiếp, như thể chuỗi kinh ấy đủ sức che chở cả gia đình qua những ngày khó.',
                    'Bây giờ mẹ đã già, tay run không còn lần hạt nhanh như trước. Tôi cầm chuỗi hạt ấy trong tay mỗi khi cầu nguyện, và hiểu rằng đức tin được truyền lại không phải bằng sách vở, mà bằng những bàn tay kiên trì như thế.',
                )),
            array('2026-09-25 20:00', 'Một ngày ở lớp học tình thương',
                'Những đứa trẻ bán vé số buổi sáng, đến lớp buổi tối với đôi mắt sáng ngời. Tôi đã học được ở các em nhiều hơn những gì tôi dạy.',
                array(
                    'Lớp học chỉ có mười hai em, đứa lớn nhất mười bốn tuổi, đứa nhỏ nhất mới lên bảy. Buổi sáng các em đi bán vé số, nhặt ve chai; buổi tối các em đến lớp với đôi chân còn lấm bụi.',
                    'Có hôm bé Na mang đến một cái bánh nhỏ, bẻ đôi mời tôi. “Cô ăn đi, con ăn rồi.” Tôi biết em chưa ăn gì từ trưa.',
                    'Chúa đã dạy tôi về sự chia sẻ qua đôi bàn tay bé nhỏ ấy. Tôi đến để dạy các em, nhưng chính các em đang dạy tôi Tin Mừng.',
                )),
            array('2026-10-02 20:00', 'Tà áo dài trong phụng vụ',
                'Tà áo dài trong các Thánh lễ trọng thể là một nét đẹp hội nhập văn hoá của người Công giáo Việt Nam.',
                array(
                    'Trong những Thánh lễ trọng thể, hình ảnh các thiếu nữ trong tà áo dài dâng hoa, rước kiệu đã trở nên quen thuộc với người Công giáo Việt Nam.',
                    'Tà áo dài vừa kín đáo, vừa duyên dáng, thể hiện sự trang trọng và lòng tôn kính khi đến trước nhan Chúa. Đó cũng là cách người Việt đem nét đẹp của dân tộc mình dâng lên Thiên Chúa.',
                    'Hội nhập văn hoá không phải là thay đổi Tin Mừng, mà là để Tin Mừng mặc lấy những gì đẹp nhất của mỗi dân tộc.',
                )),
            array('2026-10-07 20:00', 'Thơ: Dưới chân Thập Giá',
                'Bài thơ ngắn dâng lên Đức Mẹ Sầu Bi, người đã đứng vững dưới chân Thập Giá.',
                array(
                    array('verse' => "Chiều Gôngôta lặng im gió thổi,\nMẹ đứng đây, chẳng nói một lời,\nNhìn Con treo giữa đất trời,\nTim Mẹ thắt lại, mà môi vẫn cầu.\n\nCon ở lại dưới chân Thập Giá,\nHọc nơi Mẹ cách yêu không ngừng,\nĐể mai giữa những gian truân,\nCon thành nữ tỳ ân cần của Con."),
                    'Bài thơ tự soạn cho bản mẫu.',
                )),
        ),

        // ------------------------------------------------------------- Tư liệu
        'tu-lieu' => array(
            array('2026-09-09 10:00', 'Hiến chế Lumen Gentium – Chương VI: Các Tu sĩ',
                'Tóm lược chương VI (số 43–47) của Hiến chế Tín lý về Hội Thánh, nói về đời sống tu trì trong Hội Thánh.',
                array(
                    'Hiến chế Tín lý về Hội Thánh <em>Lumen Gentium</em> của Công đồng Vaticanô II dành chương VI (số 43–47) để nói về các tu sĩ.',
                    array('list' => array(
                        'Các lời khuyên Phúc Âm là hồng ân Thiên Chúa ban cho Hội Thánh.',
                        'Đời sống tu trì là dấu chỉ báo trước những thực tại mai hậu.',
                        'Hội Thánh có thẩm quyền điều hành việc thực hành các lời khuyên Phúc Âm.',
                        'Các tu sĩ góp phần vào sự thánh thiện và sứ mạng của toàn thể Hội Thánh.',
                    )),
                    'Chương này nhấn mạnh rằng đời sống tu trì không xa lạ với con người, nhưng góp phần xây dựng thế giới cách sâu xa hơn.',
                )),
            array('2026-09-12 10:00', 'Tông huấn Vita Consecrata (1996)',
                'Tông huấn hậu Thượng Hội đồng về Đời sống Thánh hiến của Đức Giáo hoàng Gioan Phaolô II, ban hành ngày 25/3/1996.',
                array(
                    'Tông huấn <em>Vita Consecrata</em> được ban hành sau Thượng Hội đồng Giám mục năm 1994 về đời sống thánh hiến.',
                    array('h' => 'Ba phần chính'),
                    array('list' => array(
                        'Confessio Trinitatis: nguồn gốc Ba Ngôi của đời sống thánh hiến.',
                        'Signum fraternitatis: đời sống thánh hiến là dấu chỉ hiệp thông trong Hội Thánh.',
                        'Servitium caritatis: đời sống thánh hiến là sự biểu lộ tình yêu Thiên Chúa trong thế giới.',
                    )),
                    'Tông huấn là tài liệu nền tảng cho việc đào tạo và canh tân đời sống thánh hiến ngày nay.',
                )),
            array('2026-09-20 10:00', 'Giáo luật về đời sống thánh hiến (Đ. 573–746)',
                'Bộ Giáo luật 1983 dành phần III của quyển II để quy định về các tu hội đời sống thánh hiến và tu đoàn đời sống tông đồ.',
                array(
                    'Phần III quyển II Bộ Giáo luật 1983 (từ điều 573 đến điều 746) quy định về các tu hội đời sống thánh hiến và các tu đoàn đời sống tông đồ.',
                    array('list' => array(
                        'Đ. 573–606: các quy tắc chung cho mọi tu hội đời sống thánh hiến.',
                        'Đ. 607–709: các hội dòng.',
                        'Đ. 710–730: các tu hội đời.',
                        'Đ. 731–746: các tu đoàn đời sống tông đồ.',
                    )),
                    'Hiểu biết giáo luật giúp các cộng đoàn sống trung thành với đặc sủng trong sự hiệp thông với Hội Thánh.',
                )),
            array('2026-09-27 10:00', 'Kinh dâng mình cho Thánh Giá',
                'Lời kinh dâng mình mẫu để chị em và các bạn trẻ đọc trong giờ cầu nguyện chung hoặc riêng.',
                array(
                    'Lạy Chúa Giêsu chịu đóng đinh, con quỳ dưới chân Thánh Giá Chúa, xin dâng trọn cuộc đời con cho Chúa.',
                    'Xin cho con biết nhìn lên Thánh Giá mỗi khi mệt mỏi, để nhận ra Chúa đã yêu con đến tận cùng. Xin cho con biết vác thập giá mỗi ngày với lòng tin yêu và phó thác.',
                    'Lạy Mẹ Maria, Mẹ đã đứng vững dưới chân Thập Giá, xin dạy con biết trung thành và âm thầm phục vụ như Mẹ. Amen.',
                    array('note' => true),
                )),
        ),

        // --------------------------------------------------------------- Media
        'media' => array(
            array('2026-08-22 20:00', 'Album: Tĩnh huấn thường niên 2026',
                'Hình ảnh tuần tĩnh huấn thường niên của chị em tại Nhà Mẹ.',
                array(
                    'Một số hình ảnh trong tuần tĩnh huấn: giờ chầu, huấn đức và Thánh lễ bế mạc.',
                ),
                array('gallery' => 6)),
            array('2026-09-15 20:00', 'Album: Lễ bổn mạng Suy tôn Thánh Giá 2026',
                'Một số hình ảnh Thánh lễ và sinh hoạt mừng lễ bổn mạng Hiệp hội ngày 14/9/2026.',
                array(
                    'Một số hình ảnh trong ngày lễ bổn mạng Suy tôn Thánh Giá tại Nhà Mẹ. Bấm vào ảnh để xem cỡ lớn.',
                ),
                array('gallery' => 6)),
            array('2026-09-27 20:00', 'Album: Trung thu yêu thương 2026',
                'Hình ảnh đêm hội trăng rằm và trao quà Trung thu cho thiếu nhi vùng xa.',
                array(
                    'Đêm hội Trung thu với rước đèn, trò chơi dân gian và những phần quà nhỏ cho các em thiếu nhi.',
                ),
                array('gallery' => 6)),
            array('2026-10-03 20:00', 'Album: Giờ chầu mở đầu tháng Mân Côi',
                'Hình ảnh giờ chầu Thánh Thể và lần hạt Mân Côi cùng giới trẻ tối thứ Sáu đầu tháng Mười.',
                array(
                    'Một số hình ảnh giờ chầu và lần hạt Mân Côi cùng các bạn trẻ.',
                    'Để đăng video: tạo bài mới trong chuyên mục Media, ở hộp “Phân loại bài viết” chọn “Video (audio) Lời Chúa” rồi dán link YouTube/Vimeo vào ô “Link video/audio”.',
                ),
                array('gallery' => 6)),
        ),
    ),
);
