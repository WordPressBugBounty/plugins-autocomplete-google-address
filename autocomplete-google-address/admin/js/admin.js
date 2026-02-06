(function ($) {
    'use strict';

    $(function () {

        // Initialize Select2 for the page selector
        if ($.fn.select2) {
            $('#Nish_aga_load_on_pages').select2({
                placeholder: "Search and select pages...",
                width: '100%'
            });
        }

        // --- Mode Toggling Logic ---
        var modeRadios = $('input[name="Nish_aga_mode"]');
        var modePanels = $('.aga-mode-panel');

        function toggleModePanels() {
            var selectedMode = modeRadios.filter(':checked').val();
            
            modePanels.removeClass('active');
            $('#aga-panel-' + selectedMode).addClass('active');
        }

        // Run on page load
        toggleModePanels();

        // Bind to change event
        modeRadios.on('change', toggleModePanels);

    });

})(jQuery);