<?php
/**
 * Dữ liệu mẫu cho site "5 phút cho Lời Chúa" (5plc.local). Dùng bởi seed-5plc.php.
 *
 * - 'daily': mỗi ngày 1 bài Suy niệm, 14/9 → 31/10/2026 (Chúa nhật năm A, ngày thường
 *   năm chẵn). Tên ngày phụng vụ ("Thứ Hai Tuần 27 TN") được TÍNH từ ngày trong seed,
 *   ở đây chỉ ghi lễ/kính thánh, đoạn Tin Mừng và nội dung.
 * - Câu Lời Chúa trích rất ngắn, diễn ý; phần suy niệm là nội dung tự soạn cho bản mẫu.
 */

return array(
    'categories' => array(
        'suy-niem'           => array('name' => 'Suy niệm', 'desc' => 'Mỗi ngày năm phút suy niệm Tin Mừng của ngày theo lịch phụng vụ.'),
        'tu-vung'            => array('name' => 'Từ vựng', 'desc' => 'Giải thích ngắn gọn các từ ngữ thường gặp trong đời sống đức tin Công giáo.'),
        'giao-ly'            => array('name' => 'Giáo lý', 'desc' => 'Những bài giáo lý căn bản, dễ hiểu cho mọi lứa tuổi.'),
        'vui-hoc-kinh-thanh' => array('name' => 'Vui học Kinh Thánh', 'desc' => 'Đố vui, trắc nghiệm giúp làm quen với Kinh Thánh.'),
        'huan-quyen'         => array('name' => 'Huấn quyền', 'desc' => 'Tóm lược các văn kiện của Công đồng và các Đức Giáo hoàng.'),
    ),

    // Màu banner ảnh đại diện theo chuyên mục (bài Suy niệm dùng ảnh cuộn giấy da).
    'banners' => array(
        'tu-vung'            => array('from' => '#0f5e63', 'to' => '#23a3a8', 'glyph' => 'Aa'),
        'giao-ly'            => array('from' => '#7a1626', 'to' => '#c43d4f', 'glyph' => '†'),
        'vui-hoc-kinh-thanh' => array('from' => '#a65c00', 'to' => '#e59a1c', 'glyph' => '?'),
        'huan-quyen'         => array('from' => '#2c2470', 'to' => '#5d4fc4', 'glyph' => '“'),
    ),

    'links' => array(
        array('title' => 'Tòa Thánh Vatican', 'url' => 'https://www.vatican.va/'),
        array('title' => 'Vatican News tiếng Việt', 'url' => 'https://www.vaticannews.va/vi.html'),
        array('title' => 'Hội đồng Giám mục Việt Nam', 'url' => 'https://hdgmvietnam.com/'),
        array('title' => 'Tổng Giáo phận Sài Gòn', 'url' => 'https://tgpsaigon.net/'),
        array('title' => 'Kinh Thánh – Nhóm CGKPV', 'url' => 'https://ktcgkpv.org/'),
    ),

    'contact' => array(
        'title'   => 'Liên hệ',
        'content' => array(
            'Cảm ơn bạn đã ghé thăm <strong>5 phút cho Lời Chúa</strong>. Mỗi ngày, chúng tôi chia sẻ một bài suy niệm ngắn về Tin Mừng của ngày, cùng các bài từ vựng, giáo lý và vui học Kinh Thánh.',
            'Mọi góp ý về nội dung, đề nghị cộng tác hoặc báo lỗi, xin gửi về địa chỉ: <a href="mailto:lienhe@example.com">lienhe@example.com</a> (địa chỉ mẫu, vui lòng thay bằng email thật).',
            'Nếu bạn muốn nhận bài suy niệm mỗi ngày, hãy lưu trang này vào màn hình chính của điện thoại hoặc theo dõi chuyên mục <em>Suy niệm</em>.',
        ),
    ),

    // ----------------------------------------------------------------- daily
    // date => [saint, gospel, title, quote, cite, excerpt, [paragraphs], prayer]
    'daily' => array(
        '2026-09-14' => array('Lễ Suy Tôn Thánh Giá – lễ kính', 'Ga 3,13-17', 'Ánh mắt hướng về Thập Giá',
            'Thiên Chúa đã yêu thế gian đến nỗi ban Con Một của Người.', 'Ga 3,16',
            'Thập giá không phải là dấu chỉ thất bại, nhưng là nơi tình yêu Thiên Chúa tỏ lộ trọn vẹn nhất. Nhìn lên Thập Giá, ta học biết mình được yêu đến mức nào.',
            array(
                'Người Do Thái trong sa mạc được chữa lành khi nhìn lên con rắn đồng ông Môsê treo trên cây sào. Đức Giêsu dùng hình ảnh ấy để nói về chính mình: Người sẽ được giương cao, và ai tin vào Người thì được sống. Thập giá, dụng cụ hành hình đáng sợ nhất thời ấy, đã trở thành cây sự sống.',
                'Suy tôn Thánh Giá không phải là tôn vinh đau khổ, mà là tôn vinh một tình yêu dám đi đến cùng. Mỗi khi làm dấu Thánh Giá, ta tuyên xưng rằng không có gánh nặng nào của mình nằm ngoài vòng tay của Đấng đã chịu đóng đinh.',
            ),
            'Lạy Chúa Giêsu, xin cho con biết ngước nhìn lên Thánh Giá mỗi khi lòng con mỏi mệt, để nhận ra tình yêu Chúa vẫn đang nâng đỡ con. Amen.'),

        '2026-09-15' => array('Đức Mẹ Sầu Bi – lễ nhớ', 'Ga 19,25-27', 'Đứng gần Thập Giá',
            'Đứng gần thập giá Đức Giêsu, có thân mẫu Người.', 'Ga 19,25',
            'Mẹ Maria không thể cất đi nỗi đau của Con, nhưng Mẹ đã chọn ở lại. Sự hiện diện âm thầm ấy là bài học về lòng trung tín.',
            array(
                'Khi các môn đệ tản mát, Mẹ vẫn đứng đó. Tin Mừng không ghi lại lời nào của Mẹ dưới chân Thập Giá, chỉ ghi lại sự hiện diện. Có những lúc yêu thương nghĩa là không nói gì cả, chỉ ở lại bên người đang đau khổ.',
                'Từ trên cao, Đức Giêsu trao Mẹ cho người môn đệ Người thương mến, và trao người môn đệ cho Mẹ. Từ giờ ấy, Hội Thánh có một người Mẹ. Ta cũng được mời đón Mẹ về nhà mình, để Mẹ dạy ta cách đứng vững trước những thập giá của cuộc đời.',
            ),
            'Lạy Mẹ Sầu Bi, xin dạy con biết ở lại bên những người đang đau khổ, dù con không có lời nào để nói. Amen.'),

        '2026-09-16' => array('Th. Cornêliô, giáo hoàng, và Th. Cyprianô, giám mục, tử đạo', 'Lc 7,31-35', 'Thổi sáo mà không nhảy múa',
            'Đức Khôn Ngoan được tất cả con cái mình làm chứng.', 'Lc 7,35',
            'Người đời chê Gioan khắc khổ, lại chê Đức Giêsu dễ dãi. Khi lòng đã đóng, lý do nào cũng có thể trở thành cớ để từ chối.',
            array(
                'Đức Giêsu so sánh thế hệ của Người với lũ trẻ ngồi ngoài chợ, chơi trò gì cũng không vừa ý. Gioan ăn chay thì bị nói là bị quỷ ám; Con Người ăn uống thì bị gọi là phàm ăn. Vấn đề không nằm ở người rao giảng, mà ở trái tim không muốn đổi thay.',
                'Ta cũng có thể như vậy với Lời Chúa: chọn nghe điều hợp ý, gạt đi điều làm mình khó chịu. Hai thánh Cornêliô và Cyprianô đã sống trong thời bách hại và chia rẽ, nhưng các ngài giữ cho mình một tấm lòng mở ra trước sự thật, dù phải trả giá bằng mạng sống.',
            ),
            'Lạy Chúa, xin cho con đừng tìm cớ để né tránh Lời Chúa, nhưng biết mở lòng đón nhận cả những lời làm con phải đổi mới. Amen.'),

        '2026-09-17' => array('Th. Rôbertô Bellarminô, giám mục, tiến sĩ Hội Thánh', 'Lc 7,36-50', 'Yêu nhiều vì được tha nhiều',
            'Tội của chị rất nhiều, nhưng đã được tha, vì chị đã yêu mến nhiều.', 'Lc 7,47',
            'Người phụ nữ tội lỗi không nói một lời, chỉ khóc và xức dầu chân Chúa. Lòng biết ơn của người được tha thứ trở thành tình yêu không tính toán.',
            array(
                'Ông Simôn mời Đức Giêsu dùng bữa nhưng không rửa chân, không chào hôn, không xức dầu cho khách. Còn người phụ nữ bị cả thành coi là tội lỗi lại làm tất cả những điều ấy bằng nước mắt và mái tóc của mình. Ai thấy mình không cần được tha, người ấy cũng khó yêu mến.',
                'Tình yêu dành cho Chúa lớn lên khi ta nhận ra mình đã được thương xót biết bao. Bí tích Hòa Giải không phải là gánh nặng, mà là nơi ta được nghe lại lời Chúa nói với người phụ nữ: "Lòng tin của chị đã cứu chị. Chị hãy đi bình an."',
            ),
            'Lạy Chúa, xin cho con nhận ra lòng thương xót Chúa dành cho con, để con biết yêu mến Chúa nhiều hơn. Amen.'),

        '2026-09-18' => array('', 'Lc 8,1-3', 'Những người phụ nữ đi theo Chúa',
            'Các bà đã lấy của cải mình mà giúp đỡ Đức Giêsu và các môn đệ.', 'Lc 8,3',
            'Bên cạnh Nhóm Mười Hai còn có những người phụ nữ âm thầm phục vụ. Sứ vụ của Đức Giêsu được nâng đỡ bởi những tấm lòng quảng đại không tên tuổi.',
            array(
                'Thánh Luca kể tên bà Maria Mácđala, bà Gioanna, bà Susanna và nhiều bà khác. Các bà đã được Chúa chữa lành, và từ đó các bà đi theo Người, dùng của cải mình để phục vụ. Tin Mừng không chỉ được loan báo bằng lời giảng, mà còn bằng những bữa cơm, những chuyến đi được lo liệu chu đáo.',
                'Trong mỗi giáo xứ hôm nay cũng có những người như thế: người lau dọn nhà thờ, người nấu ăn cho các buổi sinh hoạt, người lặng lẽ đóng góp. Chúa thấy và ghi nhớ từng việc nhỏ ấy. Phục vụ âm thầm cũng là một cách loan báo Tin Mừng.',
            ),
            'Lạy Chúa, xin cho con biết dùng những gì con có để phục vụ Chúa và anh chị em, không cần ai biết đến. Amen.'),

        '2026-09-19' => array('Th. Januariô, giám mục, tử đạo', 'Lc 8,4-15', 'Mảnh đất tốt',
            'Hạt rơi vào đất tốt là những người nghe Lời, giữ lấy và sinh hoa kết quả.', 'Lc 8,15',
            'Hạt giống thì như nhau, chỉ có mảnh đất là khác. Lời Chúa sinh hoa trái nơi tấm lòng biết lắng nghe, gìn giữ và kiên trì.',
            array(
                'Người gieo vung tay thật rộng: hạt rơi bên vệ đường, trên sỏi đá, giữa bụi gai và vào đất tốt. Thiên Chúa không tiếc Lời của Người với ai. Điều quyết định mùa gặt là cách mỗi người đón nhận: hời hợt, nông cạn, bị lo toan bóp nghẹt, hay sâu lắng và bền bỉ.',
                'Đất tốt không tự nhiên mà có; đất cần được cày xới, nhặt đá, nhổ gai. Năm phút mỗi ngày dành cho Lời Chúa là một cách cày xới mảnh đất lòng mình, để Lời có chỗ bén rễ và lớn lên theo thời gian.',
            ),
            'Lạy Chúa, xin làm cho lòng con thành mảnh đất tốt, biết đón nhận Lời Chúa và kiên trì sinh hoa kết quả. Amen.'),

        '2026-09-20' => array('', 'Mt 20,1-16a', 'Giờ thứ mười một',
            'Hay vì thấy tôi tốt bụng mà bạn đâm ra ghen tức?', 'Mt 20,15',
            'Ông chủ vườn nho trả cho người đến sau cùng bằng người làm từ sáng sớm. Lòng quảng đại của Thiên Chúa vượt xa mọi phép tính công bằng của con người.',
            array(
                'Theo cách nghĩ thông thường, người làm cả ngày đáng được trả nhiều hơn. Nhưng ông chủ trong dụ ngôn không hề bất công: ông trả đúng như đã thỏa thuận với người đến sớm, và rộng rãi với người đến muộn. Điều làm người làm thuê khó chịu không phải là thiệt thòi, mà là lòng tốt dành cho người khác.',
                'Được làm việc trong vườn nho của Chúa từ sớm đã là một ân huệ, chứ không phải gánh nặng để đòi công. Khi thôi so sánh mình với người khác, ta mới có thể vui mừng vì một người anh em trở về vào giờ thứ mười một.',
            ),
            'Lạy Chúa, xin chữa con khỏi lòng ganh tị, để con biết vui với lòng quảng đại Chúa dành cho mọi người. Amen.'),

        '2026-09-21' => array('Th. Mátthêu, Tông đồ, tác giả sách Tin Mừng – lễ kính', 'Mt 9,9-13', 'Hãy theo Ta',
            'Ta không đến để kêu gọi người công chính, mà để kêu gọi người tội lỗi.', 'Mt 9,13',
            'Một ánh mắt, một lời mời, và người thu thuế Mátthêu bỏ lại bàn thu thuế. Chúa không chờ ta hoàn hảo rồi mới gọi.',
            array(
                'Ông Mátthêu ngồi ở trạm thu thuế, nơi bị người Do Thái coi là ô uế. Đức Giêsu đi ngang qua, thấy ông và nói: "Anh hãy theo tôi." Ông đứng dậy đi theo Người. Không có điều kiện, không có thời gian thử thách; chỉ có lời mời và một câu trả lời dứt khoát.',
                'Rồi ông Mátthêu mở tiệc mời bạn bè thu thuế đến gặp Chúa. Người vừa được gọi trở thành người đưa kẻ khác đến với Chúa. Ơn gọi Kitô hữu cũng thế: được yêu thương rồi chia sẻ tình yêu ấy cho những ai còn ở bên lề.',
            ),
            'Lạy Chúa, xin cho con nghe được tiếng Chúa gọi giữa công việc hằng ngày và mạnh dạn đứng dậy theo Chúa. Amen.'),

        '2026-09-22' => array('', 'Lc 8,19-21', 'Mẹ và anh em của Thầy',
            'Mẹ tôi và anh em tôi là những người nghe Lời Thiên Chúa và đem ra thực hành.', 'Lc 8,21',
            'Đức Giêsu mở rộng gia đình của Người cho tất cả những ai nghe và sống Lời Chúa. Ta được mời trở thành người nhà của Thiên Chúa.',
            array(
                'Mẹ và anh em Đức Giêsu đến nhưng không vào được vì dân chúng quá đông. Câu trả lời của Người không hề hạ thấp Đức Maria; trái lại, Mẹ chính là người đầu tiên đã nghe Lời và để Lời thành xác phàm trong cuộc đời mình.',
                'Thuộc về gia đình của Chúa không do huyết thống hay danh xưng, nhưng do việc nghe và làm theo Lời. Mỗi lần đọc Lời Chúa rồi cố gắng sống một điều nhỏ trong ngày, ta lại gần gũi với Chúa như người thân trong nhà.',
            ),
            'Lạy Chúa, xin cho con không chỉ nghe Lời Chúa, nhưng còn biết đem ra thực hành mỗi ngày. Amen.'),

        '2026-09-23' => array('Th. Piô Pietrelcina, linh mục – lễ nhớ', 'Lc 9,1-6', 'Hành trang nhẹ nhàng',
            'Anh em đừng mang gì đi đường, đừng mang gậy, bao bị, lương thực.', 'Lc 9,3',
            'Các Tông đồ được sai đi với hai bàn tay trắng nhưng mang theo quyền năng của Chúa. Người môn đệ càng nhẹ gánh càng dễ bước đi.',
            array(
                'Đức Giêsu trao cho Nhóm Mười Hai quyền trừ quỷ và chữa bệnh, rồi dặn các ông đừng mang gì theo. Lệnh truyền nghe có vẻ phi lý, nhưng nó dạy các ông tin cậy: Đấng sai đi sẽ lo liệu cho người được sai.',
                'Thánh Piô Pietrelcina sống nghèo khó trong một tu viện nhỏ, nhưng hàng ngàn người tìm đến tòa giải tội của ngài. Sức mạnh của ngài không đến từ phương tiện, mà từ đời sống cầu nguyện. Ta cũng được mời buông bớt những gì làm mình nặng nề để rao giảng bằng chính đời sống.',
            ),
            'Lạy Chúa, xin giúp con buông bỏ những gì không cần thiết, để con nhẹ nhàng bước theo Chúa. Amen.'),

        '2026-09-24' => array('', 'Lc 9,7-9', 'Vua Hêrôđê muốn gặp Đức Giêsu',
            'Vậy người này là ai mà tôi nghe đồn những chuyện như thế?', 'Lc 9,9',
            'Vua Hêrôđê tò mò về Đức Giêsu nhưng không muốn đổi đời. Gặp gỡ Chúa thật sự đòi hỏi nhiều hơn một sự tò mò.',
            array(
                'Vua Hêrôđê đã chém đầu ông Gioan, nhưng lương tâm ông không yên khi nghe nói về Đức Giêsu. Ông muốn gặp Người, như muốn xem một hiện tượng lạ. Về sau, khi gặp Chúa trong cuộc thương khó, ông chỉ chờ xem phép lạ rồi chế giễu Người.',
                'Có thể ta cũng tìm hiểu về Chúa như một đề tài thú vị, đọc nhiều, nghe nhiều mà lòng chẳng đổi thay. Câu hỏi "Người này là ai?" chỉ có câu trả lời thật khi ta dám để Người bước vào và thay đổi cuộc sống mình.',
            ),
            'Lạy Chúa, xin cho con không dừng lại ở sự tò mò, nhưng thật lòng tìm gặp và đi theo Chúa. Amen.'),

        '2026-09-25' => array('', 'Lc 9,18-22', 'Anh em bảo Thầy là ai?',
            'Còn anh em, anh em bảo Thầy là ai?', 'Lc 9,20',
            'Câu hỏi của Đức Giêsu không dành cho đám đông mà cho từng môn đệ. Mỗi người phải tự trả lời bằng chính đời sống mình.',
            array(
                'Sau khi cầu nguyện một mình, Đức Giêsu hỏi các môn đệ về dư luận. Người ta bảo Người là Gioan Tẩy Giả, là Êlia, là một ngôn sứ. Rồi Người hỏi thẳng các ông. Ông Phêrô thưa: "Thầy là Đấng Kitô của Thiên Chúa."',
                'Ngay sau lời tuyên xưng, Đức Giêsu nói về cuộc khổ nạn. Biết Chúa là Đấng Kitô cũng có nghĩa là chấp nhận con đường thập giá của Người. Câu trả lời của ta không chỉ nằm trên môi miệng, mà trong cách ta đón nhận những khó khăn mỗi ngày.',
            ),
            'Lạy Chúa Giêsu, Chúa là Đấng Kitô của Thiên Chúa. Xin cho con tuyên xưng điều ấy bằng cả cuộc đời con. Amen.'),

        '2026-09-26' => array('Th. Cosma và Th. Đamianô, tử đạo', 'Lc 9,43b-45', 'Những điều khó hiểu',
            'Con Người sắp bị nộp vào tay người đời.', 'Lc 9,44',
            'Giữa lúc mọi người thán phục các việc Chúa làm, Người lại nói về cuộc thương khó. Các môn đệ không hiểu mà cũng sợ không dám hỏi.',
            array(
                'Đám đông đang trầm trồ trước các phép lạ, còn Đức Giêsu thì muốn các môn đệ ghi nhớ một điều khác: Người sẽ bị nộp. Các ông không hiểu, và Tin Mừng ghi thêm một chi tiết rất thật: các ông sợ không dám hỏi.',
                'Có những lời của Chúa ta chưa hiểu, có những biến cố trong đời ta chưa thấy ý nghĩa. Đừng sợ hỏi Chúa trong cầu nguyện. Hai thánh y sĩ Cosma và Đamianô chữa bệnh không lấy tiền và làm chứng cho Chúa đến cùng; các ngài nhắc ta rằng tin tưởng không có nghĩa là đã hiểu hết.',
            ),
            'Lạy Chúa, khi con không hiểu đường lối Chúa, xin cho con đủ can đảm để hỏi và đủ khiêm nhường để tin. Amen.'),

        '2026-09-27' => array('', 'Mt 21,28-32', 'Người con nói không rồi lại đi',
            'Con không muốn đi, nhưng sau đó nó hối hận nên đã đi.', 'Mt 21,29',
            'Một người con nói "không" nhưng rồi đi làm vườn nho, người kia nói "vâng" mà không đi. Điều Chúa tìm không phải là lời hứa đẹp, mà là lòng hoán cải thật.',
            array(
                'Dụ ngôn rất ngắn mà chạm vào điều sâu kín: khoảng cách giữa lời nói và việc làm. Người con thứ hai trả lời lễ phép nhưng không đi; người con thứ nhất cứng đầu lúc đầu nhưng đã đổi ý. Đức Giêsu nói những người thu thuế và gái điếm vào Nước Trời trước các thượng tế, vì họ đã tin và hoán cải.',
                'Không bao giờ là quá muộn để đổi ý và trở về. Và cũng đừng vội tự mãn vì mình đã nói "vâng" với Chúa từ lâu. Mỗi ngày là một dịp để biến lời "vâng" thành việc làm cụ thể trong vườn nho của gia đình, giáo xứ và xã hội.',
            ),
            'Lạy Chúa, xin cho con biết hoán cải mỗi ngày, để lời con thưa "vâng" với Chúa trở thành việc làm. Amen.'),

        '2026-09-28' => array('Th. Venceslaô, tử đạo; Th. Lôrensô Ruiz và các bạn tử đạo', 'Lc 9,46-50', 'Ai là người lớn nhất?',
            'Người nhỏ nhất trong tất cả anh em, người ấy mới là người lớn nhất.', 'Lc 9,48',
            'Các môn đệ tranh nhau chỗ nhất, Đức Giêsu đặt một em nhỏ đứng bên cạnh. Sự cao trọng trong Nước Trời được đo bằng lòng khiêm hạ.',
            array(
                'Ý nghĩ ai lớn hơn ai len lỏi vào cả nhóm những người gần Chúa nhất. Đức Giêsu không trách mắng dài dòng; Người đặt một em bé đứng bên cạnh mình. Đón tiếp một em bé nhân danh Người là đón tiếp chính Người.',
                'Ngay sau đó, ông Gioan muốn ngăn cản một người trừ quỷ nhân danh Thầy vì người ấy không thuộc nhóm. Chúa đáp: ai không chống đối anh em là ủng hộ anh em. Lòng khiêm nhường cũng giúp ta vui mừng vì điều tốt người khác làm, dù họ không ở trong nhóm của mình.',
            ),
            'Lạy Chúa, xin cho con biết chọn chỗ thấp và vui mừng vì mọi điều tốt đẹp người khác làm nhân danh Chúa. Amen.'),

        '2026-09-29' => array('Các Tổng lãnh Thiên thần Micae, Gabriel và Raphael – lễ kính', 'Ga 1,47-51', 'Trời rộng mở',
            'Anh em sẽ thấy trời rộng mở, và các thiên sứ lên xuống trên Con Người.', 'Ga 1,51',
            'Các Tổng lãnh Thiên thần là sứ giả nối liền trời với đất. Nơi Đức Giêsu, trời đã mở ra cho con người.',
            array(
                'Ông Nathanaen ngạc nhiên vì Đức Giêsu đã biết ông từ khi ông ngồi dưới cây vả. Chúa hứa với ông những điều lớn lao hơn: trời rộng mở và các thiên sứ lên xuống trên Con Người, như chiếc thang tổ phụ Giacóp đã thấy trong giấc mơ.',
                'Micae nghĩa là "Ai bằng Thiên Chúa", Gabriel là "Sức mạnh của Thiên Chúa", Raphael là "Thiên Chúa chữa lành". Tên các ngài là những lời tuyên xưng. Các ngài nhắc ta rằng Thiên Chúa không bỏ mặc con người; Người luôn sai sứ giả đến bảo vệ, loan báo và chữa lành.',
            ),
            'Lạy các Tổng lãnh Thiên thần, xin bảo vệ chúng con trong cuộc chiến đấu hằng ngày và dẫn đưa chúng con về cùng Thiên Chúa. Amen.'),

        '2026-09-30' => array('Th. Giêrônimô, linh mục, tiến sĩ Hội Thánh – lễ nhớ', 'Lc 9,57-62', 'Đã tra tay cầm cày',
            'Ai đã tra tay cầm cày mà còn ngoái lại đàng sau thì không thích hợp với Nước Thiên Chúa.', 'Lc 9,62',
            'Theo Chúa không phải là một lựa chọn nửa vời. Người môn đệ được mời nhìn về phía trước với một trái tim không chia sẻ.',
            array(
                'Ba người muốn theo Đức Giêsu, và cả ba đều nghe những lời đòi hỏi: Con Người không có chỗ tựa đầu; hãy để kẻ chết chôn kẻ chết; đừng ngoái lại đàng sau. Những lời ấy không nhằm làm ta sợ, mà để ta hiểu rằng Chúa xứng đáng được ưu tiên hàng đầu.',
                'Thánh Giêrônimô dành cả đời để dịch và giải thích Kinh Thánh. Ngài để lại câu nói nổi tiếng: không biết Kinh Thánh là không biết Đức Kitô. Cầm cày mà không ngoái lại, với ta hôm nay, có thể bắt đầu bằng việc trung thành với vài phút đọc Lời Chúa mỗi ngày.',
            ),
            'Lạy Chúa, xin cho con một trái tim không chia sẻ, dám bước theo Chúa mà không ngoái lại. Amen.'),

        '2026-10-01' => array('Th. Têrêsa Hài Đồng Giêsu, trinh nữ, tiến sĩ Hội Thánh – lễ nhớ', 'Lc 10,1-12', 'Lúa chín đầy đồng',
            'Lúa chín đầy đồng, mà thợ gặt lại ít.', 'Lc 10,2',
            'Đức Giêsu sai bảy mươi hai môn đệ đi trước Người. Thánh Têrêsa không rời đan viện mà vẫn là bổn mạng các xứ truyền giáo.',
            array(
                'Chúa sai các môn đệ đi từng hai người, như chiên giữa bầy sói, không túi tiền, không bao bị. Việc đầu tiên khi vào một nhà là chúc bình an. Sứ điệp thật đơn sơ: Triều Đại Thiên Chúa đã đến gần.',
                'Thánh Têrêsa Hài Đồng Giêsu sống "con đường nhỏ": làm những việc bé nhỏ với tình yêu lớn, và cầu nguyện không ngừng cho các nhà truyền giáo. Ai cũng có thể là thợ gặt theo cách của mình: bằng lời cầu nguyện, bằng một nụ cười, bằng việc bổn phận làm cho trọn.',
            ),
            'Lạy Chúa, xin sai thêm thợ gặt vào đồng lúa của Chúa, và xin cho con biết góp phần bằng những việc nhỏ làm với tình yêu lớn. Amen.'),

        '2026-10-02' => array('Các Thiên thần Hộ thủ – lễ nhớ', 'Mt 18,1-5.10', 'Thiên thần của những người bé nhỏ',
            'Các thiên thần của họ ở trên trời hằng chiêm ngưỡng nhan Cha Thầy.', 'Mt 18,10',
            'Mỗi người, nhất là những ai bé nhỏ, đều quý giá trước mặt Thiên Chúa. Thiên thần hộ thủ là dấu chỉ Người luôn che chở ta.',
            array(
                'Khi các môn đệ hỏi ai lớn nhất, Đức Giêsu gọi một em nhỏ đến và đặt vào giữa. Người dạy phải trở nên như trẻ nhỏ mới vào được Nước Trời, và cảnh giác: đừng khinh một ai trong những kẻ bé mọn này.',
                'Lời Chúa nói về các thiên thần của những người bé nhỏ là một lời an ủi lớn: không ai bị bỏ quên. Từ thuở bé, nhiều người trong chúng ta đã thuộc kinh Thiên Thần Bản Mệnh. Hôm nay là dịp đọc lại kinh ấy với lòng tin tưởng của một đứa trẻ.',
            ),
            'Lạy Thiên Thần Chúa là đấng Chúa sai đến gìn giữ con, xin soi sáng, che chở và dẫn đưa con trong ngày hôm nay. Amen.'),

        '2026-10-03' => array('', 'Lc 10,17-24', 'Hãy vui vì tên anh em được ghi trên trời',
            'Hãy mừng vì tên anh em đã được ghi trên trời.', 'Lc 10,20',
            'Các môn đệ trở về hớn hở vì những thành công. Đức Giêsu chỉ cho các ông niềm vui sâu xa hơn: được Thiên Chúa biết và yêu.',
            array(
                'Bảy mươi hai môn đệ trở về và kể lại rằng cả ma quỷ cũng phải khuất phục. Đức Giêsu không phủ nhận thành quả ấy, nhưng Người hướng các ông đến một niềm vui bền vững hơn mọi thành công: tên các ông đã được ghi trên trời.',
                'Rồi chính Đức Giêsu hớn hở trong Thánh Thần, chúc tụng Chúa Cha vì đã mặc khải cho những người bé mọn. Niềm vui của người môn đệ không lệ thuộc vào kết quả công việc, mà vào tương quan với Chúa. Nhớ điều ấy, ta không kiêu ngạo lúc thành công, cũng không nản lòng lúc thất bại.',
            ),
            'Lạy Chúa, xin cho con tìm niềm vui nơi tình yêu Chúa hơn là nơi những thành công của con. Amen.'),

        '2026-10-04' => array('', 'Mt 21,33-43', 'Tảng đá thợ xây loại bỏ',
            'Tảng đá thợ xây loại bỏ lại trở nên đá tảng góc tường.', 'Mt 21,42',
            'Chủ vườn nho chăm sóc vườn chu đáo, sai hết đầy tớ này đến đầy tớ khác, cuối cùng sai cả con mình. Dụ ngôn kể về lòng kiên nhẫn vô bờ của Thiên Chúa.',
            array(
                'Ông chủ rào giậu, đào bồn ép nho, xây tháp canh rồi giao vườn cho tá điền. Đến mùa, ông sai người đến thu hoa lợi, nhưng họ bị đánh đập, giết chết. Cuối cùng ông sai con trai mình, và người con cũng bị giết. Đức Giêsu đang nói về chính Người.',
                'Vườn nho là những gì Thiên Chúa trao cho ta: sự sống, gia đình, tài năng, đức tin. Ta không phải là chủ, mà là người quản lý được tin tưởng. Câu hỏi của dụ ngôn vẫn còn đó: đến mùa, ta có hoa trái nào để dâng lại cho Chủ vườn?',
            ),
            'Lạy Chúa, xin cho con biết chăm sóc vườn nho Chúa trao và sinh hoa trái đúng mùa. Amen.'),

        '2026-10-05' => array('Th. Faustina Kowalska, trinh nữ', 'Lc 10,25-37', 'Ai là người thân cận của tôi?',
            'Ông hãy đi, và cũng hãy làm như vậy.', 'Lc 10,37',
            'Người Samari không hỏi người bị nạn là ai, chỉ thấy và chạnh lòng thương. Thân cận không phải là người ở gần ta, mà là người ta chọn đến gần.',
            array(
                'Một thầy thông luật hỏi để thử Đức Giêsu, rồi hỏi thêm để tự biện minh: "Ai là người thân cận của tôi?" Chúa trả lời bằng câu chuyện người Samari nhân hậu. Thầy tư tế và thầy Lêvi đi qua bên kia đường; người Samari, kẻ bị coi là ngoại đạo, lại dừng lại, băng bó, chở người bị nạn đến quán trọ và trả tiền.',
                'Đức Giêsu đảo ngược câu hỏi: không phải "ai là người thân cận của tôi", mà "tôi có trở nên người thân cận của ai không". Thánh Faustina, sứ giả Lòng Chúa Thương Xót, nhắc ta rằng lòng thương xót không dừng ở cảm xúc, mà phải trở thành việc làm cụ thể.',
            ),
            'Lạy Chúa Giêsu, xin cho con có đôi mắt biết thấy và trái tim biết chạnh lòng thương trước những người đang cần con giúp đỡ. Amen.'),

        '2026-10-06' => array('Th. Brunô, linh mục', 'Lc 10,38-42', 'Chỉ có một chuyện cần thôi',
            'Maria đã chọn phần tốt nhất và sẽ không bị lấy mất.', 'Lc 10,42',
            'Chị Mácta tất bật lo tiếp khách, cô Maria ngồi dưới chân Chúa lắng nghe. Phục vụ chỉ có ý nghĩa khi xuất phát từ việc lắng nghe.',
            array(
                'Chị Mácta không làm điều gì sai; chị đang phục vụ chính Chúa. Nhưng chị bị cuốn vào bao nhiêu việc, đến mức bực mình với em gái và trách cả Chúa. Đức Giêsu dịu dàng gọi tên chị hai lần, như muốn kéo chị ra khỏi vòng xoáy lo lắng.',
                'Thánh Brunô đã lập dòng Chartreux, nơi các đan sĩ dành trọn đời cho thinh lặng và cầu nguyện. Không phải ai cũng được gọi vào đan viện, nhưng ai cũng cần những phút ngồi dưới chân Chúa. Từ đó, công việc của ta mới không biến thành gánh nặng.',
            ),
            'Lạy Chúa, giữa bao bận rộn, xin cho con biết chọn phần tốt nhất là lắng nghe Chúa mỗi ngày. Amen.'),

        '2026-10-07' => array('Đức Mẹ Mân Côi – lễ nhớ', 'Lc 11,1-4', 'Xin dạy chúng con cầu nguyện',
            'Thưa Thầy, xin dạy chúng con cầu nguyện.', 'Lc 11,1',
            'Các môn đệ thấy Thầy cầu nguyện và ước ao được như Thầy. Kinh Lạy Cha và chuỗi Mân Côi là hai trường dạy cầu nguyện của Hội Thánh.',
            array(
                'Đức Giêsu dạy các môn đệ gọi Thiên Chúa là Cha, xin cho Danh Cha được hiển thánh, Nước Cha trị đến, xin lương thực hằng ngày, xin ơn tha thứ và ơn khỏi sa chước cám dỗ. Lời kinh ngắn mà chứa đựng cả Tin Mừng.',
                'Trong tháng Mân Côi, Hội Thánh mời ta lần hạt cùng Mẹ Maria. Mỗi chục kinh bắt đầu bằng kinh Lạy Cha và đưa ta suy niệm một mầu nhiệm trong cuộc đời Đức Giêsu. Lần chuỗi không phải là lặp lại máy móc, mà là cùng Mẹ ngắm nhìn khuôn mặt của Con.',
            ),
            'Lạy Mẹ Maria, xin dạy con cầu nguyện với lòng tin tưởng của người con, và cùng Mẹ suy niệm cuộc đời Chúa Giêsu. Amen.'),

        '2026-10-08' => array('', 'Lc 11,5-13', 'Cứ xin thì sẽ được',
            'Anh em cứ xin thì sẽ được, cứ tìm thì sẽ thấy, cứ gõ cửa thì sẽ mở cho.', 'Lc 11,9',
            'Người bạn gõ cửa lúc nửa đêm cuối cùng cũng được giúp vì kiên nhẫn. Thiên Chúa còn quảng đại hơn thế, Người ban Thánh Thần cho kẻ kêu xin.',
            array(
                'Đức Giêsu kể chuyện một người đến nhà bạn lúc nửa đêm để mượn bánh. Người bạn đã đóng cửa, đã lên giường, nhưng vì sự lì lợm của người kia mà dậy đưa bánh. Dụ ngôn mời ta kiên trì cầu nguyện, không nản lòng khi chưa thấy câu trả lời.',
                'Rồi Chúa hỏi: có người cha nào con xin cá mà lại cho rắn? Thiên Chúa là Cha tốt lành; Người không luôn cho đúng điều ta xin, nhưng luôn ban điều tốt nhất là chính Thánh Thần của Người. Cầu nguyện kiên trì làm cho lòng ta dần mở ra để đón nhận món quà ấy.',
            ),
            'Lạy Cha, xin cho con kiên trì cầu nguyện và tin rằng Cha luôn ban cho con điều tốt nhất. Amen.'),

        '2026-10-09' => array('Th. Đionysiô, giám mục, và các bạn tử đạo; Th. Gioan Lêônarđô, linh mục', 'Lc 11,15-26', 'Ai không thu góp là phân tán',
            'Ai không thu góp với tôi là phân tán.', 'Lc 11,23',
            'Có người cho rằng Đức Giêsu trừ quỷ nhờ quyền của quỷ. Chúa cho thấy không thể đứng trung lập trước sự thiện.',
            array(
                'Một nước tự chia rẽ thì sẽ điêu tàn. Đức Giêsu dùng lý lẽ đơn giản để vạch trần sự vô lý của những lời vu khống. Người trừ quỷ bằng ngón tay Thiên Chúa, nghĩa là Triều Đại Thiên Chúa đã đến.',
                'Chúa còn kể về căn nhà đã được quét dọn sạch sẽ nhưng bỏ trống, rồi quỷ trở lại cùng bảy quỷ khác. Hoán cải không chỉ là dọn bỏ điều xấu, mà còn phải để Chúa ở trong căn nhà lòng mình. Một trái tim trống rỗng rất dễ bị chiếm lại.',
            ),
            'Lạy Chúa, xin ngự trong lòng con, để không còn chỗ nào cho sự dữ trở lại. Amen.'),

        '2026-10-10' => array('', 'Lc 11,27-28', 'Phúc thay người nghe và giữ Lời',
            'Phúc thay kẻ lắng nghe và tuân giữ Lời Thiên Chúa.', 'Lc 11,28',
            'Một phụ nữ giữa đám đông khen người mẹ đã sinh ra Đức Giêsu. Chúa chỉ ra nguồn hạnh phúc mà ai cũng có thể đạt tới.',
            array(
                'Lời khen của người phụ nữ rất tự nhiên: ai có người con như thế thì thật hạnh phúc. Đức Giêsu không phủ nhận, nhưng mở rộng mối phúc: phúc cho những ai nghe và giữ Lời Thiên Chúa. Và Đức Maria chính là người đầu tiên sống mối phúc ấy.',
                'Thứ Bảy thường được dành để kính nhớ Đức Mẹ. Mẹ đã giữ mọi lời và suy đi nghĩ lại trong lòng. Năm phút với Lời Chúa mỗi ngày là cách ta bước theo Mẹ trên con đường của mối phúc này.',
            ),
            'Lạy Mẹ Maria, xin giúp con biết lắng nghe và gìn giữ Lời Chúa như Mẹ. Amen.'),

        '2026-10-11' => array('', 'Mt 22,1-14', 'Tiệc cưới đã sẵn sàng',
            'Tiệc cưới đã sẵn sàng, mời các anh đến dự.', 'Mt 22,4',
            'Nhà vua mở tiệc cưới cho con mình, nhưng khách được mời lại viện cớ không đến. Lời mời của Thiên Chúa vẫn được gửi đến mọi ngả đường.',
            array(
                'Người thì về thăm trại, người đi buôn bán, có kẻ còn bắt các đầy tớ mà giết. Nhà vua liền sai đầy tớ ra các ngả đường, gặp ai cũng mời vào, cả người xấu lẫn người tốt, và phòng tiệc đầy khách.',
                'Nhưng có một người không mặc áo cưới. Lời mời là nhưng không, nhưng đáp lại lời mời đòi ta mặc lấy chiếc áo mới là Đức Kitô, là đời sống đổi mới. Mỗi Thánh Lễ là một bàn tiệc; ta được mời đến với tấm lòng đã sửa soạn.',
            ),
            'Lạy Chúa, cảm tạ Chúa đã mời con dự tiệc. Xin cho con mặc lấy chiếc áo cưới của lòng hoán cải mỗi ngày. Amen.'),

        '2026-10-12' => array('', 'Lc 11,29-32', 'Dấu lạ ông Giôna',
            'Ở đây còn có hơn ông Giôna nữa.', 'Lc 11,32',
            'Người ta đòi một dấu lạ ngoạn mục, trong khi Đấng lớn hơn ông Giôna và vua Salômôn đang ở ngay trước mặt họ.',
            array(
                'Dân thành Ninivê đã hoán cải khi nghe ông Giôna rao giảng; nữ hoàng phương Nam đã vượt đường xa để nghe sự khôn ngoan của vua Salômôn. Còn thế hệ của Đức Giêsu có Đấng lớn hơn ngay giữa họ mà vẫn đòi thêm dấu lạ.',
                'Ta cũng có thể chờ một dấu chỉ đặc biệt mới chịu thay đổi. Nhưng Chúa đã ở đây: trong Lời Người, trong Thánh Thể, trong người nghèo bên cạnh. Dấu lạ lớn nhất là Đức Kitô chết và sống lại, và Người đang mời ta hoán cải hôm nay.',
            ),
            'Lạy Chúa, xin mở mắt con để nhận ra Chúa đang hiện diện, và không chờ thêm dấu lạ nào mới chịu trở về. Amen.'),

        '2026-10-13' => array('', 'Lc 11,37-41', 'Sạch bên ngoài, sạch bên trong',
            'Hãy bố thí những gì ở bên trong, thế là mọi sự sẽ trở nên sạch.', 'Lc 11,41',
            'Người Pharisêu ngạc nhiên vì Đức Giêsu không rửa tay trước bữa ăn. Chúa nhắc đến sự trong sạch quan trọng hơn: sự trong sạch của trái tim.',
            array(
                'Người Pharisêu rửa sạch bên ngoài chén đĩa, nhưng bên trong lại đầy tham lam và gian ác. Đức Giêsu không chống lại việc giữ luật; Người chỉ cho thấy luật chỉ có giá trị khi diễn tả một trái tim ngay thẳng.',
                'Đức Giêsu đưa ra một phương thuốc bất ngờ: hãy bố thí. Khi biết cho đi, trái tim được giải thoát khỏi sự bám víu và trở nên trong sạch. Có khi điều cần rửa sạch nơi ta không phải là đôi tay, mà là những tính toán ích kỷ.',
            ),
            'Lạy Chúa, xin tạo cho con một trái tim trong sạch, biết cho đi với lòng quảng đại. Amen.'),

        '2026-10-14' => array('Th. Callistô I, giáo hoàng, tử đạo', 'Lc 11,42-46', 'Công lý và lòng yêu mến Thiên Chúa',
            'Các điều này phải làm, mà các điều kia cũng không được bỏ.', 'Lc 11,42',
            'Người Pharisêu nộp thuế thập phân cả rau húng, nhưng bỏ qua công lý và lòng yêu mến Thiên Chúa. Chúa mời ta nhìn lại thứ tự ưu tiên.',
            array(
                'Đức Giêsu không chê việc giữ luật dâng cúng; Người chỉ ra rằng có những điều quan trọng hơn bị bỏ quên. Các nhà thông luật chất lên vai người khác những gánh nặng khó mang, còn mình thì không động ngón tay vào.',
                'Thánh Callistô từng là nô lệ, về sau trở thành giáo hoàng và nổi tiếng vì lòng nhân hậu với những người sa ngã biết ăn năn. Ngài nhắc ta rằng làm môn đệ Chúa là nâng đỡ người khác mang gánh nặng, chứ không phải chất thêm gánh nặng lên vai họ.',
            ),
            'Lạy Chúa, xin cho con biết sống công bằng, yêu mến Chúa và nâng đỡ những người đang mang gánh nặng. Amen.'),

        '2026-10-15' => array('Th. Têrêsa Giêsu (Avila), trinh nữ, tiến sĩ Hội Thánh – lễ nhớ', 'Lc 11,47-54', 'Chìa khóa của sự hiểu biết',
            'Các người đã lấy mất chìa khóa của sự hiểu biết.', 'Lc 11,52',
            'Các nhà thông luật giữ chìa khóa hiểu biết nhưng không vào, lại còn ngăn người khác. Hiểu biết về Chúa chỉ có ý nghĩa khi dẫn ta đến với Người.',
            array(
                'Đức Giêsu trách những người xây mồ cho các ngôn sứ mà cha ông họ đã giết, trong khi chính họ cũng đang khước từ Lời Chúa. Họ biết rất nhiều về Kinh Thánh, nhưng sự hiểu biết ấy không mở cửa cho ai bước vào tương quan với Thiên Chúa.',
                'Thánh Têrêsa Avila dạy rằng cầu nguyện là trò chuyện thân tình với Đấng ta biết là yêu mình. Chìa khóa thật của sự hiểu biết là tình yêu ấy. Học hỏi đức tin là điều tốt, nhưng đừng quên mở cửa để chính mình bước vào và mời người khác cùng vào.',
            ),
            'Lạy Chúa, xin cho những gì con học biết về Chúa trở thành cánh cửa đưa con và anh chị em đến gần Chúa hơn. Amen.'),

        '2026-10-16' => array('Th. Hedwig, nữ tu; Th. Margarita Maria Alacoque, trinh nữ', 'Lc 12,1-7', 'Đừng sợ',
            'Anh em đừng sợ, anh em còn quý giá hơn muôn vàn chim sẻ.', 'Lc 12,7',
            'Thiên Chúa không quên một con chim sẻ nào, và tóc trên đầu ta Người cũng đếm cả rồi. Lòng tin xua tan nỗi sợ hãi.',
            array(
                'Đức Giêsu cảnh giác các môn đệ về men giả hình của người Pharisêu: không có gì che giấu mà sẽ không bị lộ ra. Rồi Người nói về nỗi sợ: đừng sợ những kẻ chỉ giết được thân xác; hãy kính sợ Thiên Chúa.',
                'Ngay sau lời cảnh tỉnh là lời âu yếm: năm con chim sẻ chỉ bán được hai hào, vậy mà không con nào bị Thiên Chúa bỏ quên. Thánh Margarita Maria đã loan báo Thánh Tâm Chúa Giêsu, trái tim yêu thương từng người. Ai biết mình được yêu như thế thì bớt sợ hãi.',
            ),
            'Lạy Thánh Tâm Chúa Giêsu, con tín thác nơi Chúa. Xin xua tan những nỗi sợ hãi trong lòng con. Amen.'),

        '2026-10-17' => array('Th. Ignatiô Antiôkia, giám mục, tử đạo – lễ nhớ', 'Lc 12,8-12', 'Thánh Thần sẽ dạy anh em',
            'Thánh Thần sẽ dạy anh em những gì phải nói.', 'Lc 12,12',
            'Khi phải làm chứng cho Chúa trước mặt người đời, người môn đệ không đơn độc. Thánh Thần sẽ ban lời và ban sức mạnh.',
            array(
                'Đức Giêsu hứa: ai tuyên bố nhận Người trước mặt thiên hạ, Con Người cũng sẽ nhận người ấy trước mặt các thiên sứ. Người cũng biết các môn đệ sẽ bị đưa ra trước công đường, và trấn an các ông: đừng lo phải bào chữa thế nào.',
                'Thánh Ignatiô Antiôkia, trên đường bị giải về Rôma để chịu tử đạo, đã viết những lá thư tràn đầy lòng yêu mến Đức Kitô. Ngài không tự cậy sức mình, nhưng cậy vào ơn Chúa. Làm chứng cho Chúa hôm nay có thể chỉ là một lời nói thật, một thái độ ngay thẳng giữa nơi làm việc.',
            ),
            'Lạy Chúa Thánh Thần, xin ban cho con lời lẽ và lòng can đảm để làm chứng cho Chúa trong cuộc sống hằng ngày. Amen.'),

        '2026-10-18' => array('Khánh nhật Truyền giáo', 'Mt 22,15-21', 'Của Xêda trả về Xêda',
            'Của Xêda, trả về Xêda; của Thiên Chúa, trả về Thiên Chúa.', 'Mt 22,21',
            'Câu hỏi về việc nộp thuế là một cái bẫy, nhưng câu trả lời của Đức Giêsu mở ra một chân trời: con người mang hình ảnh Thiên Chúa thuộc về Thiên Chúa.',
            array(
                'Người Pharisêu và phe Hêrôđê đặt câu hỏi để gài bẫy: nộp thuế cho Xêda có được phép không? Đức Giêsu bảo đưa đồng tiền ra xem; trên đó có hình và danh hiệu của Xêda. Vậy hãy trả cho Xêda những gì thuộc về Xêda.',
                'Nếu đồng tiền mang hình Xêda, thì con người mang hình ảnh Thiên Chúa. Trả cho Thiên Chúa là trao lại cho Người chính bản thân mình. Trong Khánh nhật Truyền giáo, ta được mời góp phần giúp mọi người nhận ra hình ảnh Thiên Chúa nơi họ, bằng lời cầu nguyện, sự đóng góp và chứng tá đời sống.',
            ),
            'Lạy Chúa, con thuộc về Chúa. Xin cho con trao lại cho Chúa cả cuộc đời con và góp phần vào sứ mạng truyền giáo của Hội Thánh. Amen.'),

        '2026-10-19' => array('Th. Gioan Brêbeuf, Th. Isaac Jogues và các bạn tử đạo; Th. Phaolô Thánh Giá, linh mục', 'Lc 12,13-21', 'Giàu có trước mặt Thiên Chúa',
            'Đời sống của một người không do của cải mà được bảo đảm.', 'Lc 12,15',
            'Phú hộ nọ tính chuyện phá kho cũ, xây kho mới, mà quên rằng đêm nay mạng sống ông có thể bị đòi lại. Chúa mời ta làm giàu trước mặt Thiên Chúa.',
            array(
                'Một người xin Đức Giêsu phân xử chuyện chia gia tài. Chúa từ chối làm người phân xử, nhưng nhân dịp ấy cảnh giác về lòng tham. Người kể dụ ngôn về ông phú hộ được mùa, chỉ nói chuyện với chính mình và tính chuyện nghỉ ngơi, ăn uống vui chơi.',
                'Ông không bị trách vì giàu, mà vì chỉ thấy mình và kho lẫm của mình. Làm giàu trước mặt Thiên Chúa là biết chia sẻ, biết sống cho người khác, biết rằng mọi thứ chỉ được trao tạm. Hôm nay, ta có thể chia sẻ điều gì đó với người đang thiếu thốn không?',
            ),
            'Lạy Chúa, xin giải thoát con khỏi lòng tham và dạy con biết làm giàu trước mặt Chúa bằng việc chia sẻ. Amen.'),

        '2026-10-20' => array('', 'Lc 12,35-38', 'Thắt lưng cho gọn, thắp đèn cho sẵn',
            'Anh em hãy thắt lưng cho gọn, thắp đèn cho sẵn.', 'Lc 12,35',
            'Người đầy tớ tỉnh thức chờ chủ đi ăn cưới về. Bất ngờ thay, chính ông chủ lại thắt lưng phục vụ họ.',
            array(
                'Đức Giêsu dùng hình ảnh quen thuộc: đầy tớ thức đợi chủ trở về giữa đêm để mở cửa ngay khi chủ gõ. Tỉnh thức không phải là lo âu, mà là sẵn sàng, như người yêu thương chờ đợi người thân.',
                'Điều bất ngờ là phần thưởng: ông chủ sẽ thắt lưng, mời họ vào bàn và phục vụ họ. Đó chính là hình ảnh Đức Giêsu, Đấng đến để phục vụ. Tỉnh thức hôm nay là sống trọn vẹn từng giờ, trung thành trong những việc nhỏ, để khi Chúa đến, Người thấy ta đang sẵn sàng.',
            ),
            'Lạy Chúa, xin giữ cho ngọn đèn đức tin trong con luôn cháy sáng, để con sẵn sàng đón Chúa mỗi ngày. Amen.'),

        '2026-10-21' => array('', 'Lc 12,39-48', 'Người quản gia trung tín',
            'Ai đã được giao phó nhiều thì sẽ bị đòi hỏi nhiều hơn.', 'Lc 12,48',
            'Người quản gia khôn ngoan lo cho gia nhân đúng giờ dù chủ đi vắng. Những gì ta có là để phục vụ, không phải để thống trị.',
            array(
                'Ông Phêrô hỏi dụ ngôn này nói cho các môn đệ hay cho mọi người. Đức Giêsu trả lời bằng hình ảnh người quản gia được đặt lên coi sóc gia nhân. Nếu anh ta nghĩ chủ còn lâu mới về rồi đánh đập tôi tớ, ăn uống say sưa, thì sẽ bị xử nghiêm.',
                'Mỗi người đều là quản gia của một điều gì đó: gia đình, công việc, tài năng, thời gian. Chúa không đòi ta làm điều vượt sức, nhưng mong ta trung tín với những gì được giao. Càng được trao nhiều, càng có trách nhiệm phục vụ nhiều.',
            ),
            'Lạy Chúa, xin cho con là người quản gia trung tín và khôn ngoan với những gì Chúa đã trao phó. Amen.'),

        '2026-10-22' => array('Th. Gioan Phaolô II, giáo hoàng', 'Lc 12,49-53', 'Lửa trên mặt đất',
            'Thầy đã đến ném lửa vào mặt đất.', 'Lc 12,49',
            'Lửa của Đức Giêsu là lửa tình yêu và Thánh Thần, có sức thanh luyện và thắp sáng. Đón nhận ngọn lửa ấy đôi khi phải trả giá.',
            array(
                'Đức Giêsu nói về một phép rửa Người phải chịu, và Người khắc khoải cho đến khi hoàn tất. Đó là cuộc thương khó, nơi tình yêu được trao ban trọn vẹn. Lửa Người mang đến là lửa của tình yêu ấy, lửa của Thánh Thần thanh luyện và đốt cháy.',
                'Thánh Gioan Phaolô II mở đầu triều đại bằng lời kêu gọi: "Đừng sợ! Hãy mở rộng cửa cho Đức Kitô." Ngài đã sống ngọn lửa ấy qua bao thử thách. Theo Chúa có thể gây chia rẽ ngay trong gia đình, nhưng lửa tình yêu, nếu được giữ gìn kiên nhẫn, cuối cùng sẽ sưởi ấm cả những người khác biệt với mình.',
            ),
            'Lạy Chúa, xin thắp lên trong con ngọn lửa tình yêu của Chúa, để con không sợ hãi mở rộng cửa lòng cho Chúa. Amen.'),

        '2026-10-23' => array('Th. Gioan Capestranô, linh mục', 'Lc 12,54-59', 'Nhận định thời đại',
            'Sao thời đại này, các người lại không biết nhận định?', 'Lc 12,56',
            'Người ta giỏi đoán mưa nắng nhưng không nhận ra thời điểm Thiên Chúa viếng thăm. Chúa mời ta đọc các dấu chỉ thời đại bằng ánh sáng Tin Mừng.',
            array(
                'Thấy mây kéo lên ở phía tây thì biết sắp mưa, thấy gió nam thổi thì biết sẽ nóng. Đức Giêsu ghi nhận sự tinh ý ấy nhưng tiếc rằng người ta không dùng nó cho điều quan trọng nhất: nhận ra Thiên Chúa đang hành động ngay giữa họ.',
                'Chúa còn khuyên hãy làm hòa với đối phương khi còn đang đi đường. Có những mối bất hòa ta cứ để đó, nghĩ rằng còn nhiều thời gian. Nhận định thời đại cũng là nhận ra hôm nay là lúc thuận tiện để hòa giải.',
            ),
            'Lạy Chúa, xin cho con biết nhận ra những dấu chỉ Chúa gửi đến hôm nay và mau mắn làm hòa với anh chị em. Amen.'),

        '2026-10-24' => array('Th. Antôn Maria Claret, giám mục', 'Lc 13,1-9', 'Cây vả được thêm một năm',
            'Thưa ông, xin cứ để nó lại năm nay nữa.', 'Lc 13,8',
            'Cây vả ba năm không có trái, chủ vườn muốn chặt đi, nhưng người làm vườn xin thêm một năm. Thời gian là quà tặng của lòng thương xót.',
            array(
                'Người ta kể cho Đức Giêsu về những người Galilê bị giết và mười tám người bị tháp Silôác đè chết. Chúa không giải thích nguyên nhân tai họa, nhưng chuyển câu chuyện về chính người nghe: nếu không sám hối, ai cũng sẽ chết như vậy.',
                'Rồi Người kể về cây vả không ra trái. Người làm vườn không bỏ cuộc: xin thêm một năm, vun xới, bón phân. Đó là hình ảnh Đức Giêsu kiên nhẫn với ta. Mỗi ngày mới là một năm thêm, một cơ hội để ta sinh hoa trái.',
            ),
            'Lạy Chúa, cảm tạ Chúa đã kiên nhẫn với con. Xin cho con biết dùng thời gian Chúa ban để sinh hoa trái. Amen.'),

        '2026-10-25' => array('', 'Mt 22,34-40', 'Điều răn lớn nhất',
            'Ngươi phải yêu người thân cận như chính mình.', 'Mt 22,39',
            'Giữa hơn sáu trăm điều luật, Đức Giêsu tóm lại trong hai: mến Chúa và yêu người. Hai điều ấy không thể tách rời.',
            array(
                'Một nhà thông luật hỏi để thử Đức Giêsu: trong Lề Luật, điều răn nào lớn nhất? Chúa trả lời bằng hai câu Kinh Thánh quen thuộc: yêu mến Thiên Chúa hết lòng, hết linh hồn, hết trí khôn; và yêu người thân cận như chính mình. Tất cả Lề Luật và các sách ngôn sứ đều tùy thuộc vào hai điều răn ấy.',
                'Yêu Chúa mà không yêu người là ảo tưởng; yêu người mà không có Chúa thì dễ cạn kiệt. Hai điều răn giống như hai thanh gỗ của Thập Giá: một hướng lên trời, một dang rộng đến mọi người, và nơi giao nhau là trái tim của Đức Kitô.',
            ),
            'Lạy Chúa, xin dạy con yêu mến Chúa hết lòng và yêu thương anh chị em như chính mình. Amen.'),

        '2026-10-26' => array('', 'Lc 13,10-17', 'Đứng thẳng lên',
            'Này bà, bà đã được giải thoát khỏi bệnh tật.', 'Lc 13,12',
            'Người phụ nữ còng lưng mười tám năm được Đức Giêsu chữa lành ngày sabát. Chúa muốn con người được đứng thẳng trong phẩm giá của mình.',
            array(
                'Bà không xin gì cả. Chính Đức Giêsu thấy bà, gọi bà lại, đặt tay lên, và lập tức bà đứng thẳng lên và tôn vinh Thiên Chúa. Viên trưởng hội đường thì bực tức vì việc chữa bệnh xảy ra trong ngày sabát.',
                'Đức Giêsu hỏi lại: ai cũng tháo bò lừa đưa đi uống nước ngày sabát, vậy người con gái của tổ phụ Abraham này không được giải thoát sao? Ngày của Chúa là ngày giải phóng con người. Có những gánh nặng làm ta còng lưng lâu ngày; Chúa vẫn đang nhìn thấy và muốn nâng ta đứng dậy.',
            ),
            'Lạy Chúa, xin chạm đến những gánh nặng đang làm con còng lưng, và giúp con đứng thẳng lên để tôn vinh Chúa. Amen.'),

        '2026-10-27' => array('', 'Lc 13,18-21', 'Hạt cải và nắm men',
            'Nước Thiên Chúa giống như chuyện hạt cải.', 'Lc 13,19',
            'Hạt cải bé nhỏ lớn thành cây, nắm men làm dậy cả khối bột. Nước Thiên Chúa lớn lên âm thầm từ những điều rất nhỏ.',
            array(
                'Hai dụ ngôn ngắn ngủi lấy hình ảnh từ đời thường: một người gieo hạt cải trong vườn, một người phụ nữ trộn men vào bột. Cả hai đều bắt đầu từ cái nhỏ bé, gần như vô hình, rồi trở nên lớn lao.',
                'Ta thường nôn nóng muốn thấy kết quả lớn ngay. Nhưng Nước Trời vận hành theo cách khác: một lời tử tế, một kinh nguyện, một việc bác ái nhỏ. Những điều ấy âm thầm làm dậy men cả gia đình và cộng đoàn.',
            ),
            'Lạy Chúa, xin cho con kiên nhẫn gieo những hạt giống nhỏ của Tin Mừng mỗi ngày và tin vào sức lớn lên của Nước Chúa. Amen.'),

        '2026-10-28' => array('Th. Simon và Th. Giuđa, Tông đồ – lễ kính', 'Lc 6,12-19', 'Suốt đêm cầu nguyện',
            'Người đã thức suốt đêm cầu nguyện cùng Thiên Chúa.', 'Lc 6,12',
            'Trước khi chọn Nhóm Mười Hai, Đức Giêsu thức suốt đêm cầu nguyện. Thánh Simon và thánh Giuđa là hai Tông đồ ít được nhắc đến nhưng vẫn là nền móng của Hội Thánh.',
            array(
                'Mọi quyết định quan trọng của Đức Giêsu đều bắt đầu bằng cầu nguyện. Sáng ra, Người gọi các môn đệ và chọn lấy mười hai người. Trong danh sách có ông Simon nhiệt thành và ông Giuđa con ông Giacôbê, hai vị Tông đồ mà Tin Mừng nói rất ít.',
                'Hội Thánh được xây dựng không chỉ trên những người nổi bật, mà còn trên những người trung thành âm thầm. Hai thánh Tông đồ hôm nay nhắc ta rằng không cần được biết đến nhiều để là chứng nhân đích thực. Điều cần là được Chúa gọi và ở lại với Người.',
            ),
            'Lạy Chúa, xin cho con biết bắt đầu mọi quyết định bằng cầu nguyện, và trung thành với ơn gọi Chúa trao. Amen.'),

        '2026-10-29' => array('', 'Lc 13,31-35', 'Như gà mẹ tập hợp gà con',
            'Như gà mẹ tập hợp gà con dưới cánh.', 'Lc 13,34',
            'Dù bị đe dọa, Đức Giêsu vẫn tiến về Giêrusalem. Lời than của Người về thành thánh chất chứa nỗi xót thương của một người mẹ.',
            array(
                'Mấy người Pharisêu báo tin vua Hêrôđê muốn giết Đức Giêsu. Người không sợ hãi, vẫn tiếp tục trừ quỷ, chữa bệnh và đi trên con đường dẫn lên Giêrusalem, nơi Người sẽ hoàn tất sứ mạng.',
                'Rồi Người thốt lên lời than thật cảm động: biết bao lần Người muốn tập hợp con cái Giêrusalem như gà mẹ che chở gà con dưới cánh. Đó là hình ảnh dịu dàng của Thiên Chúa. Người không ép buộc, chỉ dang cánh chờ ta tự nguyện trở về.',
            ),
            'Lạy Chúa, xin cho con biết chạy đến nương náu dưới cánh tình thương của Chúa. Amen.'),

        '2026-10-30' => array('', 'Lc 14,1-6', 'Lòng thương xót trên lề luật',
            'Có được phép chữa bệnh ngày sabát hay không?', 'Lc 14,3',
            'Trong bữa ăn tại nhà một thủ lãnh Pharisêu, Đức Giêsu chữa một người mắc bệnh phù thũng. Luật lệ được đặt ra để phục vụ con người.',
            array(
                'Mọi người đều dò xét Người. Đức Giêsu hỏi thẳng có được phép chữa bệnh ngày sabát không, và họ làm thinh. Người cầm tay bệnh nhân, chữa lành và cho về.',
                'Rồi Người hỏi: nếu con hay con bò của ai rơi xuống giếng ngày sabát, người ấy có kéo lên ngay không? Không ai đáp được. Sự im lặng ấy cho thấy lòng thương xót luôn đi trước. Những quy tắc tốt đẹp của ta cũng cần được soi sáng bởi lòng thương xót ấy.',
            ),
            'Lạy Chúa, xin cho con đừng bao giờ dùng luật lệ để khép lòng trước những người đang cần được giúp đỡ. Amen.'),

        '2026-10-31' => array('', 'Lc 14,1.7-11', 'Hãy ngồi chỗ cuối',
            'Ai tôn mình lên sẽ bị hạ xuống; ai hạ mình xuống sẽ được tôn lên.', 'Lc 14,11',
            'Thấy khách tranh nhau chỗ nhất, Đức Giêsu khuyên hãy chọn chỗ cuối. Khiêm nhường là để Thiên Chúa đặt ta vào đúng chỗ của mình.',
            array(
                'Trong bữa tiệc, khách mời ai cũng muốn ngồi gần chủ nhà. Đức Giêsu đưa ra lời khuyên thực tế: đừng ngồi chỗ nhất, kẻo có người quan trọng hơn đến và mình phải xấu hổ nhường chỗ. Hãy ngồi chỗ cuối, để chủ nhà mời lên.',
                'Đây không chỉ là phép lịch sự mà là con đường của Nước Trời. Đức Giêsu đã tự hạ mình, và Chúa Cha đã tôn vinh Người. Khiêm nhường không phải là tự coi rẻ mình, mà là thôi bận tâm giành chỗ, để trái tim được tự do yêu thương và phục vụ.',
            ),
            'Lạy Chúa Giêsu hiền lành và khiêm nhường trong lòng, xin uốn lòng con nên giống Trái Tim Chúa. Amen.'),
    ),

    // ------------------------------------------------- other categories
    // slug => list of [date 'Y-m-d H:i', title, excerpt, [paragraphs | ['h' => heading] | ['qa' => [[q, a], ...]]]]
    'posts' => array(
        'tu-vung' => array(
            array('2026-09-16 09:00', 'Phụng vụ là gì?',
                'Phụng vụ là việc thờ phượng công khai của toàn thể Hội Thánh, trong đó Đức Kitô tiếp tục công trình cứu độ giữa chúng ta.',
                array(
                    'Từ "phụng vụ" dịch từ tiếng Hy Lạp leitourgia, nguyên nghĩa là "công việc chung" hay "việc phục vụ cho dân". Trong Hội Thánh, phụng vụ là việc thờ phượng công khai mà Đức Kitô, Đầu, cùng với Thân Thể của Người là Hội Thánh dâng lên Chúa Cha.',
                    array('h' => 'Phụng vụ gồm những gì?'),
                    'Phụng vụ gồm Thánh Lễ, các bí tích, Phụng vụ Giờ Kinh (Kinh Nhật Tụng) và các á bí tích. Trong đó, Thánh Lễ là trung tâm, được Công đồng Vaticanô II gọi là "chóp đỉnh và nguồn mạch" của đời sống Hội Thánh.',
                    array('h' => 'Năm phụng vụ'),
                    'Hội Thánh cử hành mầu nhiệm Đức Kitô trong một chu kỳ một năm: Mùa Vọng, Mùa Giáng Sinh, Mùa Chay, Tam Nhật Vượt Qua, Mùa Phục Sinh và Mùa Thường Niên. Mỗi mùa có màu áo lễ riêng: tím, trắng, xanh, đỏ… giúp tín hữu sống nhịp điệu của đức tin.',
                )),
            array('2026-09-22 09:00', 'Bí tích',
                'Bí tích là dấu chỉ hữu hình của ân sủng vô hình, do Đức Kitô thiết lập và trao cho Hội Thánh.',
                array(
                    'Từ "bí tích" (sacramentum) chỉ một dấu chỉ thánh: qua những yếu tố hữu hình như nước, dầu, bánh, rượu, lời đọc và cử chỉ, Thiên Chúa thông ban ân sủng của Người. Bí tích không chỉ tượng trưng mà thực sự làm điều nó biểu thị.',
                    'Hội Thánh Công giáo có bảy bí tích: Rửa Tội, Thêm Sức, Thánh Thể, Hòa Giải, Xức Dầu Bệnh Nhân, Truyền Chức Thánh và Hôn Phối. Các bí tích đồng hành với những chặng quan trọng của đời người, từ lúc sinh ra trong đức tin cho đến khi đau yếu và lìa đời.',
                    'Hiệu quả của bí tích đến từ Đức Kitô hành động, không tùy vào sự thánh thiện của thừa tác viên. Tuy vậy, hoa trái của bí tích nơi mỗi người lại tùy thuộc vào tâm hồn sẵn sàng đón nhận.',
                )),
            array('2026-09-26 09:00', 'Ân sủng',
                'Ân sủng là ơn nhưng không Thiên Chúa ban để ta được làm con cái Người và sống đời sống mới.',
                array(
                    'Ân sủng (gratia) nghĩa là "món quà nhưng không". Đó là sự trợ giúp Thiên Chúa ban cho con người, không phải vì ta xứng đáng, mà vì Người yêu thương. Nhờ ân sủng, ta được tham dự vào sự sống của Thiên Chúa.',
                    'Giáo lý phân biệt ơn thánh hóa, là ơn thường tồn làm cho linh hồn nên thánh và đẹp lòng Chúa, với ơn hiện sủng, là những trợ giúp Chúa ban trong từng hoàn cảnh để ta làm điều thiện và tránh điều ác.',
                    'Ân sủng không loại bỏ tự do của con người. Thiên Chúa mời gọi, con người tự do đáp lại. Cầu nguyện, đọc Lời Chúa và lãnh nhận các bí tích là những cách ta mở lòng đón nhận ân sủng mỗi ngày.',
                )),
            array('2026-10-01 09:00', 'Mùa Thường Niên',
                'Mùa Thường Niên là mùa dài nhất trong năm phụng vụ, giúp ta sống mầu nhiệm Đức Kitô trong đời thường.',
                array(
                    'Mùa Thường Niên có 33 hoặc 34 tuần, chia làm hai phần: từ sau lễ Chúa Giêsu chịu phép rửa đến trước Thứ Tư Lễ Tro, và từ sau lễ Chúa Thánh Thần Hiện Xuống đến trước Chúa Nhật thứ nhất Mùa Vọng. Màu áo lễ là màu xanh lá, màu của hy vọng và sự sống.',
                    '"Thường niên" không có nghĩa là tầm thường. Trong mùa này, Hội Thánh lần lượt đọc các trình thuật về cuộc đời công khai của Đức Giêsu: lời giảng dạy, dụ ngôn, phép lạ. Đó là thời gian để Lời Chúa thấm vào nhịp sống hằng ngày.',
                    'Chúa nhật cuối cùng của Mùa Thường Niên là lễ Đức Giêsu Kitô Vua Vũ Trụ, khép lại năm phụng vụ và hướng ta về ngày Chúa đến trong vinh quang.',
                )),
            array('2026-10-03 09:00', 'Thánh Thể',
                'Thánh Thể là bí tích Mình và Máu Đức Kitô, nguồn mạch và chóp đỉnh của toàn bộ đời sống Kitô hữu.',
                array(
                    'Trong bữa Tiệc Ly, Đức Giêsu cầm lấy bánh và rượu, tạ ơn và trao cho các môn đệ: "Này là Mình Thầy… Này là Máu Thầy." Người truyền cho các ông làm việc này mà nhớ đến Người. Từ đó, Hội Thánh cử hành Thánh Lễ để tưởng niệm và hiện tại hóa hy tế của Đức Kitô.',
                    'Từ "Eucharistia" nghĩa là "tạ ơn". Thánh Lễ gồm hai phần chính: Phụng vụ Lời Chúa và Phụng vụ Thánh Thể. Qua lời truyền phép, bánh và rượu trở nên Mình và Máu Đức Kitô; Hội Thánh gọi đó là sự biến đổi bản thể.',
                    'Rước Lễ là kết hiệp mật thiết với Đức Kitô và với nhau. Vì thế, Thánh Thể làm nên Hội Thánh, và Hội Thánh sống nhờ Thánh Thể.',
                )),
            array('2026-10-05 09:00', 'Tin Mừng',
                'Tin Mừng là sứ điệp vui về ơn cứu độ nơi Đức Giêsu Kitô, cũng là tên gọi của bốn sách kể về cuộc đời Người.',
                array(
                    'Từ "Tin Mừng" dịch từ tiếng Hy Lạp euangelion, nghĩa là "tin vui". Trước hết, Tin Mừng chính là Đức Giêsu: Người đến để loan báo Nước Thiên Chúa và mở đường cứu độ cho mọi người.',
                    'Tân Ước có bốn sách Tin Mừng theo thánh Mátthêu, Máccô, Luca và Gioan. Ba sách đầu được gọi là "Nhất Lãm" vì có nhiều đoạn giống nhau khi đặt cạnh nhau. Tin Mừng theo thánh Gioan có cách trình bày riêng, giàu suy tư thần học.',
                    'Trong Thánh Lễ, cộng đoàn đứng lên nghe Tin Mừng để bày tỏ lòng tôn kính, vì chính Đức Kitô đang nói với dân Người.',
                )),
        ),

        'giao-ly' => array(
            array('2026-09-17 14:00', 'Kinh Tin Kính: bản tóm lược đức tin',
                'Kinh Tin Kính tóm lại những chân lý căn bản mà người Kitô hữu tuyên xưng, từ Thiên Chúa Tạo Hóa đến sự sống đời đời.',
                array(
                    'Ngay từ thời các Tông đồ, Hội Thánh đã có những công thức ngắn để tuyên xưng đức tin, nhất là khi lãnh nhận bí tích Rửa Tội. Hai bản quen thuộc nhất là Kinh Tin Kính các Tông đồ và Kinh Tin Kính Nixêa – Constantinôpôli.',
                    array('h' => 'Ba phần của Kinh Tin Kính'),
                    'Kinh Tin Kính được sắp xếp theo Ba Ngôi Thiên Chúa: tin kính Đức Chúa Cha, Đấng tạo thành trời đất; tin kính Đức Giêsu Kitô, Con Một Đức Chúa Cha, đã nhập thể, chịu chết và sống lại; tin kính Đức Chúa Thánh Thần, Hội Thánh, sự hiệp thông các thánh, ơn tha tội, xác loài người sống lại và sự sống đời đời.',
                    'Đọc Kinh Tin Kính trong Thánh Lễ Chúa nhật không chỉ là thuộc lòng, mà là mỗi lần làm mới lời "Tôi tin" của mình cùng với cả Hội Thánh.',
                )),
            array('2026-09-24 14:00', 'Bảy Bí tích của Hội Thánh',
                'Bảy bí tích chia thành ba nhóm: khai tâm Kitô giáo, chữa lành, và phục vụ sự hiệp thông.',
                array(
                    array('h' => 'Các bí tích khai tâm'),
                    'Rửa Tội, Thêm Sức và Thánh Thể đặt nền móng cho đời sống Kitô hữu. Rửa Tội cho ta được tái sinh làm con Thiên Chúa; Thêm Sức củng cố ta bằng ơn Thánh Thần; Thánh Thể nuôi dưỡng ta bằng Mình và Máu Đức Kitô.',
                    array('h' => 'Các bí tích chữa lành'),
                    'Hòa Giải đem lại ơn tha tội và hòa giải với Thiên Chúa và Hội Thánh; Xức Dầu Bệnh Nhân nâng đỡ người đau yếu, già nua bằng ơn bình an và can đảm.',
                    array('h' => 'Các bí tích phục vụ sự hiệp thông'),
                    'Truyền Chức Thánh và Hôn Phối hướng đến ơn cứu độ của người khác: qua việc phục vụ cộng đoàn hoặc qua việc xây dựng gia đình. Cả hai đều là con đường nên thánh qua việc trao ban chính mình.',
                )),
            array('2026-09-29 14:00', 'Mười Điều Răn',
                'Mười Điều Răn là lời giao ước Thiên Chúa trao cho dân Người, được Đức Giêsu tóm lại trong hai điều: mến Chúa và yêu người.',
                array(
                    'Trên núi Xinai, Thiên Chúa trao cho ông Môsê hai bia đá ghi Mười Lời. Truyền thống Hội Thánh chia Mười Điều Răn làm hai phần: ba điều đầu nói về bổn phận đối với Thiên Chúa, bảy điều sau nói về bổn phận đối với tha nhân.',
                    'Các điều răn không phải là gánh nặng tùy tiện, mà là con đường của tự do. Chúng bắt đầu bằng lời nhắc nhở: "Ta là Đức Chúa, Thiên Chúa của ngươi, đã đưa ngươi ra khỏi đất Ai Cập, khỏi cảnh nô lệ." Giữ luật là sống như những người đã được giải phóng.',
                    'Đức Giêsu không bãi bỏ Lề Luật nhưng kiện toàn: Người mời ta đi xa hơn việc tránh điều xấu, đến chỗ yêu thương như Người đã yêu.',
                )),
            array('2026-10-02 14:00', 'Cầu nguyện là gì?',
                'Cầu nguyện là nâng tâm hồn lên cùng Thiên Chúa, là cuộc trò chuyện của người con với Cha mình.',
                array(
                    'Sách Giáo lý Hội Thánh Công giáo nhắc lại định nghĩa quen thuộc của thánh Gioan Đamas: cầu nguyện là "nâng tâm hồn lên tới Thiên Chúa" hoặc "xin Thiên Chúa ban những ơn lành thích hợp". Cầu nguyện trước hết là một tương quan sống động, không chỉ là đọc kinh.',
                    array('h' => 'Các hình thức cầu nguyện'),
                    'Có nhiều cách cầu nguyện: chúc tụng và thờ lạy, cầu xin, chuyển cầu cho người khác, tạ ơn và ngợi khen. Ta có thể cầu nguyện bằng lời kinh, bằng suy niệm Lời Chúa, hay đơn giản là thinh lặng ở trước mặt Chúa.',
                    'Bí quyết của cầu nguyện là sự trung thành. Năm phút mỗi ngày, đều đặn, có giá trị hơn một giờ thỉnh thoảng mới có.',
                )),
            array('2026-10-04 14:00', 'Các bước xưng tội',
                'Bí tích Hòa Giải giúp ta làm hòa với Thiên Chúa và Hội Thánh. Dưới đây là các bước đơn giản để chuẩn bị xưng tội.',
                array(
                    array('h' => '1. Xét mình'),
                    'Dành thời gian nhìn lại đời sống dưới ánh sáng Lời Chúa và Mười Điều Răn. Không phải để tự dằn vặt, mà để nhận ra những chỗ mình chưa yêu thương.',
                    array('h' => '2. Ăn năn và dốc lòng chừa'),
                    'Thật lòng hối tiếc vì đã xúc phạm đến Thiên Chúa và anh chị em, đồng thời quyết tâm sửa đổi với ơn Chúa giúp.',
                    array('h' => '3. Xưng tội'),
                    'Thành thật kể các tội với linh mục, người thay mặt Đức Kitô. Linh mục buộc phải giữ bí mật tuyệt đối về những gì đã nghe.',
                    array('h' => '4. Nhận lời tha tội và làm việc đền tội'),
                    'Sau lời khuyên, linh mục trao việc đền tội và đọc lời tha tội. Hãy làm việc đền tội sớm và ra về trong bình an.',
                )),
            array('2026-10-05 14:00', 'Các Mối Phúc Thật',
                'Các Mối Phúc là trái tim của Bài Giảng trên núi, vẽ nên chân dung của Đức Giêsu và của người môn đệ.',
                array(
                    'Trong Tin Mừng theo thánh Mátthêu, chương 5, Đức Giêsu lên núi và bắt đầu giảng dạy bằng tám mối phúc: phúc cho ai có tinh thần nghèo khó, ai sầu khổ, ai hiền lành, ai khát khao nên công chính, ai xót thương người, ai có lòng trong sạch, ai xây dựng hòa bình, và ai bị bách hại vì sống công chính.',
                    'Các Mối Phúc đảo ngược cách nghĩ thông thường về hạnh phúc. Hạnh phúc không đến từ quyền lực hay giàu có, mà từ một trái tim mở ra cho Thiên Chúa và cho tha nhân.',
                    'Đức Thánh Cha Phanxicô gọi các Mối Phúc là "căn cước của người Kitô hữu". Mỗi tuần, ta có thể chọn sống một mối phúc cách cụ thể.',
                )),
        ),

        'vui-hoc-kinh-thanh' => array(
            array('2026-09-19 10:00', 'Đố vui: Các sách Tin Mừng',
                'Năm câu hỏi nhỏ giúp bạn ôn lại những điều căn bản về bốn sách Tin Mừng. Bạn trả lời đúng được mấy câu?',
                array(
                    'Hãy thử tự trả lời trước khi xem đáp án bên dưới mỗi câu nhé!',
                    array('qa' => array(
                        array('Tân Ước có bao nhiêu sách Tin Mừng?', 'Bốn sách: theo thánh Mátthêu, Máccô, Luca và Gioan.'),
                        array('Sách Tin Mừng nào ngắn nhất?', 'Tin Mừng theo thánh Máccô, với 16 chương.'),
                        array('Tác giả sách Tin Mừng thứ ba còn viết thêm sách nào?', 'Sách Công vụ Tông đồ, cũng do thánh Luca viết.'),
                        array('Sách Tin Mừng nào mở đầu bằng câu "Lúc khởi đầu đã có Ngôi Lời"?', 'Tin Mừng theo thánh Gioan.'),
                        array('Ba sách Tin Mừng nào được gọi là "Nhất Lãm"?', 'Mátthêu, Máccô và Luca.'),
                    )),
                )),
            array('2026-09-25 10:00', 'Trắc nghiệm: Các dụ ngôn của Chúa Giêsu',
                'Bạn có nhớ các dụ ngôn quen thuộc được ghi lại trong sách Tin Mừng nào không?',
                array(
                    'Mỗi câu dưới đây nhắc đến một dụ ngôn. Hãy đoán xem dụ ngôn đó nằm trong sách Tin Mừng nào.',
                    array('qa' => array(
                        array('Dụ ngôn người Samari nhân hậu.', 'Tin Mừng theo thánh Luca (Lc 10,25-37).'),
                        array('Dụ ngôn người cha nhân hậu và đứa con hoang đàng.', 'Tin Mừng theo thánh Luca, chương 15.'),
                        array('Dụ ngôn mười cô trinh nữ.', 'Tin Mừng theo thánh Mátthêu, chương 25.'),
                        array('Dụ ngôn thợ làm vườn nho giờ thứ mười một.', 'Tin Mừng theo thánh Mátthêu, chương 20.'),
                        array('Dụ ngôn người gieo giống.', 'Có trong cả ba Tin Mừng Nhất Lãm: Mátthêu 13, Máccô 4 và Luca 8.'),
                    )),
                )),
            array('2026-09-30 10:00', 'Đố vui: Các ngôn sứ Cựu Ước',
                'Năm câu đố về những vị ngôn sứ nổi tiếng trong Cựu Ước.',
                array(
                    'Các ngôn sứ là những người được Thiên Chúa gọi để nói thay cho Người. Bạn có nhận ra các ngài qua những câu chuyện dưới đây?',
                    array('qa' => array(
                        array('Ngôn sứ nào ở trong bụng cá ba ngày ba đêm?', 'Ông Giôna.'),
                        array('Ngôn sứ nào được đem lên trời bằng xe lửa và ngựa lửa?', 'Ông Êlia (2 V 2,11).'),
                        array('Ngôn sứ nào loan báo một trinh nữ sẽ sinh con đặt tên là Emmanuel?', 'Ông Isaia (Is 7,14).'),
                        array('Ngôn sứ nào thưa "Con còn trẻ quá" khi được Chúa gọi?', 'Ông Giêrêmia (Gr 1,6).'),
                        array('Ngôn sứ nào bị ném vào hang sư tử mà vẫn bình an?', 'Ông Đaniel.'),
                    )),
                )),
            array('2026-10-02 10:00', 'Ai đã nói câu này?',
                'Những câu nói nổi tiếng trong Tin Mừng — bạn có nhận ra ai đã nói không?',
                array(
                    'Mỗi câu dưới đây là lời của một nhân vật trong Tin Mừng.',
                    array('qa' => array(
                        array('"Vâng, tôi đây là nữ tỳ của Chúa."', 'Đức Maria, khi đáp lời sứ thần Gabriel (Lc 1,38).'),
                        array('"Tôi là tiếng người hô trong hoang địa."', 'Ông Gioan Tẩy Giả (Ga 1,23).'),
                        array('"Lạy Chúa của con, lạy Thiên Chúa của con!"', 'Ông Tôma, khi gặp Chúa Phục Sinh (Ga 20,28).'),
                        array('"Thưa Thầy, xin cho tôi nhìn thấy được."', 'Anh mù Bartimê ở Giêricô (Mc 10,51).'),
                        array('"Thưa Thầy, Thầy biết rõ mọi sự; Thầy biết con yêu mến Thầy."', 'Ông Phêrô bên bờ hồ Tibêria (Ga 21,17).'),
                    )),
                )),
            array('2026-10-03 10:00', 'Những con số trong Kinh Thánh',
                'Kinh Thánh có nhiều con số mang ý nghĩa đặc biệt. Thử sức với năm câu hỏi sau!',
                array(
                    'Các con số trong Kinh Thánh thường mang ý nghĩa biểu tượng: 7 là sự trọn vẹn, 12 là dân Chúa, 40 là thời gian thử thách và chuẩn bị.',
                    array('qa' => array(
                        array('Đức Giêsu ăn chay trong hoang địa bao nhiêu ngày?', 'Bốn mươi ngày.'),
                        array('Đức Giêsu chọn bao nhiêu Tông đồ?', 'Mười hai Tông đồ.'),
                        array('Phải tha thứ cho anh em bao nhiêu lần?', 'Không phải bảy lần, mà bảy mươi lần bảy (Mt 18,22).'),
                        array('Đức Giêsu dùng bao nhiêu chiếc bánh và mấy con cá để nuôi đám đông?', 'Năm chiếc bánh và hai con cá.'),
                        array('Ông Phêrô chối Thầy mấy lần?', 'Ba lần, trước khi gà gáy.'),
                    )),
                )),
            array('2026-10-04 10:00', 'Đố vui: Phụ nữ trong Kinh Thánh',
                'Những người phụ nữ đức tin trong Kinh Thánh — bạn biết được bao nhiêu?',
                array(
                    'Kinh Thánh ghi lại nhiều người phụ nữ can đảm và tin tưởng. Hãy đoán tên các bà qua những gợi ý sau.',
                    array('qa' => array(
                        array('Người con dâu nói với mẹ chồng: "Mẹ đi đâu, con sẽ đi đó".', 'Bà Rút (R 1,16).'),
                        array('Hoàng hậu đã liều mạng vào gặp vua để cứu dân tộc mình.', 'Hoàng hậu Étte.'),
                        array('Người vợ của tổ phụ Abraham, sinh con khi tuổi đã cao.', 'Bà Xara, mẹ của Ixaác.'),
                        array('Người đầu tiên gặp Chúa Phục Sinh và loan báo cho các Tông đồ.', 'Bà Maria Mácđala (Ga 20,18).'),
                        array('Người chị tuyên xưng: "Thầy là Đức Kitô, Con Thiên Chúa" khi em trai qua đời.', 'Chị Mácta, chị của Ladarô (Ga 11,27).'),
                    )),
                )),
        ),

        'huan-quyen' => array(
            array('2026-09-18 15:00', 'Dei Verbum: Hiến chế Tín lý về Mặc khải',
                'Hiến chế Dei Verbum của Công đồng Vaticanô II trình bày cách Thiên Chúa tỏ mình cho con người và vai trò của Kinh Thánh trong đời sống Hội Thánh.',
                array(
                    'Dei Verbum ("Lời Chúa") được Công đồng Vaticanô II công bố ngày 18/11/1965. Văn kiện ngắn gọn với sáu chương nhưng có ảnh hưởng rất lớn đến việc đọc và học hỏi Kinh Thánh trong Hội Thánh.',
                    array('h' => 'Những điểm chính'),
                    'Thiên Chúa tự mặc khải không chỉ bằng lời nói mà bằng cả công trình và biến cố, đạt đến tột đỉnh nơi Đức Giêsu Kitô. Mặc khải được truyền lại qua Thánh Truyền và Thánh Kinh, được Huấn quyền giải thích một cách trung thành.',
                    'Công đồng tha thiết mời gọi mọi tín hữu năng đọc Kinh Thánh, vì "Hội Thánh luôn tôn kính Kinh Thánh như tôn kính chính Thân Thể Chúa". Đọc Lời Chúa mỗi ngày chính là sống tinh thần của Dei Verbum.',
                )),
            array('2026-09-23 15:00', 'Lumen Gentium: Hội Thánh là Dân Thiên Chúa',
                'Hiến chế Tín lý về Hội Thánh Lumen Gentium nhấn mạnh Hội Thánh là Dân Thiên Chúa và mọi người đều được mời gọi nên thánh.',
                array(
                    'Lumen Gentium ("Ánh sáng muôn dân") được công bố ngày 21/11/1964. Văn kiện mở đầu bằng hình ảnh Đức Kitô là ánh sáng muôn dân, và Hội Thánh như bí tích, nghĩa là dấu chỉ và khí cụ của sự kết hợp với Thiên Chúa và hiệp nhất giữa loài người.',
                    'Hiến chế dành cả một chương cho Hội Thánh là Dân Thiên Chúa, trước khi nói về phẩm trật, giáo dân và tu sĩ. Mọi tín hữu đều chung một phẩm giá nhờ bí tích Rửa Tội.',
                    'Đặc biệt, chương V nói về ơn gọi nên thánh phổ quát: mọi Kitô hữu, ở bậc sống nào, đều được mời gọi đạt tới sự trọn lành của đức ái. Sự thánh thiện không dành riêng cho một số ít người.',
                )),
            array('2026-09-27 15:00', 'Sacrosanctum Concilium: Hiến chế về Phụng vụ',
                'Văn kiện đầu tiên của Công đồng Vaticanô II mở đường cho việc canh tân phụng vụ và mời gọi tín hữu tham dự tích cực.',
                array(
                    'Sacrosanctum Concilium được công bố ngày 4/12/1963, là văn kiện đầu tiên của Công đồng. Hiến chế khẳng định phụng vụ là "chóp đỉnh" mà mọi hoạt động của Hội Thánh hướng tới, đồng thời là "nguồn mạch" tuôn trào mọi sức mạnh của Hội Thánh.',
                    'Mong ước lớn của Công đồng là các tín hữu tham dự phụng vụ một cách trọn vẹn, ý thức và tích cực. Vì thế, việc dùng tiếng bản xứ được mở rộng, Lời Chúa được đọc phong phú hơn, và các nghi thức được canh tân cho đơn sơ, dễ hiểu.',
                    'Tham dự tích cực không chỉ là hát hay đọc, mà trước hết là kết hợp tâm hồn với hy tế của Đức Kitô trong mỗi Thánh Lễ.',
                )),
            array('2026-09-30 15:00', 'Evangelii Gaudium: Niềm vui Tin Mừng',
                'Tông huấn đầu tiên của Đức Thánh Cha Phanxicô mời gọi Hội Thánh "đi ra" loan báo Tin Mừng với niềm vui.',
                array(
                    'Evangelii Gaudium được ban hành ngày 24/11/2013. Ngay câu mở đầu, Đức Thánh Cha viết rằng niềm vui Tin Mừng tràn ngập tâm hồn và cuộc sống của những ai gặp gỡ Đức Giêsu.',
                    'Văn kiện mơ ước một Hội Thánh "đi ra", dám đến với các vùng ngoại biên, một Hội Thánh "bị bầm dập, đau đớn và lấm bẩn vì đã ra ngoài đường phố" hơn là một Hội Thánh khép kín vì lo bám vào sự an toàn của mình.',
                    'Tông huấn cũng dành nhiều trang cho bài giảng, cho người nghèo, và cho chiều kích xã hội của việc loan báo Tin Mừng. Mỗi người đã được rửa tội đều là "người môn đệ thừa sai".',
                )),
            array('2026-10-02 15:00', 'Laudato Si\': Chăm sóc ngôi nhà chung',
                'Thông điệp Laudato Si\' của Đức Thánh Cha Phanxicô mời gọi một cuộc hoán cải sinh thái toàn diện.',
                array(
                    'Laudato Si\' được ký ngày 24/5/2015. Tên thông điệp lấy từ Bài ca Anh Mặt Trời của thánh Phanxicô Assisi: "Lạy Chúa, nguyện Chúa được ca tụng". Trái đất được ví như người chị, người mẹ, ngôi nhà chung của mọi người.',
                    'Đức Thánh Cha đề xuất khái niệm "sinh thái học toàn diện": tiếng kêu của trái đất và tiếng kêu của người nghèo gắn liền với nhau. Việc tàn phá môi trường luôn làm người nghèo chịu thiệt nhiều nhất.',
                    'Thông điệp kêu gọi những thay đổi từ chính sách quốc tế đến lối sống cá nhân: tiết kiệm, bớt lãng phí, biết tạ ơn trước bữa ăn, sống chậm lại để chiêm ngắm công trình tạo dựng.',
                )),
            array('2026-10-03 15:00', 'Dilexit Nos: Người đã yêu thương chúng ta',
                'Thông điệp về tình yêu nhân loại và thần linh của Trái Tim Chúa Giêsu, được Đức Thánh Cha Phanxicô công bố năm 2024.',
                array(
                    'Dilexit Nos ("Người đã yêu thương chúng ta", lấy từ Rm 8,37) được công bố ngày 24/10/2024. Đây là thông điệp thứ tư của Đức Thánh Cha Phanxicô, dành trọn cho lòng sùng kính Thánh Tâm Chúa Giêsu.',
                    'Giữa một thế giới vội vã và nhiều chia cắt, thông điệp mời ta trở về với "trái tim", nơi sâu thẳm của con người, nơi ta gặp gỡ chính mình và Thiên Chúa. Trái Tim Đức Kitô là biểu tượng của một tình yêu không giới hạn.',
                    'Văn kiện cũng nhắc lại các thánh như Margarita Maria Alacoque và Têrêsa Hài Đồng Giêsu, và kêu gọi đền đáp tình yêu Chúa bằng việc yêu thương anh chị em, nhất là những người đau khổ.',
                )),
        ),
    ),
);
