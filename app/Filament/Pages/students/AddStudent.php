<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\Course;
use App\Models\Group;
use App\Models\KnowledgeLevel;
use App\Models\MarketingSource;
use Filament\Pages\Page;
use App\Models\Student;
use App\Models\Parents;
use App\Models\Staff;
use App\Models\StudentStatus;
use App\Models\Authorization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class AddStudent extends Page
{
    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'O\'quvchini qo\'shish';
    protected static ?string $title = 'O\'quvchini qo\'shish';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.students.add-student';

    // Asosiy ma'lumotlar
    public array $form = [
        'passport_number' => '',
        'branch_id' => '',
        'group_ids' => [],
        'first_name' => '',
        'last_name' => '',
        'gender' => '',
        'birth_date' => '',
        'phone' => '',
        'email' => '',
        'status_id' => '',
        'notes' => '',
        'marketing_source_id' => '',
    ];

    // O'qish ma'lumotlari
    public array $study = [
        'language' => [],
        'course_id' => '',
        'knowledge_level_id' => '',
        'days' => [],
    ];

    // Sinov darsi ma'lumotlari
    public array $trial = [
        'teacher_id' => '',
        'called_at' => '',
        'attended_at' => '',
    ];

    // Ota-ona ma'lumotlari
    public array $parent = [
        'parentsName' => '',
        'parentsPhone' => '',
        'parentsEmail' => '',
    ];

    // Select optionlari uchun ma'lumotlar
    public array $options = [
        'branches' => [],
        'groups' => [],
        'studentStatuses' => [], 
        'courses' => [],
        'teachers' => [],
        'knowledgeLevels' => [],
        'marketingSources' => [],
    ];

    public function getSelectedGroupNamesProperty()
    {
        if (empty($this->form['group_ids'])) {
            return 'Guruhlarni tanlang';
        }

        $selectedGroups = Group::whereIn('id', $this->form['group_ids'])
            ->pluck('name')
            ->join(', ');

        return $selectedGroups ?: 'Guruhlarni tanlang';
    }

    public function mount()
    {
        // Kerakli ma'lumotlarni olish
        $this->options = [
            'branches' => Branch::where('status', 'active')->get(),
            'groups' => Group::where('status', 'active')->get(),
            'studentStatuses' => StudentStatus::where('is_active', 1)->get(),
            'courses' => Course::where('status', 'active')->get(),
            'teachers' => Staff::where('status', 'active')->get(),
            'knowledgeLevels' => KnowledgeLevel::where('is_active', 1)->get(),
            'marketingSources' => MarketingSource::where('status', 'active')->get(),
        ];

        // Formani boshlang'ich holatga keltirish (mount da)
        $this->resetForm();
    }

    public function save($createAnother = false)
    {
        // Yangilangan Validatsiya
        $this->validate([
            // --- Student Asosiy Ma'lumotlari ---
            'form.passport_number' => 'nullable|string|max:255|unique:students,passport_number',
            'form.branch_id' => 'required|exists:branches,id',
            'form.group_ids' => 'nullable|array',
            'form.group_ids.*' => 'exists:groups,id',
            'form.first_name' => 'required|string|max:255',
            'form.last_name' => 'required|string|max:255',
            'form.gender' => 'required|in:male,female',
            'form.birth_date' => 'required|date|before:today',
            'form.phone' => 'required|string|max:255|unique:students,phone', 
            'form.email' => 'nullable|email|max:255|unique:students,email',
            'form.status_id' => 'required|exists:student_statuses,id',

            // --- O'qish Ma'lumotlari ---
            'study.language' => 'nullable|array', 
            'study.course_id' => 'nullable|exists:courses,id',
            'study.knowledge_level_id' => 'nullable|exists:knowledge_levels,id', 
            'study.days' => 'nullable|array', 

            // --- Marketing va Izoh ---
            'form.marketing_source_id' => 'required|exists:marketing_sources,id',
            'form.notes' => 'nullable|string', // Nom to'g'ri

            // --- Sinov Darsi Ma'lumotlari ---
            'trial.teacher_id' => 'nullable|exists:staff,id', 
            'trial.called_at' => 'nullable|date', 
            'trial.attended_at' => 'nullable|date|after_or_equal:trial.called_at', 

            // --- Ota-ona Ma'lumotlari ---
            'parent.parentsName' => 'nullable|string|max:255', 
            'parent.parentsPhone' => 'nullable|string|max:255|unique:parents,phone', 
            'parent.parentsEmail' => 'nullable|email|max:255|unique:parents,email',
        ]);

        try {
            DB::beginTransaction();

            // O'quvchi ma'lumotlarini saqlash
            $student = Student::create([
                'passport_number' => $this->form['passport_number'] ?: null,
                'branch_id' => $this->form['branch_id'],
                'first_name' => $this->form['first_name'],
                'last_name' => $this->form['last_name'],
                'gender' => $this->form['gender'],
                'birth_date' => $this->form['birth_date'],
                'phone' => $this->form['phone'],
                'email' => $this->form['email'] ?: null,
                'status_id' => $this->form['status_id'],
                'knowledge_level_id' => $this->study['knowledge_level_id'] ?: null,
                'notes' => $this->form['notes'] ?: null,
                'trial_teacher_id' => $this->trial['teacher_id'] ?: null,
                'trial_called_at' => $this->trial['called_at'] ?: null,
                'trial_attended_at' => $this->trial['attended_at'] ?: null,
                'marketing_source_id' => $this->form['marketing_source_id'],
            ]);

            // Guruhlarni saqlash
            if (!empty($this->form['group_ids'])) {
                $student->groups()->attach($this->form['group_ids']);
            }

            // O'qish tillarini saqlash
            if (!empty($this->study['language'])) {
                foreach ($this->study['language'] as $languageValue) {
                    $student->studyLanguagesStudents()->create([
                        'language' => $languageValue
                    ]);
                }
            }

            // O'qish kunlarini saqlash
            if (!empty($this->study['days'])) {
                foreach ($this->study['days'] as $dayValue) {
                   $student->studyDayStudents()->create([
                       'day' => $dayValue
                   ]);
               }
           }

            // Kursga yozish
            if ($this->study['course_id']) {
                $student->courses()->attach($this->study['course_id']);
            }

            // Ota-ona ma'lumotlarini saqlash (agar kiritilgan bo'lsa)
            $parent = null;
            if (!empty($this->parent['parentsName']) && !empty($this->parent['parentsPhone'])) {
                $parent = Parents::create([
                    'student_id' => $student->id,
                    'full_name' => $this->parent['parentsName'],
                    'phone' => $this->parent['parentsPhone'],
                    'email' => $this->parent['parentsEmail'],
                ]);
            }

            // --- Avtorizatsiya ma'lumotlarini generatsiya qilish ---

            // Student uchun Login/Parol Generatsiyasi (har doim)
            $studentLoginBase = strtolower(substr($student->first_name, 0, 1) . $student->last_name);
            $studentLogin = $this->generateUniqueLogin($studentLoginBase);
            $studentPlainPassword = Str::random(8);

            $student->authorization()->create([
                'login' => $studentLogin,
                'password' => Hash::make($studentPlainPassword),
            ]);

            // Ota-ona uchun Login/Parol Generatsiyasi (agar ota-ona yaratilgan bo'lsa)
            $parentLogin = null;
            $parentPlainPassword = null;
            if ($parent) {
                $parentLoginBase = strtolower(str_replace(' ', '', $parent->full_name));
                $parentLogin = $this->generateUniqueLogin($parentLoginBase . '_p');
                $parentPlainPassword = Str::random(8);

                $parent->authorization()->create([
                    'login' => $parentLogin,
                    'password' => Hash::make($parentPlainPassword),
                ]);
            }

            DB::commit();

            // Muvaffaqiyatli xabar
            $notificationBody = "O'quvchi: Login: {$studentLogin}, Parol: {$studentPlainPassword}\n";
            if ($parentLogin && $parentPlainPassword) {
                $notificationBody .= "Ota-ona: Login: {$parentLogin}, Parol: {$parentPlainPassword}";
            }

            Notification::make()
                ->title('O\'quvchi muvaffaqiyatli qo\'shildi')
                ->body($notificationBody)
                ->success()
                ->persistent()
                ->send();


            if ($createAnother) {
                $this->resetForm();
            } else {
                // StudentsPage ga yo'naltirish (Filament route nomidan foydalanish)
                return redirect()->route('filament.admin.pages.students');
            }

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }


    /**
     * Noyob login generatsiya qilish uchun yordamchi funksiya.
     */
    private function generateUniqueLogin(string $base): string
    {
        $login = $base;
        $counter = 1;
        // Bunday login authorizations jadvalida mavjudligini tekshirish
        while (Authorization::where('login', $login)->exists()) {
            $login = $base . $counter;
            $counter++;
        }
        return $login;
    }

    /**
     * Formani tozalash
     */
    private function resetForm()
    {
        // Barcha formalarni boshlang'ich holatga qaytarish
        $this->reset(
            'form',
            'study',
            'trial',
            'parent'
        );
        // group_ids ni alohida tozalash kerak, chunki u $form ichida
        $this->form['group_ids'] = [];
    }

    /**
     * "Qo'shish" tugmasi uchun metod
     */
    public function create()
    {
        $this->save(false);
    }

    /**
     * "Qo'shish & Yana Qo'shish" tugmasi uchun metod
     */
    public function createAnother()
    {
        $this->save(true);
    }
}
