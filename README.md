# 🎓 CRM-School – O‘quv markazlari uchun CRM tizimi

CRM-School bu Laravel asosida ishlab chiqilgan veb-ilova bo‘lib, o‘quv markazlarining kundalik jarayonlarini boshqarish, optimallashtirish va avtomatlashtirishga mo‘ljallangan. Tizim orqali o‘quvchilar, o‘qituvchilar, guruhlar, kurslar va to‘lovlar boshqariladi.

---

## 🚀 Asosiy imkoniyatlar

- 🔐 Avtorizatsiya va autentifikatsiya (Admin/Hodim)
- 👨‍🎓 O‘quvchilarni qo‘shish, tahrirlash, ro‘yxatini ko‘rish
- 👩‍🏫 O‘qituvchilarni boshqarish
- 🧑‍🏫 Kurslar va guruhlarni boshqarish
- 💰 To‘lovlar ro‘yxati, tahriri va holatini nazorat qilish
- 📅 Dars jadvali yoki guruh tuzilmasi
- 📊 Dashboard orqali umumiy statistikani ko‘rish
- 🖥️ Laravel + Livewire + Tailwind asosidagi zamonaviy UI

---

## 🛠 Texnologiyalar

- **Laravel 10+** – PHP backend framework
- **Livewire** – Dinamik, real vaqt rejimidagi komponentlar
- **Tailwind CSS** – Responsiv va minimal dizayn
- **Alpine.js** – Yengil JS interaktivlik
- **MySQL** – Ma’lumotlar bazasi
- **Vite** – Frontend build sistemi

---

## ⚙️ O‘rnatish bo‘yicha yo‘riqnoma

Quyidagi bosqichlarda loyihani o‘z kompyuteringizda ishga tushirishingiz mumkin:

### 1. Loyihani yuklab oling

```bash
git clone https://github.com/ruswer/crm-school.git
cd crm-school
```

### 2. Composer kutubxonalarini o‘rnating

```bash
composer install
```

### 3. .env faylini yarating va sozlang

```bash
cp .env.example .env
php artisan key:generate
```

`.env` faylida `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` kabi sozlamalarni to‘g‘rilang.

### 4. Migratsiya va Seeder'larni ishga tushiring

```bash
php artisan migrate --seed
```

### 5. Vite frontend build (agar kerak bo‘lsa)

```bash
npm install
npm run dev
```

### 6. Laravel development serverni ishga tushiring

```bash
php artisan serve
```

🔗 Ilovani brauzerda ochish:  
[http://localhost:8000](http://localhost:8000)

---

## 👤 Admin login (default)

```text
Email: admin@example.com
Password: password
```

(Seeder orqali avtomatik yaratilgan — kerak bo‘lsa `DatabaseSeeder.php` faylini ko‘rib sozlang)

---

## 📌 Eslatmalar

- Kod modulli va o‘qilishi oson tarzda tashkil etilgan.
- Livewire orqali real-time formalar, modal oynalar va validatsiya mavjud.
- Responsiv UI har xil ekranlar uchun moslashtirilgan.

---

## 📄 Litsenziya

Ushbu loyiha MIT litsenziyasi asosida tarqatiladi. Tafsilotlar uchun `LICENSE` faylini ko‘ring.

---

## 👨‍💻 Muallif

**Doniyor Rustamov**  
📧 doniyor.ruswer@gmail.com  
🔗 GitHub: [@ruswer](https://github.com/ruswer)

---

## 🙌 Qo‘shilish

Agar siz ushbu loyihani rivojlantirishda ishtirok etmoqchi bo‘lsangiz, pull request oching yoki issue yarating.
