<?php

namespace App\Filament\Pages\Education;

use App\Models\Branch;
use App\Models\Cabinet;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Staff;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CreateSchedulePage extends Page implements HasForms
{
    use InteractsWithForms;

    // --- Navigatsiya va Sahifa Sozlamalari ---
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Yangi Dars Jadvali Qo\'shish';
    protected static ?string $slug = 'education/schedule/create';
    protected static string $view = 'filament.pages.education.create-schedule-page';

    // --- Forma Ma'lumotlari ---
    public ?int $branch_id = null;
    public ?int $group_id = null;
    public ?string $selectedTeacherId = null;

    // --- Jadval Ma'lumotlari ---

    public array $scheduleData = [];

    // Kunlar ro'yxati (Blade'da ishlatish uchun)
    public array $daysOfWeek = [
        1 => 'Dushanba',
        2 => 'Seshanba',
        3 => 'Chorshanba',
        4 => 'Payshanba',
        5 => 'Juma',
        6 => 'Shanba',
        7 => 'Yakshanba',
    ];

    protected $queryString = [
        'branch_id' => ['except' => null],
        'group_id' => ['except' => null],
    ];

    public function mount(): void
    {
        // Agar URLda group_id bo'lsa, jadvalni yuklash
        if ($this->group_id) {
            $this->loadGroupSchedule();
        } else {
            $this->initializeScheduleData(); // Bo'sh jadvalni tayyorlash
        }
    }

    // --- Filtrlar uchun Options ---
    public function branchOptions(): Collection
    {
        return Branch::pluck('name', 'id');
    }

    public function groupOptions(): Collection
    {
        if (!$this->branch_id) {
            return collect(); // Filial tanlanmagan bo'lsa bo'sh
        }
        return Group::where('branch_id', $this->branch_id)->pluck('name', 'id');
    }

    public function cabinetOptions(): Collection
    {
        // Hamma kabinetlarni yoki filialga qarab olish mumkin
        return Cabinet::pluck('name', 'id');
    }

    public function teachersOptions(): Collection
    {
        return Staff::where('status', 'active')
            ->get()
            ->mapWithKeys(function ($teacher) {
                return [$teacher->id => $teacher->first_name . ' ' . $teacher->last_name];
            });
    }

    // --- Filtrlar o'zgarganda ---
    public function updatedBranchId(): void
    {
        $this->group_id = null; // Guruh tanlovini tozalash
        $this->scheduleData = []; // Jadvalni tozalash
        $this->initializeScheduleData(); // Bo'sh jadvalni tayyorlash
    }

    public function updatedGroupId($value): void
    {
        // Guruh tanlanganda jadvalni yuklash
        if ($value) {
            $this->loadGroupSchedule();
        } else {
            $this->scheduleData = []; // Jadvalni tozalash
            $this->initializeScheduleData(); // Bo'sh jadvalni tayyorlash
        }
    }

    // --- Jadvalni Yuklash ---
    protected function loadGroupSchedule(): void
    {
        $this->initializeScheduleData(); // Avval bo'shatib olamiz

        if (!$this->group_id) {
            return;
        }

        $schedules = Schedule::where('group_id', $this->group_id)->get();

        foreach ($schedules as $schedule) {
            if (isset($this->scheduleData[$schedule->day_of_week])) {
                $this->scheduleData[$schedule->day_of_week] = [
                    'id' => $schedule->id, // Mavjud yozuv IDsi
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'cabinet_id' => $schedule->cabinet_id,
                ];
            }
        }
    }

    // --- Bo'sh Jadvalni Tayyorlash ---
    protected function initializeScheduleData(): void
    {
        $this->scheduleData = [];
        foreach ($this->daysOfWeek as $dayNum => $dayName) {
            $this->scheduleData[$dayNum] = [
                'id' => null, // Yangi yozuv uchun ID null
                'start_time' => null,
                'end_time' => null,
                'cabinet_id' => null,
            ];
        }
    }

    // --- Jadvalni Saqlash ---
    protected function validateTimes($dayNum, $startTime, $endTime): bool
    {
        try {
            // Agar vaqtlar bo'sh bo'lsa
            if (empty($startTime) || empty($endTime)) {
                return true;
            }

            // Vaqtlarni tozalash
            $startTime = trim($startTime);
            $endTime = trim($endTime);

            // Soat va daqiqalarni ajratib olish
            [$startHour, $startMinute] = explode(':', $startTime);
            [$endHour, $endMinute] = explode(':', $endTime);

            // Daqiqalarga o'tkazib solishtirish
            $startDaqiqa = ((int)$startHour * 60) + (int)$startMinute;
            $endDaqiqa = ((int)$endHour * 60) + (int)$endMinute;

            // Solishtirib, tugash vaqti kattaroq bo'lishi kerak
            return $endDaqiqa > $startDaqiqa;
            
        } catch (\Exception $e) {
            return false;
        }
    }

    public function saveSchedule()
{
    try {
        // Guruh tekshiruvi
        $group = Group::with('teacher')->find($this->group_id);
        
        if (!$group) {
            throw new \Exception("Guruh topilmadi.");
        }

        if (!$group->teacher_id) {
            throw new \Exception("Guruhga o'qituvchi biriktirilmagan.");
        }

        // Validatsiya qoidalari
        $validationRules = [];
        foreach ($this->daysOfWeek as $dayNum => $dayName) {
            $validationRules["scheduleData.{$dayNum}.start_time"] = [
                'nullable',
                'required_with:scheduleData.'.$dayNum.'.end_time,scheduleData.'.$dayNum.'.cabinet_id',
                'date_format:H:i',
            ];
            
            $validationRules["scheduleData.{$dayNum}.end_time"] = [
                'nullable',
                'required_with:scheduleData.'.$dayNum.'.start_time,scheduleData.'.$dayNum.'.cabinet_id',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($dayNum) {
                    if (empty($value) || empty($this->scheduleData[$dayNum]['start_time'])) {
                        return;
                    }

                    $startTime = $this->scheduleData[$dayNum]['start_time'];
                    
                    // Soat va daqiqalarni ajratib olish
                    [$startHour, $startMinute] = explode(':', $startTime);
                    [$endHour, $endMinute] = explode(':', $value);
                    
                    // Daqiqalarga o'tkazib solishtirish
                    $startTotalMinutes = ((int)$startHour * 60) + (int)$startMinute;
                    $endTotalMinutes = ((int)$endHour * 60) + (int)$endMinute;
                    
                    if ($startTotalMinutes >= $endTotalMinutes) {
                        $fail("Tugash vaqti ({$value}) boshlanish vaqtidan ({$startTime}) keyin bo'lishi kerak");
                    }
                }
            ];
            
            $validationRules["scheduleData.{$dayNum}.cabinet_id"] = [
                'nullable',
                'required_with:scheduleData.'.$dayNum.'.start_time,scheduleData.'.$dayNum.'.end_time',
                'exists:cabinets,id'
            ];
        }

        // Custom error messages
        $messages = [
            'scheduleData.*.start_time.regex' => 'Noto\'g\'ri vaqt formati. Masalan: 09:00',
            'scheduleData.*.end_time.regex' => 'Noto\'g\'ri vaqt formati. Masalan: 09:00',
            'scheduleData.*.cabinet_id.exists' => 'Tanlangan kabinet mavjud emas.',
            'scheduleData.*.start_time.required_with' => 'Boshlanish vaqtini kiriting.',
            'scheduleData.*.end_time.required_with' => 'Tugash vaqtini kiriting.',
            'scheduleData.*.cabinet_id.required_with' => 'Kabinetni tanlang.',
        ];

        // Ma'lumotlarni validatsiya qilish
        $validatedData = $this->validate($validationRules, $messages)['scheduleData'];

        foreach ($validatedData as $dayNum => $data) {
            if (!empty($data['start_time']) && !empty($data['end_time']) && !empty($data['cabinet_id'])) {
                // Vaqtlarni H:i formatiga keltiramiz
                $startTime = \Carbon\Carbon::createFromFormat('H:i', $data['start_time'])->format('H:i');
                $endTime = \Carbon\Carbon::createFromFormat('H:i', $data['end_time'])->format('H:i');

                Schedule::updateOrCreate(
                    [
                        'group_id' => $this->group_id,
                        'day_of_week' => $dayNum,
                    ],
                    [
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'cabinet_id' => $data['cabinet_id'],
                        'teacher_id' => $group->teacher_id,
                        'branch_id' => $this->branch_id,
                    ]
                );
            } else {
                if (isset($this->scheduleData[$dayNum]['id'])) {
                    Schedule::find($this->scheduleData[$dayNum]['id'])?->delete();
                }
            }
        }

        Notification::make()
            ->title('Jadval muvaffaqiyatli saqlandi')
            ->success()
            ->send();

        $this->loadGroupSchedule();
        return redirect()->to('/admin/education/schedule');

    } catch (ValidationException $e) {
        Notification::make()
            ->title('Validatsiya xatosi')
            ->body('Iltimos, ma\'lumotlarni to\'g\'ri kiriting. Tugash vaqti boshlanish vaqtidan keyin bo\'lishi kerak.')
            ->danger()
            ->send();
    } catch (\Exception $e) {
        Notification::make()
            ->title('Xatolik yuz berdi')
            ->body('Jadvalni saqlashda xatolik: ' . $e->getMessage())
            ->danger()
            ->send();
    }
}

    public function assignTeacher(): void
    {
        $this->validate([
            'selectedTeacherId' => ['required', 'exists:staff,id'],
        ]);

        try {
            $group = Group::findOrFail($this->group_id);
            
            // To'g'ridan-to'g'ri yangilash o'rniga munosabat orqali yangilash
            $group->teacher()->associate($this->selectedTeacherId);
            $group->save();
            
            $this->selectedTeacherId = null;
            
            Notification::make()
                ->title('O\'qituvchi muvaffaqiyatli biriktirildi')
                ->success()
                ->send();
                
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik yuz berdi')
                ->body('O\'qituvchini biriktrishda xatolik: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
