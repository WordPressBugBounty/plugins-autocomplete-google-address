# সম্পন্ন কাজের তালিকা

> প্রতি সেশনের শেষে এখানে নতুন entry যোগ হবে

---

## ২৯ মার্চ ২০২৬ — v5.2.0 → v5.2.2

### নতুন ফিচার (১২টা)
- [x] Map Picker — GPS দিয়ে location ধরে, pin drag করে address বাছাই
- [x] PO Box / APO / Military address detection
- [x] Fluent Forms integration + wizard card
- [x] Ninja Forms integration + wizard card
- [x] Setup Wizard — WooCommerce feature config step + live preview
- [x] Address Verification Webhook (Slack/Zapier)
- [x] White Label Mode (custom admin menu name)
- [x] Checkout Abandonment Tracking
- [x] REST API endpoints (/aga/v1/config + /aga/v1/validate)
- [x] Dark mode CSS support
- [x] RTL language support
- [x] ARIA accessibility (screen reader support)

### বাগ ফিক্স (১০টা)
- [x] iOS/Safari touch event handling
- [x] Race condition in rapid address selection
- [x] Stale API response discard
- [x] Map Picker wrong pin in Mali fix (marker hidden until real location)
- [x] Map Picker removed from admin page
- [x] Disabled select field broken checkmark pattern fix
- [x] Autocomplete dropdown re-trigger after map drag fix
- [x] Null safety for config.formats
- [x] Google Maps API polling timeout (20s max)
- [x] ABSPATH protection on 6 PHP files
- [x] Freemius is_premium flag fix

### পারফরম্যান্স (৩টা)
- [x] Combined 2 DB queries into 1
- [x] Health check cached in 5-min transient
- [x] Async Google Maps loading

### অন্যান্য
- [x] Map Zoom Level control (Settings > Appearance)
- [x] GPS geolocation with IP fallback
- [x] Setup Wizard visible in admin menu
- [x] WooCommerce auto-detect in wizard
- [x] Wizard creates visible form config posts
- [x] Elementor Widget/Form Field — all 4 Pro features added
- [x] Help page updated with Pro features guide
- [x] readme.txt fully updated with Map Picker as #1 feature
- [x] README.md changelog updated

---

## ৩১ মার্চ ২০২৬ — v5.3.0

### নতুন ফিচার (১টা)
- [x] **Visual CSS Selector Tool (F10)** — iframe-based visual picker that lets non-developers click form fields to generate CSS selectors without DevTools
  - Pick button (crosshair icon) next to every selector input field (Pro only)
  - Full-screen modal with iframe loading any site page
  - Page dropdown: homepage, all site pages, WooCommerce checkout
  - Hover highlighting with blue overlay on form fields (input/textarea/select)
  - Click to select — overlay turns green, generates optimal CSS selector
  - Smart selector generation: ID > name attr > class > placeholder > data-* > nth-of-type path
  - Selector preview in modal header with confirm/cancel buttons
  - Admin bar hidden in iframe mode, crosshair cursor
  - Form submissions and link clicks disabled inside iframe
  - Bridge script injected via postMessage for cross-frame communication
  - Responsive modal layout for mobile/tablet
