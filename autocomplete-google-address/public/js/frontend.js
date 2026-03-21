(function ($) {
    'use strict';

    var aga = {
        init: function () {
            if (typeof window.aga_form_configs === 'undefined' || window.aga_form_configs.length === 0) {
                return;
            }

            var checkGoogle = setInterval(function () {
                if (typeof window.google !== 'undefined' && typeof window.google.maps !== 'undefined') {
                    clearInterval(checkGoogle);
                    google.maps.importLibrary('places').then(function () {
                        aga.run();
                    });
                }
            }, 100);
        },

        run: function () {
            window.aga_form_configs.forEach(function (config) {
                aga.setupAutocomplete(config);
            });
        },

        setupAutocomplete: function (config) {
            var mainInput = document.querySelector(config.main_selector);
            if (!mainInput) {
                console.warn('Autocomplete Google Address: Main input not found for selector:', config.main_selector);
                return;
            }

            var wrapper = mainInput.parentNode;
            if (window.getComputedStyle(wrapper).position === 'static') {
                wrapper.style.position = 'relative';
            }

            var dropdown = document.createElement('ul');
            dropdown.className = 'aga-autocomplete-dropdown';
            dropdown.style.display = 'none';
            wrapper.appendChild(dropdown);

            var state = {
                sessionToken: new google.maps.places.AutocompleteSessionToken(),
                debounceTimer: null,
                activeIndex: -1,
                isSelecting: false,
                isFetching: false
            };

            mainInput.setAttribute('autocomplete', 'off');

            mainInput.addEventListener('input', function () {
                if (state.isSelecting) return;

                var query = mainInput.value.trim();

                if (state.debounceTimer) {
                    clearTimeout(state.debounceTimer);
                }

                if (query.length < 2) {
                    aga.hideDropdown(dropdown);
                    return;
                }

                state.debounceTimer = setTimeout(function () {
                    aga.fetchSuggestions(query, config, state, dropdown, mainInput);
                }, 300);
            });

            mainInput.addEventListener('keydown', function (e) {
                var items = dropdown.querySelectorAll('.aga-autocomplete-item');
                if (!items.length || dropdown.style.display === 'none') return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    state.activeIndex = Math.min(state.activeIndex + 1, items.length - 1);
                    aga.highlightItem(items, state.activeIndex);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    state.activeIndex = Math.max(state.activeIndex - 1, 0);
                    aga.highlightItem(items, state.activeIndex);
                } else if (e.key === 'Enter' && state.activeIndex >= 0) {
                    e.preventDefault();
                    items[state.activeIndex].click();
                } else if (e.key === 'Escape') {
                    aga.hideDropdown(dropdown);
                    state.activeIndex = -1;
                }
            });

            document.addEventListener('click', function (e) {
                if (!wrapper.contains(e.target)) {
                    aga.hideDropdown(dropdown);
                    state.activeIndex = -1;
                }
            });
        },

        fetchSuggestions: function (query, config, state, dropdown, mainInput) {
            var request = {
                input: query,
                sessionToken: state.sessionToken
            };

            if (config.component_restrictions && config.component_restrictions.country) {
                var country = config.component_restrictions.country;
                request.includedRegionCodes = Array.isArray(country) ? country : [country];
            }

            // Show loading state
            state.isFetching = true;
            aga.showLoading(dropdown, mainInput);

            google.maps.places.AutocompleteSuggestion.fetchAutocompleteSuggestions(request)
                .then(function (result) {
                    state.isFetching = false;
                    var suggestions = result.suggestions || [];
                    if (suggestions.length) {
                        aga.renderDropdown(suggestions, dropdown, mainInput, config, state);
                    } else {
                        aga.showNoResults(dropdown, mainInput);
                    }
                })
                .catch(function (err) {
                    state.isFetching = false;
                    console.warn('Autocomplete Google Address: Suggestion fetch failed:', err);
                    aga.hideDropdown(dropdown);
                });
        },

        showLoading: function (dropdown, mainInput) {
            dropdown.innerHTML = '<li class="aga-autocomplete-status">Searching...</li>';
            aga.positionDropdown(dropdown, mainInput);
            dropdown.style.display = 'block';
        },

        showNoResults: function (dropdown, mainInput) {
            dropdown.innerHTML = '<li class="aga-autocomplete-status">No results found</li>';
            aga.positionDropdown(dropdown, mainInput);
            dropdown.style.display = 'block';
            setTimeout(function () {
                aga.hideDropdown(dropdown);
            }, 2000);
        },

        positionDropdown: function (dropdown, mainInput) {
            var inputRect = mainInput.getBoundingClientRect();
            var spaceBelow = window.innerHeight - inputRect.bottom;
            var dropdownHeight = 250;

            dropdown.style.left = mainInput.offsetLeft + 'px';
            dropdown.style.width = mainInput.offsetWidth + 'px';

            if (spaceBelow < dropdownHeight && inputRect.top > dropdownHeight) {
                // Show above the input
                dropdown.style.top = 'auto';
                dropdown.style.bottom = (mainInput.parentNode.offsetHeight - mainInput.offsetTop) + 'px';
                dropdown.classList.add('aga-dropdown-above');
            } else {
                // Show below the input
                dropdown.style.top = (mainInput.offsetTop + mainInput.offsetHeight) + 'px';
                dropdown.style.bottom = 'auto';
                dropdown.classList.remove('aga-dropdown-above');
            }
        },

        renderDropdown: function (suggestions, dropdown, mainInput, config, state) {
            dropdown.innerHTML = '';
            state.activeIndex = -1;

            suggestions.forEach(function (suggestion, index) {
                var prediction = suggestion.placePrediction;
                if (!prediction) return;

                var li = document.createElement('li');
                li.className = 'aga-autocomplete-item';
                li.textContent = prediction.text.toString();

                li.addEventListener('mouseenter', function () {
                    var items = dropdown.querySelectorAll('.aga-autocomplete-item');
                    aga.highlightItem(items, index);
                });

                li.addEventListener('click', function () {
                    aga.selectPlace(prediction, mainInput, config, state);
                    aga.hideDropdown(dropdown);
                });

                dropdown.appendChild(li);
            });

            // Google attribution (required by ToS)
            var attribution = document.createElement('li');
            attribution.className = 'aga-autocomplete-attribution';
            attribution.innerHTML = '<img src="https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3_hdpi.png" alt="Powered by Google" height="14" />';
            dropdown.appendChild(attribution);

            aga.positionDropdown(dropdown, mainInput);
            dropdown.style.display = 'block';
        },

        selectPlace: function (prediction, mainInput, config, state) {
            var place = prediction.toPlace();

            var fields = ['formattedAddress', 'location', 'id'];
            if (config.mode === 'smart_mapping') {
                fields.push('addressComponents');
            }

            state.isSelecting = true;

            place.fetchFields({ fields: fields }).then(function () {
                mainInput.value = place.formattedAddress || '';
                mainInput.dispatchEvent(new Event('change', { bubbles: true }));

                aga.applyMapping(place, config);

                // Show map preview if enabled
                if (config.show_map_preview && place.location) {
                    aga.showMapPreview(mainInput, place.location, config);
                }

                state.sessionToken = new google.maps.places.AutocompleteSessionToken();

                setTimeout(function () {
                    state.isSelecting = false;
                }, 100);
            });
        },

        showMapPreview: function (mainInput, location, config) {
            var containerId = 'aga-map-preview-' + (config.form_id || 'default');
            var existing = document.getElementById(containerId);
            if (existing) {
                existing.remove();
            }

            var mapContainer = config.map_container_selector
                ? document.querySelector(config.map_container_selector)
                : null;

            var mapDiv = document.createElement('div');
            mapDiv.id = containerId;
            mapDiv.className = 'aga-map-preview';

            if (mapContainer) {
                mapContainer.innerHTML = '';
                mapContainer.appendChild(mapDiv);
            } else {
                mainInput.parentNode.insertBefore(mapDiv, mainInput.nextSibling);
            }

            google.maps.importLibrary('maps').then(function () {
                var map = new google.maps.Map(mapDiv, {
                    center: location,
                    zoom: 15,
                    disableDefaultUI: true,
                    zoomControl: true,
                    mapId: 'aga_preview_map'
                });

                google.maps.importLibrary('marker').then(function () {
                    var marker = new google.maps.marker.AdvancedMarkerElement({
                        map: map,
                        position: location,
                        gmpDraggable: true
                    });

                    marker.addListener('dragend', function () {
                        var newPos = marker.position;
                        aga.reverseGeocode(newPos, mainInput, config);
                    });
                });
            });
        },

        reverseGeocode: function (latLng, mainInput, config) {
            var lat = typeof latLng.lat === 'function' ? latLng.lat() : latLng.lat;
            var lng = typeof latLng.lng === 'function' ? latLng.lng() : latLng.lng;
            var location = { lat: lat, lng: lng };

            // Try Geocoding API first (requires Geocoding API enabled)
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: location }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    var result = results[0];
                    mainInput.value = result.formatted_address || '';
                    mainInput.dispatchEvent(new Event('change', { bubbles: true }));

                    // Update lat/lng with exact drop position
                    aga.updateLatLngFields(location, config);

                    if (config.selectors.place_id) {
                        aga.setFieldValue(config.selectors.place_id, result.place_id);
                    }

                    // Update smart mapping fields
                    if (config.mode === 'smart_mapping' && result.address_components) {
                        var components = aga.parseReverseComponents(result.address_components);
                        aga.applyParsedComponents(components, config);
                    }
                } else {
                    // Geocoding API not enabled — just update coordinates
                    console.warn('Autocomplete Google Address: Geocoding failed (' + status + '). Enable the Geocoding API in Google Cloud Console for drag-to-update.');
                    aga.updateLatLngFields(location, config);
                    mainInput.value = lat.toFixed(6) + ', ' + lng.toFixed(6);
                    mainInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        },

        parseReverseComponents: function (components) {
            var parsed = {
                street_number: '', route: '', locality: '', sublocality: '',
                administrative_area_level_1_long: '', administrative_area_level_1_short: '',
                administrative_area_level_2: '',
                country_long: '', country_short: '', postal_code: ''
            };
            components.forEach(function (c) {
                (c.types || []).forEach(function (type) {
                    switch (type) {
                        case 'street_number': parsed.street_number = c.long_name; break;
                        case 'route': parsed.route = c.long_name; break;
                        case 'locality': parsed.locality = c.long_name; break;
                        case 'sublocality_level_1':
                        case 'sublocality':
                            if (!parsed.locality) parsed.sublocality = c.long_name; break;
                        case 'administrative_area_level_1':
                            parsed.administrative_area_level_1_long = c.long_name;
                            parsed.administrative_area_level_1_short = c.short_name; break;
                        case 'administrative_area_level_2':
                            parsed.administrative_area_level_2 = c.long_name; break;
                        case 'country':
                            parsed.country_long = c.long_name;
                            parsed.country_short = c.short_name; break;
                        case 'postal_code': parsed.postal_code = c.long_name; break;
                    }
                });
            });
            return parsed;
        },

        applyParsedComponents: function (components, config) {
            if (config.selectors.country) {
                var countryPrimary = (config.formats && config.formats.country === 'short') ? components.country_short : components.country_long;
                var countryAlt = (config.formats && config.formats.country === 'short') ? components.country_long : components.country_short;
                aga.setFieldValue(config.selectors.country, countryPrimary, countryAlt);
            }
            if (config.selectors.street) {
                aga.setFieldValue(config.selectors.street, (components.street_number + ' ' + components.route).trim());
            }
            if (config.selectors.city) {
                aga.setFieldValue(config.selectors.city, components.locality || components.sublocality || components.administrative_area_level_2 || '');
            }

            var statePrimary = (config.formats && config.formats.state === 'short') ? components.administrative_area_level_1_short : components.administrative_area_level_1_long;
            var stateAlt = (config.formats && config.formats.state === 'short') ? components.administrative_area_level_1_long : components.administrative_area_level_1_short;
            var zipValue = components.postal_code;

            setTimeout(function () {
                if (config.selectors.state) {
                    aga.setFieldValue(config.selectors.state, statePrimary, stateAlt);
                }
                if (config.selectors.zip) {
                    aga.setFieldValue(config.selectors.zip, zipValue);
                }
            }, 500);
        },

        updateLatLngFields: function (latLng, config) {
            var lat = typeof latLng.lat === 'function' ? latLng.lat() : latLng.lat;
            var lng = typeof latLng.lng === 'function' ? latLng.lng() : latLng.lng;
            if (config.selectors.lat) {
                aga.setFieldValue(config.selectors.lat, lat);
            }
            if (config.selectors.lng) {
                aga.setFieldValue(config.selectors.lng, lng);
            }
        },

        highlightItem: function (items, index) {
            items.forEach(function (item, i) {
                item.classList.toggle('aga-autocomplete-item--active', i === index);
            });
        },

        hideDropdown: function (dropdown) {
            dropdown.style.display = 'none';
            dropdown.innerHTML = '';
        },

        applyMapping: function (place, config) {
            if (config.selectors.lat && place.location) {
                aga.setFieldValue(config.selectors.lat, place.location.lat());
            }
            if (config.selectors.lng && place.location) {
                aga.setFieldValue(config.selectors.lng, place.location.lng());
            }
            if (config.selectors.place_id) {
                aga.setFieldValue(config.selectors.place_id, place.id);
            }

            if (config.mode === 'smart_mapping' && place.addressComponents) {
                var components = aga.parseAddressComponents(place.addressComponents);

                // Set country FIRST — frameworks like WooCommerce re-render
                // state/postcode fields when country changes.
                if (config.selectors.country) {
                    // Pass both short and long so smart matcher can find the right option.
                    var countryPrimary = (config.formats.country === 'short') ? components.country_short : components.country_long;
                    var countryAlt = (config.formats.country === 'short') ? components.country_long : components.country_short;
                    aga.setFieldValue(config.selectors.country, countryPrimary, countryAlt);
                }

                if (config.selectors.street) {
                    aga.setFieldValue(config.selectors.street, (components.street_number + ' ' + components.route).trim());
                }
                if (config.selectors.city) {
                    // Try locality first, then sublocality, then admin_area_level_2 for countries
                    // that use districts (e.g. Bangladesh, India).
                    aga.setFieldValue(config.selectors.city, components.locality || components.sublocality || components.administrative_area_level_2 || '');
                }

                // Delay state and postcode to allow framework re-render after country change.
                var statePrimary = (config.formats.state === 'short') ? components.administrative_area_level_1_short : components.administrative_area_level_1_long;
                var stateAlt = (config.formats.state === 'short') ? components.administrative_area_level_1_long : components.administrative_area_level_1_short;
                var zipValue = components.postal_code;

                setTimeout(function () {
                    if (config.selectors.state) {
                        // Pass both short and long for smart select matching.
                        aga.setFieldValue(config.selectors.state, statePrimary, stateAlt);
                    }
                    if (config.selectors.zip) {
                        aga.setFieldValue(config.selectors.zip, zipValue);
                    }
                }, 500);
            }
        },

        _warnedSelectors: {},

        setFieldValue: function (selector, value, altValue) {
            if (!selector || value === undefined) return;
            var field = document.querySelector(selector);
            if (!field) {
                if (!aga._warnedSelectors[selector]) {
                    aga._warnedSelectors[selector] = true;
                    console.warn('Autocomplete Google Address: Field not found for selector:', selector, '— check your form config.');
                }
                return;
            }

            var finalValue = value;

            // Smart matching for <select> elements (country/state/district dropdowns).
            // Try multiple strategies to find the right option.
            if (field.tagName === 'SELECT') {
                finalValue = aga.findSelectMatch(field, value, altValue) || value;
            }

            // For React-controlled inputs (e.g. WooCommerce block checkout),
            // we must use the native setter to trigger React's change detection.
            var nativeInputValueSetter = Object.getOwnPropertyDescriptor(
                window.HTMLInputElement.prototype, 'value'
            );
            var nativeSelectValueSetter = Object.getOwnPropertyDescriptor(
                window.HTMLSelectElement.prototype, 'value'
            );
            var nativeTextareaValueSetter = Object.getOwnPropertyDescriptor(
                window.HTMLTextAreaElement.prototype, 'value'
            );

            if (field.tagName === 'SELECT' && nativeSelectValueSetter) {
                nativeSelectValueSetter.set.call(field, finalValue);
            } else if (field.tagName === 'TEXTAREA' && nativeTextareaValueSetter) {
                nativeTextareaValueSetter.set.call(field, finalValue);
            } else if (nativeInputValueSetter) {
                nativeInputValueSetter.set.call(field, finalValue);
            } else {
                field.value = finalValue;
            }

            // Dispatch events that both React and vanilla JS listeners pick up.
            field.dispatchEvent(new Event('input', { bubbles: true }));
            field.dispatchEvent(new Event('change', { bubbles: true }));
        },

        /**
         * Smart-match a value against <select> options.
         * Tries: exact value, exact text, case-insensitive, partial text match, alt value.
         * Works for all countries — handles state codes, district names, provinces, etc.
         */
        findSelectMatch: function (select, value, altValue) {
            if (!value && !altValue) return null;
            var options = select.options;
            var valueLower = value ? value.toLowerCase().trim() : '';
            var altLower = altValue ? altValue.toLowerCase().trim() : '';

            // 1. Exact match on option value
            for (var i = 0; i < options.length; i++) {
                if (options[i].value === value) return options[i].value;
            }

            // 2. Exact match on alt value (e.g. short code vs long name)
            if (altValue) {
                for (var i = 0; i < options.length; i++) {
                    if (options[i].value === altValue) return options[i].value;
                }
            }

            // 3. Case-insensitive match on option value
            for (var i = 0; i < options.length; i++) {
                if (options[i].value.toLowerCase() === valueLower) return options[i].value;
                if (altLower && options[i].value.toLowerCase() === altLower) return options[i].value;
            }

            // 4. Exact match on option text
            for (var i = 0; i < options.length; i++) {
                var optText = options[i].text.toLowerCase().trim();
                if (optText === valueLower) return options[i].value;
                if (altLower && optText === altLower) return options[i].value;
            }

            // 5. Partial match — option text contains value or vice versa
            //    e.g. "Dhaka" matches "Dhaka Division" or "Dhaka District"
            for (var i = 0; i < options.length; i++) {
                var optText = options[i].text.toLowerCase().trim();
                if (!optText || !options[i].value) continue;
                if (valueLower && (optText.indexOf(valueLower) !== -1 || valueLower.indexOf(optText) !== -1)) {
                    return options[i].value;
                }
                if (altLower && (optText.indexOf(altLower) !== -1 || altLower.indexOf(optText) !== -1)) {
                    return options[i].value;
                }
            }

            // 6. No match found — return null (the raw value will be used as fallback)
            return null;
        },

        parseAddressComponents: function (components) {
            var parsed = {
                street_number: '',
                route: '',
                locality: '',
                sublocality: '',
                administrative_area_level_1_long: '',
                administrative_area_level_1_short: '',
                administrative_area_level_2: '',
                country_long: '',
                country_short: '',
                postal_code: ''
            };
            components.forEach(function (component) {
                var types = component.types || [];
                types.forEach(function (type) {
                    switch (type) {
                        case 'street_number':
                            parsed.street_number = component.longText || component.long_name || '';
                            break;
                        case 'route':
                            parsed.route = component.longText || component.long_name || '';
                            break;
                        case 'locality':
                            parsed.locality = component.longText || component.long_name || '';
                            break;
                        case 'sublocality_level_1':
                        case 'sublocality':
                            if (!parsed.locality) {
                                parsed.sublocality = component.longText || component.long_name || '';
                            }
                            break;
                        case 'administrative_area_level_1':
                            parsed.administrative_area_level_1_long = component.longText || component.long_name || '';
                            parsed.administrative_area_level_1_short = component.shortText || component.short_name || '';
                            break;
                        case 'administrative_area_level_2':
                            parsed.administrative_area_level_2 = component.longText || component.long_name || '';
                            break;
                        case 'country':
                            parsed.country_long = component.longText || component.long_name || '';
                            parsed.country_short = component.shortText || component.short_name || '';
                            break;
                        case 'postal_code':
                            parsed.postal_code = component.longText || component.long_name || '';
                            break;
                    }
                });
            });
            return parsed;
        }
    };

    $(function () {
        aga.init();
    });

})(jQuery);
