/* Lion Bartender — admin: media picker + color sync cho màn hình mùi hương. */
jQuery(function ($) {
  'use strict';

  // Chọn ảnh đại diện qua Thư viện Media.
  $(document).on('click', '.lb-img-pick', function (e) {
    e.preventDefault();
    var $field = $(this).closest('.lb-img-field');
    var frame = wp.media({
      title: 'Chọn ảnh mùi hương',
      multiple: false,
      library: { type: 'image' },
      button: { text: 'Dùng ảnh này' }
    });
    frame.on('select', function () {
      var att = frame.state().get('selection').first().toJSON();
      var url = (att.sizes && att.sizes.medium) ? att.sizes.medium.url : att.url;
      $field.find('.lb-img-id').val(att.id);
      $field.find('.lb-img-preview').attr('src', url).show();
      $field.find('.lb-img-clear').show();
    });
    frame.open();
  });

  // Xóa ảnh.
  $(document).on('click', '.lb-img-clear', function (e) {
    e.preventDefault();
    var $field = $(this).closest('.lb-img-field');
    $field.find('.lb-img-id').val('');
    $field.find('.lb-img-preview').attr('src', '').hide();
    $(this).hide();
  });

  // Đồng bộ ô màu (color picker) <-> ô text hex.
  $(document).on('input change', '.lb-color-sync', function () {
    $(this).closest('td, .form-field').find('input[name="lb_color"]').val($(this).val());
  });
  $(document).on('input change', 'input[name="lb_color"]', function () {
    var v = $(this).val();
    if (/^#[0-9a-fA-F]{6}$/.test(v)) {
      $(this).closest('td, .form-field').find('.lb-color-sync').val(v);
    }
  });

  // Sau khi thêm term mới qua AJAX, reset preview ảnh ở form thêm.
  $(document).ajaxComplete(function (e, xhr, settings) {
    if (settings && settings.data && settings.data.indexOf('action=add-tag') !== -1) {
      var $add = $('#addtag .lb-img-field');
      $add.find('.lb-img-id').val('');
      $add.find('.lb-img-preview').attr('src', '').hide();
      $add.find('.lb-img-clear').hide();
    }
  });
});
