<?php

namespace App\Filament\Pages\Students;

use Filament\Pages\Page;
use Filament\Facades\Filament;
use App\Models\Branch;
use App\Models\Student;
use App\Models\Parents;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Livewire\WithFileUploads;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class ImportStudents extends Page
{
    protected static ?string $navigationGroup = 'O‘quvchilar';
    protected static ?string $navigationLabel = 'O‘quvchilarni Import qilish';
    protected static ?string $slug = 'student-import';
    protected static ?string $title = 'O‘quvchilarni Import qilish'; // Sarlavhani o'zgartirdim
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.students.import-students';

    public $branches = [];
    public $selectedBranch = ''; // Boshlang'ich qiymat
    public $excelFile;

    use WithFileUploads; // Fayl yuklash treytini qo'shish

    public function mount(): void
    {
        // Barcha aktiv filiallarni olish
        $this->branches = Branch::where('status', 'active')->orderBy('name')->get();
        $this->excelFile = null; // Har sahifa yuklanganda faylni tozalash
    }

    public static function getRouteName(?string $panel = null): string
    {
        // Filament::getDefaultPanel() null qaytarishi mumkin, agar panel topilmasa.
        // Shuning uchun config dan olish yaxshiroq.
        $defaultPanelId = config('filament.default_panel', 'admin'); // Yoki sizning standart panel IDngiz
        $panelId = $panel ?? Filament::getPanel($defaultPanelId)?->getId() ?? $defaultPanelId;
        return 'filament.' . $panelId . '.pages.' . static::getSlug();
    }

    protected function getFormSchema(): array
    {
        return [
            // Agar Filament Forms dan foydalanmoqchi bo'lsangiz, bu yerga schema qo'shishingiz mumkin
            // Hozircha biz to'g'ridan-to'g'ri Livewire xususiyatlaridan foydalanamiz
        ];
    }

    public function importStudents()
    {
        $this->validate([
            'selectedBranch' => 'required|exists:branches,id',
            'excelFile' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB
        ], [
            'selectedBranch.required' => 'Iltimos, filialni tanlang.',
            'selectedBranch.exists' => 'Tanlangan filial mavjud emas.',
            'excelFile.required' => 'Iltimos, import uchun faylni yuklang.',
            'excelFile.file' => 'Yuklangan ma\'lumot fayl bo\'lishi kerak.',
            'excelFile.mimes' => 'Fayl formati XLSX yoki XLS bo\'lishi kerak.',
            'excelFile.max' => 'Fayl hajmi 10MB dan oshmasligi kerak.',
        ]);

        try {
            DB::beginTransaction();

            $filePath = $this->excelFile->getRealPath();
            $import = new StudentsImport($this->selectedBranch); // Import klassiga filial ID sini uzatamiz
            Excel::import($import, $filePath);

            DB::commit();

            Notification::make()
                ->title('Muvaffaqiyatli import qilindi')
                ->body("O'quvchilar ma'lumotlari muvaffaqiyatli tizimga yuklandi. Ko'rib chiqilgan qatorlar: " . $import->getRowCount() . ". Muvaffaqiyatli import qilindi: " . $import->getImportedCount() . " ta.")
                ->success()
                ->send();

            // Faylni va tanlangan filialni tozalash
            $this->excelFile = null;
            $this->selectedBranch = '';
            // Livewire fayl inputini tozalash uchun JavaScript hodisasini yuborish
            $this->dispatch('file-input-reset'); // Hodisa nomini o'zgartirdim


        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Qator " . $failure->row() . ": " . implode(', ', $failure->errors());
            } // Bu yerda } yopilishi kerak edi
            Notification::make()
                ->title('Import qilishda xatolik (Validatsiya)')
                ->danger()
                ->body(implode("\n", $errorMessages))
                ->persistent()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Import qilishda kutilmagan xatolik')
                ->danger()
                ->body($e->getMessage())
                ->persistent() // Xatolik xabari yo'qolmasligi uchun
                ->send();
        }
    }
}