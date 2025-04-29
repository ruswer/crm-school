Asosiy Funksiyalar:

To'lovlarni Qayd Etish: O'quvchilardan kelib tushgan to'lovlarni (naqd, karta, o'tkazma, onlayn) tizimga kiritish.
Qarzdorlikni Kuzatish: Har bir o'quvchi yoki guruh bo'yicha qarzdorlikni avtomatik hisoblash va ko'rsatish.
Chegirmalarni Hisobga Olish: Berilgan chegirmalarni qayd etish va to'lov summasiga ta'sirini ko'rsatish.
To'lov Tarixi: Barcha amalga oshirilgan to'lovlar ro'yxatini ko'rish va filtrlash.
Hisobotlar: Turli davrlar, filiallar, guruhlar yoki to'lov turlari bo'yicha moliyaviy hisobotlarni yaratish.
Xabarnomalar (Ixtiyoriy): To'lov muddati yaqinlashganda yoki o'tib ketganda avtomatik yoki qo'lda eslatmalar yuborish.
Onlayn To'lov Integratsiyasi (Ixtiyoriy): Payme, Click kabi to'lov tizimlari orqali to'lovlarni qabul qilish.
Kerakli Sahifalar va Bo'limlar:

Markaziy To'lovlar Sahifasi (Payment History / Transactions):

Maqsad: Barcha amalga oshirilgan to'lovlar ro'yxatini ko'rsatish.
Funksiyalar:
Jadval: To'lov sanasi, O'quvchi, Guruh (agar bo'lsa), Summa, To'lov turi, Izoh, Kim tomonidan qabul qilingan (admin/kassir).
Filtrlar: Sana oralig'i, Filial, Guruh, O'quvchi, To'lov turi.
Qidiruv: O'quvchi ismi, telefon raqami, to'lov ID si bo'yicha.
Eksport qilish (Excel/CSV).
Har bir to'lov uchun batafsil ma'lumotni ko'rish yoki chekni chop etish imkoniyati.
Filament Komponenti: PaymentResource (CRUD uchun) yoki maxsus PaymentHistoryPage.
Yangi To'lovni Qayd Etish Sahifasi/Modal:

Maqsad: Yangi to'lovni tizimga kiritish.
Funksiyalar:
Forma: O'quvchini tanlash (qidiruv bilan), Guruhni tanlash (ixtiyoriy), To'lov summasi, To'lov sanasi (standart - bugun), To'lov turi (naqd, karta, o'tkazma va hokazo), Izoh/Referens raqami.
Avtomatik qarzdorlikni yangilash (to'lov kiritilganda o'quvchining yoki guruhdagi qarzi kamayishi).
Filament Komponenti: PaymentResource ning CreatePayment sahifasi yoki StudentProfilePage yoki Markaziy To'lovlar Sahifasidagi Modal Action.
Qarzdorlar Sahifasi (Debtors):

Maqsad: Qarzi bor o'quvchilar ro'yxatini ko'rsatish.
Funksiyalar:
Jadval: O'quvchi, Guruh(lar), Qarz miqdori, Oxirgi to'lov sanasi, To'lov muddati (agar belgilangan bo'lsa).
Filtrlar: Filial, Guruh, Qarz miqdori bo'yicha (masalan, 100,000 so'mdan ko'p).
Eslatma yuborish tugmasi (har bir qator uchun).
Filament Komponenti: Maxsus DebtorsPage.
Moliyaviy Hisobotlar Sahifasi (Reports):

Maqsad: Turli moliyaviy ko'rsatkichlarni tahlil qilish.
Funksiyalar:
Tanlangan davr uchun umumiy tushum.
Tushumlar filiallar kesimida.
Tushumlar guruhlar/kurslar kesimida.
Tushumlar to'lov turlari kesimida.
Umumiy qarzdorlik hisoboti.
Grafiklar va diagrammalar.
Filament Komponenti: Maxsus FinancialReportsPage (bir nechta widget yoki chartlar bilan).

To'lov Sozlamalari Sahifasi (Payment Settings) (Ixtiyoriy):

Maqsad: To'lov tizimini sozlash.
Funksiyalar:
To'lov turlarini boshqarish (qo'shish/o'chirish).
Onlayn to'lov tizimlari integratsiyasini sozlash (API kalitlari va hokazo).
Avtomatik eslatmalar sozlamalari.
Filament Komponenti: Maxsus PaymentSettingsPage.
