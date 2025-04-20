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
use Illuminate\Support\Facades\DB;
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
        'additional_details' => '',
        
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
        'courses' => [],
        'teachers' => [],
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

        $this->form = [
            'group_ids' => [], // Guruhlar uchun bo'sh array
        ];
    }

    public function save($createAnother = false)
    {
        // Validatsiya
        $this->validate([
            'form.passport_number' => 'required|unique:students,passport_number',
            'form.branch_id' => 'required|exists:branches,id',
            'form.group_ids' => 'required|array|min:1',
            'form.group_ids.*' => 'exists:groups,id',
            'form.first_name' => 'required|string|max:255',
            'form.last_name' => 'required|string|max:255',
            'form.gender' => 'required|in:male,female',
            'form.birth_date' => 'required|date',
            'form.phone' => 'required|string|max:255',
            'form.email' => 'nullable|email|max:255',
            'form.status_id' => 'required|exists:student_statuses,id',
            'study.language' => 'required|array',
            'study.course_id' => 'nullable|exists:courses,id',
            'study.knowledge_level_id' => 'required|exists:knowledge_levels,id',
            'study.days' => 'required|array',
            'form.marketing_source_id' => 'required|exists:marketing_sources,id',
            'form.additional_details' => 'nullable|string',
            'trial.teacher_id' => 'required|exists:staff,id',
            'trial.called_at' => 'required|date',
            'trial.attended_at' => 'required|date',
            'parent.parentsName' => 'required|string|max:255',
            'parent.parentsPhone' => 'required|string|max:255',
            'parent.parentsEmail' => 'nullable|email|max:255',
        ]);

        try {
            DB::beginTransaction();
    
            // O'quvchi ma'lumotlarini saqlash (study_language va study_days maydonlarisiz)
            $student = Student::create([
                'passport_number' => $this->form['passport_number'],
                'branch_id' => $this->form['branch_id'],
                'first_name' => $this->form['first_name'],
                'last_name' => $this->form['last_name'],
                'gender' => $this->form['gender'],
                'birth_date' => $this->form['birth_date'],
                'phone' => $this->form['phone'],
                'email' => $this->form['email'],
                'status_id' => $this->form['status_id'],
                'knowledge_level_id' => $this->study['knowledge_level_id'],
                'notes' => $this->form['notes'],
                'trial_teacher_id' => $this->trial['teacher_id'],
                'trial_called_at' => $this->trial['called_at'],
                'trial_attended_at' => $this->trial['attended_at'],
                'marketing_source_id' => $this->form['marketing_source_id'],
            ]);

             // Guruhlarni saqlash
            if (!empty($this->form['group_ids'])) {
                $student->groups()->attach($this->form['group_ids']);
            }

            // O'qish tillarini saqlash
            if (!empty($this->study['language'])) {
                foreach ($this->study['language'] as $language) {
                    $student->studyLanguages()->create([
                        'language' => $language
                    ]);
                }
            }
    
            // O'qish kunlarini saqlash
            if (!empty($this->study['days'])) {
                foreach ($this->study['days'] as $day) {
                    $student->studyDays()->create([
                        'day' => $day
                    ]);
                }
            }
    
            // Kursga yozish
            if ($this->study['course_id']) {
                $student->courses()->attach($this->study['course_id']);
            }
    
            // Ota-ona ma'lumotlarini saqlash
            Parents::create([
                'student_id' => $student->id,
                'full_name' => $this->parent['parentsName'],
                'phone' => $this->parent['parentsPhone'],
                'email' => $this->parent['parentsEmail'],
            ]);
    
            DB::commit();
    
            Notification::make()
                ->title('O\'quvchi muvaffaqiyatli qo\'shildi')
                ->success()
                ->send();
    
            if ($createAnother) {
                $this->resetForm();
            } else {
                return redirect()->to('/admin/students');
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

    // Formani tozalash
    private function resetForm()
    {
        // Barcha formalarni boshlang'ich holatga qaytarish
        $this->form = array_fill_keys(array_keys($this->form), null);
        $this->form['group_ids'] = []; // group_ids ni bo'sh array sifatida qayta o'rnatish
        $this->study = [
            'language' => [],
            'course_id' => null,
            'knowledge_level_id' => null,
            'days' => [],
        ];
        $this->trial = array_fill_keys(array_keys($this->trial), null);
        $this->parent = array_fill_keys(array_keys($this->parent), null);
    }

    public function create()
    {
        $this->save(false);
    }

    public function createAnother()
    {
        $this->save(true);
    }
}
