# সেশন গাইড — প্রতিটা সেশনে এই ফাইল দাও

> নতুন সেশন শুরু করার সময় Claude কে এই docs ফোল্ডার পড়তে বলো।
> বলো: "docs ফোল্ডার পড়ো এবং যেই সেশনের কাজ বাকি আছে সেটা শুরু করো"

---

## ফাইল তালিকা

| ফাইল | কি আছে |
|------|--------|
| `01-PERFORMANCE-CHECKLIST.md` | পারফরম্যান্স উন্নতির 6টা কাজ |
| `02-CODE-QUALITY-CHECKLIST.md` | কোড কোয়ালিটি 6টা কাজ |
| `03-FEATURE-ROADMAP.md` | ভবিষ্যত ফিচার 13টা (3 সেশনে ভাগ করা) |
| `04-SALES-MARKETING-PLAN.md` | বিক্রি বাড়ানোর পুরো প্ল্যান |
| `05-SESSION-GUIDE.md` | এই ফাইল — সেশন পরিচালনার গাইড |
| `06-COMPLETED-WORK.md` | যা যা করা হয়েছে তার তালিকা |

---

## সেশন পরিকল্পনা

### সেশন A — পারফরম্যান্স (আনুমানিক 3 ঘণ্টা)
- [ ] P1: API polling optimize
- [ ] P2: DocumentFragment render
- [ ] P3: IP geolocation cache
- [ ] P4: Country centers move
- [ ] P5: CSS animation pause
- [ ] P6: Inline style cleanup

### সেশন B — কোড কোয়ালিটি (আনুমানিক 3 ঘণ্টা)
- [ ] Q1: querySelector error handling
- [ ] Q2: Event listener cleanup
- [ ] Q3: Error UI for users
- [ ] Q4: Dead code cleanup
- [ ] Q5: Error response format
- [ ] Q6: Nonce constants

### সেশন C — সহজ ফিচার (আনুমানিক 1 দিন)
- [ ] F1: API Key Validation in Wizard
- [ ] F2: Autocomplete Result Cache
- [ ] F3: Address Book 50+
- [ ] F4: Duplicate Address Warning

### সেশন D — মাঝারি ফিচার (আনুমানিক 3 দিন)
- [ ] F5: CSV Batch Validation
- [ ] F6: Auto Language Detection
- [ ] F7: Confidence Score
- [ ] F8: Shipping Cost on Map
- [ ] F9: Phone Auto-Fill

### সেশন E — বড় ফিচার (আনুমানিক 7+ দিন)
- [x] F10: Visual CSS Selector Tool
- [ ] F11: Formidable Forms Integration
- [ ] F12: Analytics Export
- [ ] F13: Multi-Store Sync

---

## নতুন সেশন শুরু করতে যা বলবে

```
docs ফোল্ডারের ফাইলগুলো পড়ো:
C:\Users\nisha\Local Sites\autocomplete\app\public\wp-content\plugins\google-autocomplete-premium\docs\

আজকে সেশন [A/B/C/D/E] এর কাজ করবো।
চেকলিস্ট দেখে যেটা বাকি আছে সেটা শুরু করো।
কাজ শেষে চেকলিস্ট আপডেট করো।
```

---

## প্রতি সেশনের শেষে

1. Claude চেকলিস্ট ফাইল আপডেট করবে ([ ] → [x])
2. `06-COMPLETED-WORK.md` তে নতুন entry যোগ হবে
3. Version bump + changelog + git push হবে
4. SVN commit message দেওয়া হবে
