=== Autocomplete Google Address ===
Contributors: nishatbd31, freemius
Tags: google, maps, places, autocomplete, address, form, woocommerce, contact form 7
Requires at least: 5.4
Tested up to: 6.9
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add Google Places address autocomplete to any existing form in WordPress using a selector-based mapping builder.

== Description ==

Tired of manually typing addresses? Autocomplete Google Address integrates the power of Google Places Autocomplete with any form on your WordPress site.

This plugin doesn't force you to create new forms. Instead, it provides a powerful, selector-based "form builder" that lets you map Google's rich address data to your *existing* form fields. It's compatible with WooCommerce, Contact Form 7, WPForms, Gravity Forms, and virtually any other form.

**Key Features:**

*   **Works with Any Form:** Add address autocomplete to checkout fields, contact forms, registration forms, and more.
*   **Selector-Based Mapping:** A simple but powerful UI lets you connect Google Address components to your form fields using CSS selectors (like `#billing_address` or `.shipping-street`).
*   **Two Powerful Modes:**
    *   **Single Line Mode:** A single field autocompletes the full, formatted address. Perfect for simple address fields.
    *   **Smart Mapping Mode:** One field triggers the autocomplete, and the plugin intelligently fills multiple fields like Street, City, State, Zip, and Country.
*   **Unlimited Configurations:** Create as many mapping configurations as you need. You can have different setups for your checkout form and your contact form on the same site.
*   **Developer Friendly:** Use the `[aga_form id="123"]` shortcode or the `aga_render_form_config(123)` PHP function to apply configurations exactly where you need them.
*   **Conflict Prevention:** Includes an option to prevent the plugin from loading the Google Maps API if another plugin or your theme already does.

== Installation ==

1.  Upload the `autocomplete-google-address` folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Go to **Google Address > Settings** and enter your Google Maps API Key. You must enable the **Places API** and **Maps JavaScript API** for your key in the Google Cloud Console.
4.  Go to **Google Address > Add New** to create your first form configuration.
5.  Follow the instructions on the Help page (**Google Address > Help**) to find your form field selectors and set up your mapping.
6.  Place the generated shortcode (e.g., `[aga_form id="123"]`) on the page where your form is located.

== Frequently Asked Questions ==

= Does this work with WooCommerce checkout? =

Yes. You can use the "Smart Mapping" mode to map the address components to the WooCommerce billing and shipping address fields.

= Do I need a Google Maps API Key? =

Yes, a Google Maps API key is required. You can get one from the [Google Cloud Platform Console](https://console.cloud.google.com/). You must enable the "Places API" and "Maps JavaScript API" for your key.

= Can I restrict results to a specific country? =

Yes. You can set a default country restriction in the plugin's global Settings page.

== Screenshots ==

1.  The configuration builder interface.
2.  The global settings page.
3.  Example of autocomplete on a frontend form.

== Changelog ==

= 1.0.0 =
*   Initial release.

== Upgrade Notice ==

= 1.0.0 =
*   The first version of the plugin. No upgrade notices yet.
