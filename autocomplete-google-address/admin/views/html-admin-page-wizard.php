<?php
defined( 'ABSPATH' ) || exit;

$is_paying = function_exists( 'google_autocomplete' ) && google_autocomplete()->is_paying();
?>
<style>
    /* Hide WP admin clutter on wizard page */
    #wpcontent { padding-left: 0 !important; }
    #wpbody-content > .notice,
    #wpbody-content > .updated,
    #wpbody-content > .error,
    .update-nag { display: none !important; }

    .aga-wizard-wrap {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        background: #f0f0f1;
        padding: 60px 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }

    .aga-wizard-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        max-width: 700px;
        width: 100%;
        padding: 48px 40px;
    }

    .aga-wizard-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 36px;
        gap: 8px;
    }

    .aga-wizard-step-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ddd;
        transition: background 0.3s;
    }

    .aga-wizard-step-indicator.active {
        background: #2271b1;
    }

    .aga-wizard-step-indicator.completed {
        background: #00a32a;
    }

    .aga-wizard-heading {
        font-size: 24px;
        font-weight: 600;
        color: #1d2327;
        margin: 0 0 8px;
        text-align: center;
    }

    .aga-wizard-subheading {
        font-size: 14px;
        color: #646970;
        margin: 0 0 32px;
        text-align: center;
    }

    .aga-wizard-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #1d2327;
        margin-bottom: 6px;
    }

    .aga-wizard-input {
        width: 100%;
        padding: 10px 14px;
        font-size: 14px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }

    .aga-wizard-input:focus {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        outline: none;
    }

    .aga-wizard-helper {
        font-size: 13px;
        color: #646970;
        margin-top: 8px;
    }

    .aga-wizard-helper a {
        color: #2271b1;
        text-decoration: none;
    }

    .aga-wizard-helper a:hover {
        text-decoration: underline;
    }

    .aga-wizard-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #2271b1;
        color: #fff;
        border: none;
        padding: 12px 32px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.2s;
        min-width: 140px;
    }

    .aga-wizard-btn:hover {
        background: #135e96;
    }

    .aga-wizard-btn:disabled {
        background: #a7aaad;
        cursor: not-allowed;
    }

    .aga-wizard-btn-wrap {
        text-align: center;
        margin-top: 32px;
    }

    .aga-wizard-error {
        color: #d63638;
        font-size: 13px;
        margin-top: 6px;
        display: none;
    }

    /* Radio cards */
    .aga-wizard-radio-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .aga-wizard-radio-card {
        position: relative;
        border: 2px solid #dcdcde;
        border-radius: 6px;
        padding: 16px;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .aga-wizard-radio-card:hover {
        border-color: #2271b1;
    }

    .aga-wizard-radio-card.selected {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        background: #f0f6fc;
    }

    .aga-wizard-radio-card.disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    .aga-wizard-radio-card.disabled:hover {
        border-color: #dcdcde;
    }

    .aga-wizard-radio-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .aga-wizard-radio-card-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #1d2327;
        margin-bottom: 4px;
    }

    .aga-wizard-radio-card-icon {
        font-size: 18px;
        width: 24px;
        text-align: center;
    }

    .aga-wizard-radio-card-desc {
        font-size: 13px;
        color: #646970;
        padding-left: 32px;
    }

    .aga-wizard-pro-badge {
        display: inline-block;
        background: #dba617;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 2px 6px;
        border-radius: 3px;
        letter-spacing: 0.5px;
    }

    /* Success step */
    .aga-wizard-success-icon {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .aga-wizard-success-icon svg {
        width: 64px;
        height: 64px;
    }

    .aga-wizard-success-desc {
        text-align: center;
        font-size: 14px;
        color: #646970;
        margin: 0 0 8px;
        line-height: 1.6;
    }

    /* Spinner */
    .aga-wizard-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: aga-wizard-spin 0.6s linear infinite;
    }

    @keyframes aga-wizard-spin {
        to { transform: rotate(360deg); }
    }

    /* Step visibility */
    .aga-wizard-step { display: none; }
    .aga-wizard-step.active { display: block; }
</style>

<div class="aga-wizard-wrap">
    <div class="aga-wizard-card">

        <!-- Step indicators -->
        <div class="aga-wizard-steps">
            <div class="aga-wizard-step-indicator active" data-step="1"></div>
            <div class="aga-wizard-step-indicator" data-step="2"></div>
            <div class="aga-wizard-step-indicator" data-step="3"></div>
        </div>

        <!-- Step 1: API Key -->
        <div class="aga-wizard-step active" id="aga-wizard-step-1">
            <h1 class="aga-wizard-heading"><?php esc_html_e( 'Welcome to Autocomplete Google Address', 'autocomplete-google-address' ); ?></h1>
            <p class="aga-wizard-subheading"><?php esc_html_e( "Let's get you set up in under a minute.", 'autocomplete-google-address' ); ?></p>

            <label class="aga-wizard-label" for="aga-wizard-api-key"><?php esc_html_e( 'Google Maps API Key', 'autocomplete-google-address' ); ?></label>
            <input type="text" id="aga-wizard-api-key" class="aga-wizard-input" placeholder="AIza..." autocomplete="off" />
            <div class="aga-wizard-error" id="aga-wizard-api-error"><?php esc_html_e( 'Please enter your API key to continue.', 'autocomplete-google-address' ); ?></div>
            <p class="aga-wizard-helper">
                <?php
                printf(
                    /* translators: %s: link to Google Cloud Console */
                    esc_html__( "Don't have an API key? %s", 'autocomplete-google-address' ),
                    '<a href="https://console.cloud.google.com/apis/credentials" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Get one from Google Cloud Console', 'autocomplete-google-address' ) . '</a>'
                );
                ?>
            </p>

            <div class="aga-wizard-btn-wrap">
                <button type="button" class="aga-wizard-btn" id="aga-wizard-next-1"><?php esc_html_e( 'Next', 'autocomplete-google-address' ); ?></button>
            </div>
        </div>

        <!-- Step 2: Form Type -->
        <div class="aga-wizard-step" id="aga-wizard-step-2">
            <h1 class="aga-wizard-heading"><?php esc_html_e( 'What form are you using?', 'autocomplete-google-address' ); ?></h1>
            <p class="aga-wizard-subheading"><?php esc_html_e( 'Select the form plugin you want to integrate with.', 'autocomplete-google-address' ); ?></p>

            <div class="aga-wizard-radio-cards">
                <!-- WooCommerce -->
                <label class="aga-wizard-radio-card <?php echo ! $is_paying ? 'disabled' : ''; ?>" data-value="woocommerce">
                    <input type="radio" name="aga_wizard_form_type" value="woocommerce" <?php echo ! $is_paying ? 'disabled' : ''; ?> />
                    <div class="aga-wizard-radio-card-title">
                        <span class="aga-wizard-radio-card-icon dashicons dashicons-cart"></span>
                        <?php esc_html_e( 'WooCommerce', 'autocomplete-google-address' ); ?>
                        <?php if ( ! $is_paying ) : ?>
                            <span class="aga-wizard-pro-badge"><?php esc_html_e( 'Pro', 'autocomplete-google-address' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="aga-wizard-radio-card-desc"><?php esc_html_e( 'Auto-detect checkout address fields', 'autocomplete-google-address' ); ?></div>
                </label>

                <!-- Contact Form 7 -->
                <label class="aga-wizard-radio-card <?php echo ! $is_paying ? 'disabled' : ''; ?>" data-value="cf7">
                    <input type="radio" name="aga_wizard_form_type" value="cf7" <?php echo ! $is_paying ? 'disabled' : ''; ?> />
                    <div class="aga-wizard-radio-card-title">
                        <span class="aga-wizard-radio-card-icon dashicons dashicons-email"></span>
                        <?php esc_html_e( 'Contact Form 7', 'autocomplete-google-address' ); ?>
                        <?php if ( ! $is_paying ) : ?>
                            <span class="aga-wizard-pro-badge"><?php esc_html_e( 'Pro', 'autocomplete-google-address' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="aga-wizard-radio-card-desc"><?php esc_html_e( 'Popular free form plugin', 'autocomplete-google-address' ); ?></div>
                </label>

                <!-- WPForms -->
                <label class="aga-wizard-radio-card <?php echo ! $is_paying ? 'disabled' : ''; ?>" data-value="wpforms">
                    <input type="radio" name="aga_wizard_form_type" value="wpforms" <?php echo ! $is_paying ? 'disabled' : ''; ?> />
                    <div class="aga-wizard-radio-card-title">
                        <span class="aga-wizard-radio-card-icon dashicons dashicons-feedback"></span>
                        <?php esc_html_e( 'WPForms', 'autocomplete-google-address' ); ?>
                        <?php if ( ! $is_paying ) : ?>
                            <span class="aga-wizard-pro-badge"><?php esc_html_e( 'Pro', 'autocomplete-google-address' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="aga-wizard-radio-card-desc"><?php esc_html_e( 'Drag and drop form builder', 'autocomplete-google-address' ); ?></div>
                </label>

                <!-- Gravity Forms -->
                <label class="aga-wizard-radio-card <?php echo ! $is_paying ? 'disabled' : ''; ?>" data-value="gravity">
                    <input type="radio" name="aga_wizard_form_type" value="gravity" <?php echo ! $is_paying ? 'disabled' : ''; ?> />
                    <div class="aga-wizard-radio-card-title">
                        <span class="aga-wizard-radio-card-icon dashicons dashicons-list-view"></span>
                        <?php esc_html_e( 'Gravity Forms', 'autocomplete-google-address' ); ?>
                        <?php if ( ! $is_paying ) : ?>
                            <span class="aga-wizard-pro-badge"><?php esc_html_e( 'Pro', 'autocomplete-google-address' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="aga-wizard-radio-card-desc"><?php esc_html_e( 'Advanced form builder', 'autocomplete-google-address' ); ?></div>
                </label>

                <!-- Elementor Forms -->
                <label class="aga-wizard-radio-card <?php echo ! $is_paying ? 'disabled' : ''; ?>" data-value="elementor">
                    <input type="radio" name="aga_wizard_form_type" value="elementor" <?php echo ! $is_paying ? 'disabled' : ''; ?> />
                    <div class="aga-wizard-radio-card-title">
                        <span class="aga-wizard-radio-card-icon dashicons dashicons-admin-page"></span>
                        <?php esc_html_e( 'Elementor Forms', 'autocomplete-google-address' ); ?>
                        <?php if ( ! $is_paying ) : ?>
                            <span class="aga-wizard-pro-badge"><?php esc_html_e( 'Pro', 'autocomplete-google-address' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="aga-wizard-radio-card-desc"><?php esc_html_e( 'Elementor page builder forms', 'autocomplete-google-address' ); ?></div>
                </label>

                <!-- Manual Setup -->
                <label class="aga-wizard-radio-card" data-value="manual">
                    <input type="radio" name="aga_wizard_form_type" value="manual" />
                    <div class="aga-wizard-radio-card-title">
                        <span class="aga-wizard-radio-card-icon dashicons dashicons-admin-generic"></span>
                        <?php esc_html_e( 'Manual Setup', 'autocomplete-google-address' ); ?>
                    </div>
                    <div class="aga-wizard-radio-card-desc"><?php esc_html_e( "I'll configure CSS selectors myself", 'autocomplete-google-address' ); ?></div>
                </label>
            </div>

            <div class="aga-wizard-error" id="aga-wizard-form-error"><?php esc_html_e( 'Please select a form type to continue.', 'autocomplete-google-address' ); ?></div>

            <div class="aga-wizard-btn-wrap">
                <button type="button" class="aga-wizard-btn" id="aga-wizard-next-2"><?php esc_html_e( 'Continue', 'autocomplete-google-address' ); ?></button>
            </div>
        </div>

        <!-- Step 3: Done -->
        <div class="aga-wizard-step" id="aga-wizard-step-3">
            <div class="aga-wizard-success-icon">
                <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="32" cy="32" r="32" fill="#00a32a"/>
                    <path d="M20 33l8 8 16-16" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="aga-wizard-heading"><?php esc_html_e( "You're all set!", 'autocomplete-google-address' ); ?></h1>
            <p class="aga-wizard-success-desc" id="aga-wizard-done-desc"></p>

            <div class="aga-wizard-btn-wrap">
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=aga_form' ) ); ?>" class="aga-wizard-btn" id="aga-wizard-finish"><?php esc_html_e( 'Go to Dashboard', 'autocomplete-google-address' ); ?></a>
            </div>
        </div>

    </div>
</div>

<script>
(function() {
    var steps      = document.querySelectorAll('.aga-wizard-step');
    var indicators = document.querySelectorAll('.aga-wizard-step-indicator');
    var currentStep = 1;

    function goToStep(step) {
        steps.forEach(function(el) { el.classList.remove('active'); });
        document.getElementById('aga-wizard-step-' + step).classList.add('active');

        indicators.forEach(function(dot, i) {
            var s = i + 1;
            dot.classList.remove('active', 'completed');
            if (s < step) {
                dot.classList.add('completed');
            } else if (s === step) {
                dot.classList.add('active');
            }
        });

        currentStep = step;
    }

    // Step 1 - Next
    document.getElementById('aga-wizard-next-1').addEventListener('click', function() {
        var apiKey = document.getElementById('aga-wizard-api-key').value.trim();
        var error  = document.getElementById('aga-wizard-api-error');

        if (!apiKey) {
            error.style.display = 'block';
            return;
        }

        error.style.display = 'none';
        goToStep(2);
    });

    // Radio card selection
    var cards = document.querySelectorAll('.aga-wizard-radio-card');
    cards.forEach(function(card) {
        card.addEventListener('click', function(e) {
            if (card.classList.contains('disabled')) {
                e.preventDefault();
                return;
            }
            cards.forEach(function(c) { c.classList.remove('selected'); });
            card.classList.add('selected');
            card.querySelector('input[type="radio"]').checked = true;
        });
    });

    // Step 2 - Continue (AJAX save)
    document.getElementById('aga-wizard-next-2').addEventListener('click', function() {
        var selected = document.querySelector('input[name="aga_wizard_form_type"]:checked');
        var error    = document.getElementById('aga-wizard-form-error');

        if (!selected) {
            error.style.display = 'block';
            return;
        }

        error.style.display = 'none';

        var btn      = this;
        var apiKey   = document.getElementById('aga-wizard-api-key').value.trim();
        var formType = selected.value;

        // Loading state
        btn.disabled = true;
        btn.innerHTML = '<span class="aga-wizard-spinner"></span> Saving...';

        var data = new FormData();
        data.append('action', 'aga_save_wizard');
        data.append('nonce', aga_admin_data.nonce);
        data.append('api_key', apiKey);
        data.append('form_type', formType);

        fetch(aga_admin_data.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(function(response) { return response.json(); })
        .then(function(result) {
            btn.disabled = false;
            btn.textContent = '<?php echo esc_js( __( 'Continue', 'autocomplete-google-address' ) ); ?>';

            if (result.success) {
                // Set description and redirect URL on final step
                var desc   = '';
                var finishBtn = document.getElementById('aga-wizard-finish');

                switch (formType) {
                    case 'woocommerce':
                        desc = '<?php echo esc_js( __( 'WooCommerce integration is enabled. Your checkout address fields will autocomplete automatically.', 'autocomplete-google-address' ) ); ?>';
                        break;
                    case 'cf7':
                        desc = '<?php echo esc_js( __( 'A Contact Form 7 configuration has been created and activated globally.', 'autocomplete-google-address' ) ); ?>';
                        break;
                    case 'wpforms':
                        desc = '<?php echo esc_js( __( 'A WPForms configuration has been created and activated globally.', 'autocomplete-google-address' ) ); ?>';
                        break;
                    case 'gravity':
                        desc = '<?php echo esc_js( __( 'A Gravity Forms configuration has been created and activated globally.', 'autocomplete-google-address' ) ); ?>';
                        break;
                    case 'elementor':
                        desc = '<?php echo esc_js( __( 'An Elementor Forms configuration has been created and activated globally.', 'autocomplete-google-address' ) ); ?>';
                        break;
                    case 'manual':
                        desc = '<?php echo esc_js( __( 'Your API key has been saved. You can now create a form configuration with your own CSS selectors.', 'autocomplete-google-address' ) ); ?>';
                        break;
                }

                document.getElementById('aga-wizard-done-desc').textContent = desc;

                if (result.data && result.data.redirect) {
                    finishBtn.href = result.data.redirect;
                }

                goToStep(3);
            } else {
                var msg = (result.data && result.data.message) ? result.data.message : '<?php echo esc_js( __( 'Something went wrong. Please try again.', 'autocomplete-google-address' ) ); ?>';
                alert(msg);
            }
        })
        .catch(function() {
            btn.disabled = false;
            btn.textContent = '<?php echo esc_js( __( 'Continue', 'autocomplete-google-address' ) ); ?>';
            alert('<?php echo esc_js( __( 'Network error. Please try again.', 'autocomplete-google-address' ) ); ?>');
        });
    });

    // Allow Enter key on API key field
    document.getElementById('aga-wizard-api-key').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('aga-wizard-next-1').click();
        }
    });
})();
</script>
