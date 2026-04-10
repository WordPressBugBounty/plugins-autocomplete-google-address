# পারফরম্যান্স উন্নতির চেকলিস্ট

> প্লাগিন: Autocomplete Google Address (Premium)
> ভার্সন: 5.2.2 (স্ট্যাবল)
> আপডেট: ২৯ মার্চ ২০২৬

---

## অগ্রাধিকার: উচ্চ

### P1. Google Maps API পোলিং অপটিমাইজ
- **ফাইল:** `public/js/frontend.js` (লাইন 61-89)
- **সমস্যা:** প্রতি 100ms এ setInterval চালায় 20 সেকেন্ড পর্যন্ত (200 বার)
- **সমাধান:** MutationObserver বা `requestAnimationFrame` ব্যবহার করা
- **প্রভাব:** CPU 50% কম ব্যবহার হবে
- **সময়:** 45 মিনিট

### P2. Dropdown Render এ DocumentFragment
- **ফাইল:** `public/js/frontend.js` — `renderDropdown` ফাংশন
- **সমস্যা:** প্রতিটা suggestion আলাদাভাবে DOM এ append করা হচ্ছে — browser reflow হচ্ছে বারবার
- **সমাধান:** DocumentFragment তৈরি করে সব item যোগ করে, তারপর একবারে DOM এ append করা
- **প্রভাব:** টাইপিং আরও স্মুথ হবে
- **সময়:** 30 মিনিট

### P3. IP Geolocation Cache
- **ফাইল:** `public/js/frontend.js` — `ipGeolocate` ফাংশন
- **সমস্যা:** প্রতি পেজ লোডে ipapi.co তে API call হচ্ছে
- **সমাধান:** localStorage এ cache করা (24 ঘণ্টা TTL)
- **প্রভাব:** ম্যাপ তাৎক্ষণিক লোড হবে repeat visit এ
- **সময়:** 30 মিনিট

---

## অগ্রাধিকার: মাঝারি

### P4. Country Centers ডাটা সরানো
- **ফাইল:** `public/js/frontend.js` (লাইন 380-425)
- **সমস্যা:** 40+ country object JS তে hardcoded — প্রতি পেজে লোড হচ্ছে
- **সমাধান:** `aga_frontend_data` তে move করা, শুধু restricted country পাঠানো
- **প্রভাব:** প্রতি পেজে 2KB JS কম
- **সময়:** 20 মিনিট

### P5. CSS Animation Pause
- **ফাইল:** `public/css/frontend.css`
- **সমস্যা:** Loading spinner ও skeleton animation ক্রমাগত চলে dropdown hide থাকলেও
- **সমাধান:** `animation-play-state: paused` যোগ করা যখন dropdown বন্ধ
- **প্রভাব:** মোবাইলে ব্যাটারি বাঁচবে
- **সময়:** 15 মিনিট

### P6. Inline Style গুলো CSS এ সরানো
- **ফাইল:** `public/js/frontend.js` (লাইন 253)
- **সমস্যা:** `mainInput.style.paddingRight = '38px'` hardcoded
- **সমাধান:** `aga-has-geolocation` CSS class ব্যবহার করা
- **প্রভাব:** অন্য plugin এর CSS এর সাথে conflict কম
- **সময়:** 20 মিনিট

---

## মোট আনুমানিক সময়: 2 ঘণ্টা 40 মিনিট
