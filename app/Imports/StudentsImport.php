<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Parents;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // Xatoliklarni log qilish uchun

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    private int $branchId;
    private int $rowCount = 0;
    private int $importedCount = 0; // Muvaffaqiyatli import qilinganlar soni

    public function __construct(int $branchId)
    {
        $this->branchId = $branchId;
    }

    /**
    * @param array $row Excel faylidagi bir qator ma'lumotlari
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row): ?Student
    {
        $this->rowCount++; // Umumiy ko'rilgan qatorlar soni

        // Tug'ilgan kunini formatlash
        $birthDate = null;
        if (!empty($row['tugilgan_kuni'])) {
            try {
                // Har xil formatlarni qabul qilishga harakat qilamiz
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $row['tugilgan_kuni'])) { // YYYY-MM-DD
                    $birthDate = Carbon::parse($row['tugilgan_kuni'])->format('Y-m-d');
                } elseif (preg_match('/^\d{2}-\d{2}-\d{4}$/', $row['tugilgan_kuni'])) { // DD-MM-YYYY
                    $birthDate = Carbon::createFromFormat('d-m-Y', $row['tugilgan_kuni'])->format('Y-m-d');
                } elseif (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $row['tugilgan_kuni'])) { // DD.MM.YYYY
                    $birthDate = Carbon::createFromFormat('d.m.Y', $row['tugilgan_kuni'])->format('Y-m-d');
                } else {
                    // Agar format mos kelmasa, log yozamiz
                    Log::warning("Noto'g'ri sana formati 'tugilgan_kuni' uchun qator {$this->rowCount}: " . $row['tugilgan_kuni']);
                }
            } catch (\Exception $e) {
                Log::error("Sana formatlashda xatolik 'tugilgan_kuni' uchun qator {$this->rowCount}: " . $row['tugilgan_kuni'] . " - " . $e->getMessage());
            }
        }

        // Jinsni formatlash
        $gender = null;
        if (!empty($row['jinsi'])) {
            $genderValue = strtolower(trim($row['jinsi']));
            if (in_array($genderValue, ['male', 'erkak', 'м', 'мужской'])) {
                $gender = 'male';
            } elseif (in_array($genderValue, ['female', 'ayol', 'ж', 'женский'])) {
                $gender = 'female';
            } else {
                Log::warning("Noto'g'ri jins qiymati 'jinsi' uchun qator {$this->rowCount}: " . $row['jinsi']);
            }
        }

        // Telefon raqamini tozalash (faqat raqamlarni qoldirish)
        $studentPhone = !empty($row['mobil_telefon_raqami']) ? preg_replace('/[^0-9]/', '', $row['mobil_telefon_raqami']) : null;
        $parentPhone = !empty($row['ota_ona_telefon_raqami']) ? preg_replace('/[^0-9]/', '', $row['ota_ona_telefon_raqami']) : null;


        // Student yaratish
        // Agar student telefon raqami bo'yicha mavjud bo'lsa, yangisini yaratmaymiz (ixtiyoriy)
        // $existingStudent = Student::where('phone', $studentPhone)->first();
        // if ($existingStudent) {
        //     Log::info("Student allaqachon mavjud (telefon: {$studentPhone}), qator {$this->rowCount} o'tkazib yuborildi.");
        //     return null; // Yoki mavjud studentni yangilashingiz mumkin
        // }

        $student = Student::create([
            'first_name' => $row['ismi'],
            'last_name' => $row['familiyasi'],
            'phone' => $studentPhone,
            'birth_date' => $birthDate,
            'gender' => $gender,
            'branch_id' => $this->branchId,
            'status_id' => 1, // Masalan, "Yangi" yoki "Aktiv" statusi ID si. StudentStatus modelingizga qarang.
            // Qolgan kerakli maydonlar...
            // 'email' => strtolower(Str::slug($row['ismi'] . $row['familiyasi'], '')) . '_' . Str::random(3) . '@example.com', // Misol uchun unikal email generatsiyasi
        ]);

        // Ota-ona ma'lumotlarini qayta ishlash
        if (!empty($row['ota_ona_ismi']) && $student) { // Student muvaffaqiyatli yaratilgan bo'lsa
            // Ota-onani topish yoki yaratish
            // Agar ota-ona telefon raqami bo'sh bo'lsa, ism bo'yicha qidirishga harakat qilish mumkin, lekin bu xatoliklarga olib kelishi mumkin.
            // Eng yaxshisi, ota-ona uchun ham unikal identifikator (masalan, telefon) bo'lishi.
            $parent = null;
            if ($parentPhone) {
                 $parent = Parents::firstOrCreate(
                    ['phone' => $parentPhone], // Telefon raqami bo'yicha qidiramiz
                    ['full_name' => $row['ota_ona_ismi']]
                );
            } elseif (!empty($row['ota_ona_ismi'])) { // Agar telefon yo'q, lekin ism bor bo'lsa (kamdan-kam holat)
                $parent = Parents::firstOrCreate(
                    ['full_name' => $row['ota_ona_ismi']],
                    ['phone' => null] // Telefonni null qilib saqlash
                );
            }


            // Studentni ota-onaga bog'lash
            if ($parent) {
                try {
                    // Student va Parents modellari o'rtasida many-to-many aloqa (parents() metodi Student modelida)
                    $student->parents()->syncWithoutDetaching([$parent->id]);
                } catch (\Exception $e) {
                    Log::error("Studentni ota-onaga bog'lashda xatolik, student ID: {$student->id}, parent ID: {$parent->id} - " . $e->getMessage());
                }
            }
        }

        if ($student) {
            $this->importedCount++; // Muvaffaqiyatli import qilinganlar sonini oshirish
        }

        return $student;
    }

    public function rules(): array
    {
        // Excel faylidagi ustun nomlariga mos kelishi kerak
        return [
            'ismi' => 'required|string|max:255',
            'familiyasi' => 'required|string|max:255',
            'ota_ona_ismi' => 'nullable|string|max:255', // Agar bo'sh bo'lishi mumkin bo'lsa nullable
            'mobil_telefon_raqami' => 'nullable|string|max:20', // Yoki 'required'
            'tugilgan_kuni' => 'nullable|string', // Formatni model() metodida tekshiramiz
            'jinsi' => 'nullable|string', // Qiymatlarni model() metodida tekshiramiz
            'ota_ona_telefon_raqami' => 'nullable|string|max:20',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'ismi.required' => 'Excel faylda "Ismi" ustuni to\'ldirilishi shart.',
            'familiyasi.required' => 'Excel faylda "Familiyasi" ustuni to\'ldirilishi shart.',
            // Boshqa xabarlar...
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }
}
