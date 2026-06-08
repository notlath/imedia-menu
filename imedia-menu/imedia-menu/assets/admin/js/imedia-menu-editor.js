(function ($) {
  'use strict';

  $(document).ready(function () {
    $('.imedia-open-builder').on('click', function () {
      var itemId = $(this).data('item-id');
      var menuId = $('#menu').length ? parseInt($('#menu').val(), 10) : 0;
      if (isNaN(menuId)) menuId = 0;

      var modal = $('#imedia-panel-builder-modal');
      if (!modal.length) return;

      modal.data('item-id', itemId);
      modal.data('menu-id', menuId);
      $('body').addClass('imm-builder-open');
      modal.attr('aria-hidden', 'false');
      modal.addClass('imm-builder-modal--open');

      $(document).trigger('imedia:open-builder', [{ itemId: itemId, menuId: menuId }]);
    });

    $(document).on('imedia:close-builder', function () {
      var modal = $('#imedia-panel-builder-modal');
      $('body').removeClass('imm-builder-open');
      modal.attr('aria-hidden', 'true');
      modal.removeClass('imm-builder-modal--open');
      modal.removeData('item-id');
      modal.removeData('menu-id');
    });

    $(document).on('click', '.imedia-export-btn', function () {
      var data = {
        action: 'imedia_menu_export',
        nonce: imediaMenuEditor.nonce
      };

      $.post(imediaMenuEditor.ajaxUrl, data, function (response) {
        if (response.success) {
          var blob = new Blob([response.data.json], { type: 'application/json' });
          var a = document.createElement('a');
          a.href = URL.createObjectURL(blob);
          a.download = 'imedia-menu-export-' + new Date().toISOString().slice(0, 10) + '.json';
          a.click();
        }
      });
    });

    $(document).on('click', '.imedia-import-btn', function () {
      var input = $(this).siblings('.imedia-import-input')[0];
      if (!input || !input.files || !input.files[0]) {
        alert('Please select a JSON file to import.');
        return;
      }

      var file = input.files[0];
      var reader = new FileReader();

      reader.onload = function (e) {
        var data = {
          action: 'imedia_menu_import',
          nonce: imediaMenuEditor.nonce,
          json: e.target.result
        };

        $.post(imediaMenuEditor.ajaxUrl, data, function (response) {
          if (response.success) {
            alert('Import completed successfully.');
          } else {
            alert('Import failed: ' + (response.data.message || 'Unknown error'));
          }
        });
      };

      reader.readAsText(file);
    });

    $(document).on('click', '.imedia-megamenu-import-btn', function () {
      var btn = $(this);
      var originalText = btn.text();
      btn.prop('disabled', true).text('Importing...');

      var data = {
        action: 'imedia_menu_import_megamenu',
        nonce: imediaMenuEditor.nonce
      };

      $.post(imediaMenuEditor.ajaxUrl, data, function (response) {
        btn.prop('disabled', false).text(originalText);

        if (response.success) {
          alert(
            'Megamenu import completed.\n\n' +
            'Settings: ' + (response.data.settings ? 'Yes' : 'No') + '\n' +
            'Locations: ' + response.data.locations + '\n' +
            'Menu items: ' + response.data.items + '\n' +
            'Toggle blocks: ' + response.data.toggle_blocks
          );
        } else {
          var errors = response.data && response.data.errors
            ? '\n\nErrors:\n' + response.data.errors.join('\n')
            : '';
          alert('Megamenu import failed.' + errors);
        }
      });
    });
  });

})(jQuery);
