# কোড কোয়ালিটি চেকলিস্ট

> প্লাগিন: Autocomplete Google Address (Premium)
> ভার্সন: 5.2.2 (স্ট্যাবল)
> আপডেট: ২৯ মার্চ ২০২৬

---

## অগ্রাধিকার: উচ্চ

### Q1. querySelector Error Handling
- **ফাইল:** `public/js/frontend.js` (লাইন 104)
- **সমস্যা:** ভুল CSS selector দিলে `querySelector` crash করতে পারে
- **সমাধান:** try-catch wrapper বা selector validation helper যোগ করা
- **সময়:** 15 মিনিট

### Q2. Event Listener Cleanup
- **ফাইল:** `public/js/frontend.js` (লাইন 193-194)
- **সমস্যা:** WooCommerce AJAX re-render এ পুরানো listener গুলো থেকে যায়
- **সমাধান:** re-init এর সময় পুরানো dropdown element ও listener পরিষ্কার করা
- **সময়:** 1 ঘণ্টা

### Q3. fetchSuggestions Error UI
- **ফাইল:** `public/js/frontend.js` (লাইন 589-592)
- **সমস্যা:** API error হলে শুধু console.log হচ্ছে, ইউজার কিছু দেখে না
- **সমাধান:** "Unable to fetch suggestions. Try again." মেসেজ 3 সেকেন্ড দেখানো
- **সময়:** 20 মিনিট

---

## অগ্রাধিকার: মাঝারি

### Q4. Dead Code Cleanup
- **ফাইল:** একাধিক ফাইল
- **সমস্যা:** কিছু unused variable ও orphaned code আছে
- **সমাধান:** পুরো কোডবেস scan করে dead code সরানো
- **সময়:** 30 মিনিট

### Q5. Consistent Error Response Format
- **ফাইল:** AJAX handlers (একাধিক class)
- **সমস্যা:** কোথাও `wp_send_json_error('string')`, কোথাও `wp_send_json_error(array('message' => ...))`
- **সমাধান:** সব জায়গায় array format ব্যবহার করা
- **সময়:** 20 মিনিট

### Q6. Nonce Name Constants
- **ফাইল:** 8+ ফাইল জুড়ে
- **সমস্যা:** `'aga_admin_nonce'` string 8+ জায়গায় হার্ডকোড
- **সমাধান:** class constant হিসেবে define করা — typo থেকে বাঁচবে
- **সময়:** 15 মিনিট

---

## মোট আনুমানিক সময়: 2 ঘণ্টা 40 মিনিট
