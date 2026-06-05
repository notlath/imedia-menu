(function ($) {
  'use strict';

  $(document).ready(function () {
    $('.imedia-open-builder').on('click', function () {
      var itemId = $(this).data('item-id');
      var url = new URL(window.location.href);
      url.searchParams.set('page', 'imedia-menu');
      url.searchParams.set('tab', 'builder');
      url.searchParams.set('item_id', itemId);
      window.location.href = url.toString();
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
  });

})(jQuery);
