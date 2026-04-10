# ফিচার রোডম্যাপ

> প্লাগিন: Autocomplete Google Address (Premium)
> ভার্সন: 5.3.0 (স্ট্যাবল)
> আপডেট: ২৯ মার্চ ২০২৬

---

## সেশন ১ — সহজ ফিচার (১-২ দিন)

### F1. API Key Validation in Wizard
- **কি:** Setup Wizard এর Step 1 এ key দেওয়ার সাথে সাথে চেক করা কোন কোন Google API enable আছে
- **কেন:** 80% সাপোর্ট টিকেট এই কারণে আসে — "plugin কাজ করছে না" কিন্তু আসলে API enable করেনি
- **সময়:** 2-3 ঘণ্টা
- **বিক্রি প্রভাব:** সাপোর্ট খরচ কমবে, ইউজার সন্তুষ্টি বাড়বে

### F2. Autocomplete Result Cache (localStorage)
- **কি:** সাম্প্রতিক search result localStorage এ cache করা
- **কেন:** একই address বারবার টাইপ করলে API call কম হবে, দ্রুত result আসবে
- **সময়:** 2-3 ঘণ্টা
- **বিক্রি প্রভাব:** ফাস্ট UX = বেশি conversion

### F3. Address Book Expansion (50+ Addresses)
- **কি:** বর্তমানে 5টা saved address — Pro তে 50+ করা
- **কেন:** Returning customer দের জন্য দারুণ, repeat purchase বাড়ায়
- **সময়:** 1 ঘণ্টা (শুধু limit বাড়ানো)
- **বিক্রি প্রভাব:** মাঝারি

### F4. Duplicate Address Warning
- **কি:** একই session এ একই address দিলে সতর্ক করা
- **কেন:** E-commerce fraud কমায়, duplicate order prevention
- **সময়:** 2 ঘণ্টা
- **বিক্রি প্রভাব:** মাঝারি (security feature হিসেবে market করা যায়)

---

## সেশন ২ — মাঝারি ফিচার (৩-৫ দিন)

### F5. CSV Batch Address Validation
- **কি:** CSV file আপলোড করে সব address একসাথে validate করা
- **কেন:** B2B customer দের জন্য killer feature — shipping company, real estate, logistics
- **সময়:** 3 দিন
- **বিক্রি প্রভাব:** উচ্চ — আলাদা $100-200/year চার্জ করা যায়

### F6. Auto Browser Language Detection
- **কি:** ইউজারের browser language অনুযায়ী autocomplete result ভাষা change করা
- **কেন:** Global e-commerce store এর জন্য — ফরাসি customer ফরাসি ভাষায় result পাবে
- **সময়:** 3 ঘণ্টা
- **বিক্রি প্রভাব:** International customer base বাড়বে

### F7. Address Confidence Score
- **কি:** Google এর HIGH/MEDIUM/LOW confidence level badge এ দেখানো
- **কেন:** শুধু ✓/⚠/✗ না, কেন invalid সেটাও বলবে
- **সময়:** 4 ঘণ্টা
- **বিক্রি প্রভাব:** মাঝারি — enterprise customer চাইবে

### F8. Shipping Cost Preview on Map
- **কি:** Map Picker এ pin করলে WooCommerce shipping zone অনুযায়ী cost দেখানো
- **কেন:** WooCommerce store owner দের জন্য killer feature — customer checkout এ cost আগেই দেখতে পাবে
- **সময়:** 5 দিন
- **বিক্রি প্রভাব:** অনেক উচ্চ — WooCommerce Pro+ tier এ $199/year

### F9. Phone Number Auto-Fill
- **কি:** Google Places থেকে business phone number auto-fill করা
- **কেন:** Address + phone একসাথে — checkout দ্রুত হবে
- **সময়:** 3 দিন
- **বিক্রি প্রভাব:** ভালো upsell opportunity

---

## সেশন ৩ — বড় ফিচার (৭+ দিন)

### F10. Visual CSS Selector Tool
- **কি:** ক্লিক করে CSS selector বাছাই — DevTools ছাড়াই
- **কেন:** Non-developer user দের জন্য game changer — CSS জানার দরকার নেই
- **সময়:** 7 দিন
- **বিক্রি প্রভাব:** অনেক উচ্চ — conversion 50%+ বাড়তে পারে

### F11. Formidable Forms Native Integration
- **কি:** Formidable Forms এর জন্য dedicated widget/integration
- **কেন:** নতুন customer base
- **সময়:** 3 দিন
- **বিক্রি প্রভাব:** মাঝারি

### F12. Analytics Export (Slack/Sheets/Zapier)
- **কি:** Analytics data Slack, Google Sheets, বা Zapier এ export করা
- **কেন:** Enterprise customer চাইবে
- **সময়:** 4 দিন
- **বিক্রি প্রভাব:** উচ্চ — Agency tier feature

### F13. Multi-Store Address Sync
- **কি:** একাধিক WordPress site এ address book sync করা
- **কেন:** Agency customer দের জন্য — একবার configure, সব site এ কাজ করবে
- **সময়:** 7 দিন
- **বিক্রি প্রভাব:** উচ্চ — Agency exclusive feature

---

## সম্পন্ন ফিচার ✅

| ফিচার | ভার্সন | তারিখ |
|--------|--------|-------|
| Map Picker (GPS + drag) | 5.2.0 | মার্চ ২০২৬ |
| PO Box Detection | 5.2.0 | মার্চ ২০২৬ |
| Fluent Forms Integration | 5.2.0 | মার্চ ২০২৬ |
| Ninja Forms Integration | 5.2.0 | মার্চ ২০২৬ |
| Webhook (Slack/Zapier) | 5.2.0 | মার্চ ২০২৬ |
| White Label Mode | 5.2.0 | মার্চ ২০২৬ |
| Abandonment Tracking | 5.2.0 | মার্চ ২০২৬ |
| REST API | 5.2.0 | মার্চ ২০২৬ |
| Dark Mode | 5.2.0 | মার্চ ২০২৬ |
| RTL Support | 5.2.0 | মার্চ ২০২৬ |
| ARIA Accessibility | 5.2.0 | মার্চ ২০২৬ |
| iOS/Safari Fix | 5.2.0 | মার্চ ২০২৬ |
| Map Zoom Control | 5.2.1 | মার্চ ২০২৬ |
| GPS Geolocation | 5.2.1 | মার্চ ২০২৬ |
| Visual CSS Selector Tool | 5.3.0 | মার্চ ২০২৬ |
