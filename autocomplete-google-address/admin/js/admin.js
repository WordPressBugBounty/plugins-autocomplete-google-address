(function ($) {
    'use strict';

    $(function () {

        // ---- Select2 for page selector ----
        if ($.fn.select2 && $('#Nish_aga_load_on_pages').length) {
            $('#Nish_aga_load_on_pages').select2({
                placeholder: "Search and select pages...",
                width: '100%'
            });
        }

        // ---- Mode Toggling Logic ----
        var modeRadios = $('input[name="Nish_aga_mode"]');
        var modePanels = $('.aga-mode-panel');

        function toggleModePanels() {
            var selectedMode = modeRadios.filter(':checked').val();
            modePanels.removeClass('active');
            $('#aga-panel-' + selectedMode).addClass('active');
        }

        toggleModePanels();
        modeRadios.on('change', toggleModePanels);

        // ---- Settings Page Tabs ----
        $('.aga-settings-tab').on('click', function () {
            var target = $(this).data('tab');
            $('.aga-settings-tab').removeClass('active');
            $(this).addClass('active');
            $('.aga-settings-panel').removeClass('active');
            $('.aga-settings-panel[data-tab="' + target + '"]').addClass('active');
        });

        // ---- Preset Auto-Fill ----
        function getPresetData() {
            return window.aga_presets || {};
        }

        function applyPreset(presetKey, flash) {
            var presetData = getPresetData();
            if (!presetKey || !presetData[presetKey]) return;

            var preset = presetData[presetKey];
            var selectors = preset.selectors || {};

            // Fill trigger field
            if (selectors.main_selector) {
                $('#aga_main_selector').val(selectors.main_selector);
                if (flash) aga_flashField('#aga_main_selector');
            }

            // Switch to smart mapping mode and toggle panel
            $('#mode_smart_mapping').prop('checked', true);
            toggleModePanels();

            // Fill smart mapping fields
            var fieldMap = {
                'street': '#aga_street_selector',
                'city': '#aga_city_selector',
                'state': '#aga_state_selector',
                'zip': '#aga_zip_selector',
                'country': '#aga_country_selector'
            };

            $.each(fieldMap, function (key, selector) {
                if (selectors[key]) {
                    $(selector).val(selectors[key]);
                    if (flash) aga_flashField(selector);
                }
            });

            // Show helper text
            var $desc = $('#aga_form_preset').closest('.aga-preset-selector').find('.description');
            if (preset.description) {
                var msg = flash ? '<strong style="color: #00a32a;">Fields filled!</strong> ' : '';
                $desc.html(msg + preset.description);
            }
        }

        // Apply on dropdown change
        $('#aga_form_preset').on('change', function () {
            applyPreset($(this).val(), true);
        });

        // "Apply Preset" button
        $('#aga-apply-preset-btn').on('click', function (e) {
            e.preventDefault();
            var key = $('#aga_form_preset').val();
            if (!key) {
                alert('Please select a form plugin first.');
                return;
            }
            applyPreset(key, true);
        });

        function aga_flashField(selector) {
            $(selector).css('background-color', '#e6ffe6');
            setTimeout(function () {
                $(selector).css('background-color', '');
            }, 2000);
        }

        // ---- API Key Validation Button ----
        $('#aga-validate-key-btn').on('click', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var $status = $('#aga-key-status');
            var apiKey = $('#api_key').val();

            if (!apiKey || apiKey.indexOf('\u2022') !== -1) {
                $status.text('Enter a new key first').removeClass('success').addClass('error');
                return;
            }

            $btn.prop('disabled', true);
            $status.text('Checking...').removeClass('success error');

            // Test the API key with a simple geocode request
            $.ajax({
                url: 'https://maps.googleapis.com/maps/api/geocode/json',
                data: {
                    address: 'New York',
                    key: apiKey
                },
                success: function (response) {
                    if (response.status === 'OK') {
                        $status.text('Valid API key').removeClass('error').addClass('success');
                    } else if (response.status === 'REQUEST_DENIED') {
                        $status.text('Invalid key or API not enabled').removeClass('success').addClass('error');
                    } else {
                        $status.text('Key works but returned: ' + response.status).removeClass('error').addClass('success');
                    }
                },
                error: function () {
                    $status.text('Could not validate key').removeClass('success').addClass('error');
                },
                complete: function () {
                    $btn.prop('disabled', false);
                }
            });
        });

    });

})(jQuery);
