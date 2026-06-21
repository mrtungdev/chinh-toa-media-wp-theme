/**
 * Editor script (no-build) cho block "Lời Chúa: Câu ghi nhớ" (chinhtoa/loichua-card).
 *
 * Dùng các global của WordPress (wp.blocks, wp.element, wp.blockEditor, wp.components,
 * wp.serverSideRender) — KHÔNG JSX, KHÔNG import, không cần webpack. Block là dynamic:
 * save() trả null, mặt trước do render_callback PHP (ct_loichua_card_block_render) lo;
 * trong editor xem trước bằng ServerSideRender (tự gửi context.postId nhờ usesContext).
 */
(function (wp) {
  var el = wp.element.createElement;
  var Fragment = wp.element.Fragment;
  var __ = wp.i18n.__;
  var registerBlockType = wp.blocks.registerBlockType;

  var be = wp.blockEditor || wp.editor;
  var InspectorControls = be.InspectorControls;
  var useBlockProps = be.useBlockProps;
  var PanelColorSettings = be.PanelColorSettings;

  var cmp = wp.components;
  var PanelBody = cmp.PanelBody;
  var TextControl = cmp.TextControl;
  var TextareaControl = cmp.TextareaControl;
  var SelectControl = cmp.SelectControl;

  var ServerSideRender = wp.serverSideRender;

  registerBlockType('chinhtoa/loichua-card', {
    edit: function (props) {
      var a = props.attributes;
      var set = props.setAttributes;
      var blockProps = useBlockProps();
      var isDynamic = a.mode === 'dynamic';

      function setStr(key) {
        return function (v) {
          var o = {};
          o[key] = v;
          set(o);
        };
      }
      function setInt(key) {
        return function (v) {
          var o = {};
          o[key] = parseInt(v, 10) || 0;
          set(o);
        };
      }

      var sourcePanel = el(
        PanelBody,
        { title: __('Nguồn nội dung', 'chinhtoa'), initialOpen: true },
        el(SelectControl, {
          label: __('Chế độ', 'chinhtoa'),
          value: a.mode,
          options: [
            { label: __('Nhập tay (Static)', 'chinhtoa'), value: 'static' },
            { label: __('Tự động từ bài viết (Dynamic)', 'chinhtoa'), value: 'dynamic' }
          ],
          onChange: setStr('mode')
        }),
        isDynamic &&
          el(SelectControl, {
            label: __('Lấy từ', 'chinhtoa'),
            value: a.source,
            options: [
              { label: __('Mới nhất trong chuyên mục', 'chinhtoa'), value: 'category' },
              { label: __('Bài đang xem', 'chinhtoa'), value: 'current' },
              { label: __('Bài viết cụ thể', 'chinhtoa'), value: 'post' }
            ],
            onChange: setStr('source')
          }),
        isDynamic &&
          a.source === 'category' &&
          el(TextControl, {
            label: __('ID chuyên mục (0 = mọi chuyên mục)', 'chinhtoa'),
            type: 'number',
            value: a.sourceCategory,
            onChange: setInt('sourceCategory')
          }),
        isDynamic &&
          a.source === 'post' &&
          el(TextControl, {
            label: __('ID bài viết', 'chinhtoa'),
            type: 'number',
            value: a.sourcePostId,
            onChange: setInt('sourcePostId')
          })
      );

      var contentPanel = el(
        PanelBody,
        { title: __('Nội dung thẻ', 'chinhtoa'), initialOpen: true },
        el(TextControl, {
          label: __('Nhãn (LABEL)', 'chinhtoa'),
          value: a.label,
          onChange: setStr('label'),
          help: __('Ví dụ: CÂU GHI NHỚ', 'chinhtoa')
        }),
        el(TextareaControl, {
          label: __('Câu Lời Chúa', 'chinhtoa'),
          value: a.quote,
          onChange: setStr('quote'),
          help: isDynamic ? __('Để trống = lấy từ metabox bài viết (hoặc mô tả).', 'chinhtoa') : ''
        }),
        el(TextControl, {
          label: __('Trích dẫn (CITATION)', 'chinhtoa'),
          value: a.citation,
          onChange: setStr('citation'),
          help: isDynamic
            ? __('Để trống = lấy từ metabox bài viết (hoặc tiêu đề).', 'chinhtoa')
            : __('Ví dụ: Mt 6, 33', 'chinhtoa')
        })
      );

      var colorPanel = el(PanelColorSettings, {
        title: __('Màu sắc', 'chinhtoa'),
        initialOpen: false,
        colorSettings: [
          { value: a.bgColor, onChange: setStr('bgColor'), label: __('Màu nền', 'chinhtoa') },
          { value: a.textColor, onChange: setStr('textColor'), label: __('Màu chữ (câu)', 'chinhtoa') },
          { value: a.accentColor, onChange: setStr('accentColor'), label: __('Màu nhấn (nhãn + trích dẫn)', 'chinhtoa') }
        ]
      });

      return el(
        Fragment,
        null,
        el(InspectorControls, null, sourcePanel, contentPanel, colorPanel),
        el(
          'div',
          blockProps,
          el(ServerSideRender, {
            block: 'chinhtoa/loichua-card',
            attributes: a
          })
        )
      );
    },
    save: function () {
      return null; // dynamic block — mặt trước do PHP render_callback lo.
    }
  });
})(window.wp);
