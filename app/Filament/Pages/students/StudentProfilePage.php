<?php

namespace App\Filament\Pages\Students;

use App\Models\Student;
use App\Models\Group; // <-- Import Group model
use App\Models\BillingSetting; // <-- Import BillingSetting model (or Setting if you use that)
use App\Models\RemovalReason;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\DB; // <-- Import DB facade for transaction

class StudentProfilePage extends Page implements HasForms
{
    use InteractsWithForms;

    public Student $record;

    public string $activeTab = 'profile';

    // Removal properties
    public bool $showRemoveStudentModal = false;
    public ?int $removalReasonId = null;
    public array $removalReasonsOptions = [];

    // --- Imtiyoz Qo'shish Modal Xususiyatlari ---
    public bool $showAddDiscountModal = false;
    public ?Group $discountGroup = null; // Qaysi guruhga imtiyoz qo'shilayotgani
    public $discountPoints = null; // Kiritilgan ballar (null bo'lishi mumkin)
    public string $discountDescription = ''; // Tavsif
    public float $calculatedDiscountAmount = 0.00; // Hisoblangan summa
    public float $pointsDiscountRate = 1000.00; // Ball stavkasi (standart)
    // --- End Imtiyoz Qo'shish Modal Xususiyatlari ---

    protected static ?string $slug = 'students/{record}/profile';
    protected static string $view = 'filament.pages.students.student-profile';
    protected static bool $shouldRegisterNavigation = false;

    public function mount(Student $record): void
    {
        $this->record = $record->loadMissing([
            'branch',
            // Load pivot data for groups - Make sure 'discount' and 'debt' are loaded if needed directly
            // If you access them via $group->pivot->discount, this should be enough
            'groups',
            'status',
            'courses',
            'studyLanguagesStudents',
            'knowledgeLevel',
            'studyDayStudents',
            'parents.authorization',
            'marketingSource',
            'trialTeacher',
            'removalReason',
            'authorization',
            // Load points if not already loaded and needed for discount logic
            // 'points' // Uncomment if 'points' is a direct attribute and needed
        ]);

        $this->loadRemovalReasonsOptions();
        $this->loadPointsDiscountRate(); // <-- Ball stavkasini yuklash
    }

    // Ball stavkasini sozlamalardan yuklash
    protected function loadPointsDiscountRate(): void
    {
        // BillingSetting modelidan foydalanish
        $billingSetting = BillingSetting::first(); // Yagona sozlamani olish
        if ($billingSetting && isset($billingSetting->points_discount_rate)) {
            $this->pointsDiscountRate = (float)$billingSetting->points_discount_rate;
        }
        // Agar Setting modeli ishlatilsa:
        // $rateSetting = \App\Models\Setting::where('key', 'points_discount_rate')->value('value');
        // $this->pointsDiscountRate = $rateSetting ? (float)$rateSetting : 1000.00;
    }


    protected function loadRemovalReasonsOptions(): void
    {
        $this->removalReasonsOptions = RemovalReason::where('is_active', true)
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->all();
    }

    public function getTitle(): string
    {
        return $this->record->first_name . ' ' . $this->record->last_name . ' profili';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    // --- Imtiyoz Qo'shish Logikasi ---

    // Modalni ochish
    public function openAddDiscountModal(int $groupId): void
    {
        // Find the group within the loaded relationship
        $this->discountGroup = $this->record->groups->find($groupId);

        if ($this->discountGroup) {
            $this->reset('discountPoints', 'discountDescription', 'calculatedDiscountAmount'); // Formani tozalash
            $this->resetErrorBag(); // Xatoliklarni tozalash
            $this->showAddDiscountModal = true;
        } else {
            Notification::make()->title('Xatolik')->body('Guruh topilmadi.')->danger()->send();
        }
    }

    // Ballar kiritilganda summani dinamik hisoblash
    // Use Livewire's magic updated hook
    public function updatedDiscountPoints($value): void
    {
        // Ensure the value is treated as a float, handle potential null/empty string
        $points = is_numeric($value) ? (float)$value : 0;

        if ($points >= 0) {
            $this->calculatedDiscountAmount = $points * $this->pointsDiscountRate;
        } else {
            $this->calculatedDiscountAmount = 0;
            // Optionally add validation error feedback immediately
            $this->addError('discountPoints', 'Ball manfiy bo\'lishi mumkin emas.');
        }
    }

    // Imtiyozni saqlash
    public function saveDiscount(): void
    {
        if (!$this->discountGroup) {
            Notification::make()->title('Xatolik')->body('Guruh aniqlanmadi.')->danger()->send();
            return;
        }

        // Validatsiya
        $validatedData = $this->validate([
            // Use 'discountPoints' which is the property name
            'discountPoints' => ['required', 'numeric', 'min:0', 'max:6'],
            'discountDescription' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $points = (float)$validatedData['discountPoints'];
            // Recalculate here to be absolutely sure, using the validated value
            $discountAmountToAdd = $points * $this->pointsDiscountRate;
            $description = $validatedData['discountDescription'];

            // Transaksiya ichida bajarish
            DB::transaction(function () use ($points, $discountAmountToAdd, $description) {
                // 1. Pivot jadvaldagi joriy imtiyozni yangilash
                // Ensure pivot data is loaded or reload if necessary
                $pivotData = $this->record->groups()->find($this->discountGroup->id)?->pivot;

                if (!$pivotData) {
                     throw new \Exception("Guruh bog'lanishi topilmadi.");
                }

                $newDiscount = $discountAmountToAdd; 

                $this->record->groups()->updateExistingPivot($this->discountGroup->id, [
                    'discount' => $newDiscount,
                    // Add description to pivot if you have a column for it
                    // 'discount_description' => $description,
                ]);

                // 2. (MUHIM!) O'quvchining umumiy ballarini kamaytirish
                //    Check if the 'points' attribute exists on the student model
                if (property_exists($this->record, 'points')) {
                    $this->record->points = max(0, (float)$this->record->points - $points); // Ensure non-negative
                    $this->record->save(); // Save the student record with updated points
                } else {
                    // Log a warning if points attribute doesn't exist but logic expects it
                    \Illuminate\Support\Facades\Log::warning("Student modelida 'points' atributi topilmadi (ID: {$this->record->id}). Ballar kamaytirilmaydi.");
                }
            });

            $this->showAddDiscountModal = false; // Modalni yopish
            // Reload the student record with updated group pivot data
            $this->record->load('groups');

            Notification::make()
                ->title('Muvaffaqiyatli')
                ->body(number_format($discountAmountToAdd, 0, ',', ' ') . ' so\'m imtiyoz muvaffaqiyatli qo\'shildi.')
                ->success()
                ->send();

        } catch (ValidationException $e) {
            // Validation errors are handled automatically by Livewire/Filament
            // No need to catch specifically unless you want custom handling
            throw $e; // Re-throw for Filament to handle
        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik')
                ->body('Imtiyoz qo\'shishda xatolik yuz berdi: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
    // --- End Imtiyoz Qo'shish Logikasi ---


    // --- Student Removal Logic ---
    public function openRemoveStudentModal(): void
    {
        $this->removalReasonId = null;
        $this->resetErrorBag();
        $this->showRemoveStudentModal = true;
    }

    // Change the return type hint to mixed
    public function removeStudent(): mixed
    {
        try {
            // Use validate() directly for simplicity if only validating one field here
            $validatedData = $this->validate([
                'removalReasonId' => ['required', 'integer', 'exists:removal_reasons,id'],
            ], [
                'removalReasonId.required' => 'Iltimos, safdan chiqarish sababini tanlang.',
                'removalReasonId.exists' => 'Tanlangan sabab mavjud emas.',
            ]);
        } catch (ValidationException $e) {
            // Let Filament handle showing validation errors
            return null;
        }

        try {
            // $student = $this->record; // Already available as $this->record
            $this->record->removal_reason_id = $validatedData['removalReasonId'];
            // No need to save() before delete() if only updating soft delete columns
            // $this->record->save();
            $this->record->delete(); // Soft delete

            $this->showRemoveStudentModal = false;

            Notification::make()
                ->title('Muvaffaqiyatli')
                ->body($this->record->first_name . ' ' . $this->record->last_name . ' muvaffaqiyatli safdan chiqarildi.')
                ->success()
                ->send();

            // Redirect to the students list page using the correct page class
            // Make sure App\Filament\Pages\StudentsPage exists and is registered
            return redirect()->to(\App\Filament\Pages\StudentsPage::getUrl());


        } catch (\Exception $e) {
            Notification::make()
                ->title('Xatolik')
                ->body('Studentni safdan chiqarishda xatolik yuz berdi: ' . $e->getMessage())
                ->danger()
                ->send();

            return null; // Return null on general exception
        }
    }
    // --- End Student Removal Logic ---
}
