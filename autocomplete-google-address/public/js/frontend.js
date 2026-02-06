(function ($) {
    'use strict';

    var aga = {
        /**
         * Attempts to initialize the autocomplete functionality.
         * It will wait until the Google Maps API is fully loaded.
         */
        init: function () {
            if (typeof window.aga_form_configs === 'undefined' || window.aga_form_configs.length === 0) {
                return;
            }

            // The Google Maps script is loaded with async and defer.
            // We need to wait for it to be ready.
            var checkGoogle = setInterval(function () {
                if (typeof window.google !== 'undefined' && typeof window.google.maps !== 'undefined' && typeof window.google.maps.places !== 'undefined') {
                    clearInterval(checkGoogle);
                    aga.run();
                }
            }, 100);
        },

        /**
         * Executes the main logic after confirming the API is ready.
         */
        run: function() {
            window.aga_form_configs.forEach(function (config) {
                aga.setupAutocomplete(config);
            });
        },

        /**
         * Set up autocomplete for a single form configuration.
         * @param {object} config The configuration object for a form.
         */
        setupAutocomplete: function (config) {
            var mainInput = document.querySelector(config.main_selector);
            if (!mainInput) {
                console.warn('Autocomplete Google Address: Main input not found for selector:', config.main_selector);
                return;
            }

            var options = {};
            if (config.component_restrictions && Object.keys(config.component_restrictions).length > 0) {
                options.componentRestrictions = config.component_restrictions;
            }

            var autocomplete = new google.maps.places.Autocomplete(mainInput, options);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }
                aga.applyMapping(place, config);
            });
        },

        /**
         * Apply the address mapping based on the configuration.
         * @param {object} place The PlaceResult object from Google.
         * @param {object} config The configuration object for the form.
         */
        applyMapping: function (place, config) {
            if (config.selectors.lat) {
                aga.setFieldValue(config.selectors.lat, place.geometry.location.lat());
            }
            if (config.selectors.lng) {
                aga.setFieldValue(config.selectors.lng, place.geometry.location.lng());
            }
            if (config.selectors.place_id) {
                aga.setFieldValue(config.selectors.place_id, place.place_id);
            }

            if (config.mode === 'smart_mapping') {
                var components = aga.parseAddressComponents(place.address_components);

                if (config.selectors.street) {
                    aga.setFieldValue(config.selectors.street, (components.street_number + ' ' + components.route).trim());
                }
                if (config.selectors.city) {
                    aga.setFieldValue(config.selectors.city, components.locality || components.sublocality || '');
                }
                if (config.selectors.state) {
                    var stateValue = (config.formats.state === 'short') ? components.administrative_area_level_1_short : components.administrative_area_level_1_long;
                    aga.setFieldValue(config.selectors.state, stateValue);
                }
                if (config.selectors.zip) {
                    aga.setFieldValue(config.selectors.zip, components.postal_code);
                }
                if (config.selectors.country) {
                    var countryValue = (config.formats.country === 'short') ? components.country_short : components.country_long;
                    aga.setFieldValue(config.selectors.country, countryValue);
                }
            }
        },

        setFieldValue: function (selector, value) {
            if (!selector || value === undefined) return;
            var field = document.querySelector(selector);
            if (field) {
                field.value = value;
                var event = new Event('change', { bubbles: true });
                field.dispatchEvent(event);
            } else {
                console.warn('Autocomplete Google Address: Field not found for selector:', selector);
            }
        },

        parseAddressComponents: function (components) {
            var parsed = {};
            components.forEach(function (component) {
                var type = component.types[0];
                switch (type) {
                    case 'street_number':
                        parsed.street_number = component.long_name;
                        break;
                    case 'route':
                        parsed.route = component.long_name;
                        break;
                    case 'locality':
                        parsed.locality = component.long_name;
                        break;
                    case 'sublocality_level_1':
                    case 'sublocality':
                        if (!parsed.locality) {
                            parsed.sublocality = component.long_name;
                        }
                        break;
                    case 'administrative_area_level_1':
                        parsed.administrative_area_level_1_long = component.long_name;
                        parsed.administrative_area_level_1_short = component.short_name;
                        break;
                    case 'country':
                        parsed.country_long = component.long_name;
                        parsed.country_short = component.short_name;
                        break;
                    case 'postal_code':
                        parsed.postal_code = component.long_name;
                        break;
                }
            });
            return parsed;
        }
    };

    // This will run when the DOM is ready.
    $(function () {
        aga.init();
    });

})(jQuery);