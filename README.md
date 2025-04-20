crm Struktura

Bulimlar
    -uquvchilar
        (sahifalar)
        -o'quvchilar
            (funksionalliklar)
            -qidirish qismida excel fayldan import qilish,qidirish
            -pastki qismda uquvchilar ruyhatini jadval sifatida kursatish
        -o'quvchini qo'shish
            (funksionalliklar)
            -uquvchi malumotlarini yaratish yani create,Qo'shish & Yana Qo'shish
        -ota-ona
            (funksionalliklar)
            -qidirish
            -pastki qismda ota-ona ruyhatini jadval sifatida kursatish
        -avtorizatsiya malumotlari
            (funksionalliklar)
            -qidirish
            -pastki qismda uquvchi va ota-ona Saytga kirish ma'lumotlari kursatish
        -bitiruvchilar
            (funksionalliklar)
            -qidirish
            -pastki qismda Bitiruvchilar ruyhatini jadval sifatida kursatish
    -Tulovlar
        (sahifalar)
        -tulov
            (funksionalliklar)
            -qidirish,
            -pastki qismda tulov ruyhati natijasi jadval sifatida kursatish
        -qarzdorlar
            (funksionalliklar)
            -qidirish,
            -pastki qismda qarzdorlar ruyhatini jadval sifatida kursatish
    -chiqimilar
        (sahifalar)
        -chiqim qoshish
            (funksionalliklar)
            -chiqim qoshish
            -ong tomonda chiqimlar ruyhatini jadval sifatida kursatish
        -chiqimilarni izlash
            (funksionalliklar)
            -qidirish,
            -pastki qismda chiqimlar natijasini jadval sifatida kursatish
        -chiqim kategoriyalari
            (funksionalliklar)
            -chiqim kategoriya qoshish
            -ong tomonda kategoriyalar ruyhatini jadval sifatida kursatish
    -marketing
        (sahifalar)
        -marketing hisoboti
            (funksionalliklar)
            -qidirish,
            -pastki qismda reklama buyicha hisobot,Jins bo'yicha taqsimot natijasini alohida jadval sifatida kursatish
        -reklama turlari
            (funksionalliklar)
            -reklama turi qoshish
            -ong tomonda reklama turi ruyhatini jadval sifatida kursatish
    -imtihonlar
        (sahifalar)
        -imtihonlar royhati
            (funksionalliklar)
            -qidirish
            -pastki tomonda ong tomonda qoshish, imtihonlar ruyhatini jadval sifatida kursatish
    -talim
        (sahifalar)
        -guruhlar
            (funksionalliklar)
            -guruh qoshish
            -ong tomonda guruh ruyhatini jadval sifatida kursatish,tepa qismida faol,kutilmoqda,ochirilgan buyicha sortlash
        -filiallar
            (funksionalliklar)
            -filial qoshish
            -ong tomonda filiallar ruyhatini jadval sifatida kursatish
        -kurslar
            (funksionalliklar)
            -kurs qoshish
            -ong tomonda kurslar ruyhatini jadval sifatida kursatish
        -bilim darajasi
            (funksionalliklar)
            -bilim darajasi qoshish
            -ong tomonda bilim darajalari ruyhatini jadval sifatida kursatish
        -kabinetlar
            (funksionalliklar)
            -kabinet qoshish
            -ong tomonda kabunetlar ruyhatini jadval sifatida kursatish
        -darslar jadvali
            (funksionalliklar)
            -qidirish,qoshish tugmasi orqali create
            -pastki qismda darslar jadvalini kalendar ichida kursatish kerak
    -kadrlar bulimi
        (sahifalar)
        -xodimlar
            (funksionalliklar)
            -qidirish,qoshish tugmasi orqali create
            -pastki qismda xodimlarni ruyhatini grid va royhat sifatida kursatish
        -xodimlar davomati
            (funksionalliklar)
            -qidirish
            -pastki qismda saqlash tugmasi, xodimlar davomatini jadval ichida qiymatlarini kiritib saqlash tugmasi orqali DB ga saqlash kerak
        -xodimlar davomat hisoboti
            (funksionalliklar)
            -qidirish,
            -pastki qismda xodimlar davomati hisoboti jadval ichida kursatish kerak
        -ish xaqi
            (funksionalliklar)
            -qidirish
            -pastki qismda jadval ichida xodimlar ruyhati va qiymatlarini kiritib saqlash tugmasi orqali DB ga saqlash kerak
        -bolim
            (funksionalliklar)
            -bolim qoshish
            -ong tomonda bolimlar ruyhati jadval ichida kursatish kerak
        -lavozim
            (funksionalliklar)
            -lavozim qoshish
            -ong tomonda lavozimlar ruyhati jadval ichida kursatish kerak
    -tizimni sozlash
        (sahifalar)
        sozlamalar
            (funksionalliklar)
            -logo qoshish
            -ong tomonda bolimlar ruyhati jadval ichida kursatish kerak,tahrirlash orqali update



                            1-Database strukturasi:

1. Users (Foydalanuvchilar)
   - id, name, email, password, role, status

2. Students (O'quvchilar)
   - id, first_name, last_name, phone, address, gender, birth_date, status
   - photo, registration_date, marketing_source_id

3. Parents (Ota-onalar)
   - id, student_id, name, phone, additional_phone, relation_type

4. Groups (Guruhlar)
   - id, name, course_id, teacher_id, room_id, status
   - start_date, end_date, lesson_days, lesson_time

5. Courses (Kurslar)
   - id, name, description, price, duration
   - level_id, status

6. Payments (To'lovlar)
   - id, student_id, group_id, amount, payment_date
   - payment_type, status, description

7. Expenses (Chiqimlar)
   - id, category_id, amount, date, description
   - added_by, status

8. ExpenseCategories (Chiqim kategoriyalari)
   - id, name, description

9. Staff (Xodimlar)
   - id, first_name, last_name, position_id
   - department_id, salary, hire_date, status

10. Attendance (Davomat)
    - id, student_id/staff_id, date, status
    - type (student/staff), description

11. Branches (Filiallar)
    - id, name, address, phone, manager_id

12. Rooms (Xonalar)
    - id, branch_id, name, capacity, status

13. Exams (Imtihonlar)
    - id, name, course_id, date, description

14. ExamResults
    - id, exam_id, student_id, score, notes

15. MarketingSources (Marketing manbalari)
    - id, name, type, status

                            2-Asosiy modullar:

1. Authentication va Authorization
   - Login/Logout
   - Role-based access control
   - Permission management

2. O'quvchilar moduli
   - CRUD operatsiyalari
   - Excel import/export
   - Ota-ona ma'lumotlari
   - To'lov tarixi
   - Davomat

3. To'lovlar moduli
   - To'lov qabul qilish
   - Qarzdorlar ro'yxati
   - To'lov tarixi
   - Hisobotlar

4. Moliya moduli
   - Chiqimlarni boshqarish
   - Kategoriyalar
   - Moliyaviy hisobotlar

5. Ta'lim moduli
   - Guruhlarni boshqarish
   - Dars jadvali
   - Kurslar
   - Xonalar
   - Imtihonlar

6. Kadrlar moduli
   - Xodimlar
   - Davomat
   - Ish haqi
   - Bo'limlar

7. Marketing moduli
   - Marketing hisobotlari
   - Reklama turlari
   - Konversiya tahlili

8. Hisobotlar moduli
   - Moliyaviy hisobotlar
   - Davomat hisobotlari
   - Marketing hisobotlari

                        3-Interfeys strukturasi:

1. Dashboard
   - Asosiy statistika
   - Tezkor ma'lumotlar
   - Grafik va diagrammalar

2. Asosiy menyu
   - O'quvchilar
   - To'lovlar
   - Chiqimlar
   - Marketing
   - Ta'lim
   - Kadrlar
   - Sozlamalar

3. Qidiruv va filtrlash
   - Har bir bo'limda advanced qidiruv
   - Filtrlar
   - Export funksiyalari

                        4-Xavfsizlik:

1. Rollar:
   - Super Admin
   - Admin (Filial rahbari)
   - O'qituvchi
   - Hisobchi
   - Marketing manager
   - Operator

2. Ruxsatlar:
   - Create
   - Read
   - Update
   - Delete
   - Export
   - Import

                        5-Integratsiyalar:

1. SMS xizmati
2. Email xizmati
3. Excel import/export
4. Kalendar
5. Hisobot generatori

                        6-Qo'shimcha funksionallar:

1. Avtomatik eslatmalar
   - To'lov muddati
   - Dars jadvali
   - Tug'ilgan kun

2. Statistika va tahlil
   - O'quvchilar soni dinamikasi
   - Moliyaviy ko'rsatkichlar
   - Marketing samaradorligi

3. Monitoring
   - Davomat nazorati
   - To'lovlar nazorati
   - Xodimlar faoliyati