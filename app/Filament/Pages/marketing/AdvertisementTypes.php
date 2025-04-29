<?php

namespace App\Filament\Pages\Marketing;

use App\Models\MarketingSource; // MarketingSource modelini import qiling
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification; // Notification uchun
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder; // Builder uchun
use Livewire\Component; // Livewire Component emas, Page dan meros oladi
use Livewire\WithPagination; // Pagination uchun

class AdvertisementTypes extends Page implements HasForms // HasForms interfeysini qo'shing
{
    use InteractsWithForms; // Formlar bilan ishlash uchun trait
    use WithPagination; // Pagination uchun trait

    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Reklama turlari';
    protected static ?string $title = 'Reklama Turlari'; // Sarlavhani o'zgartirdim
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.marketing.advertisement-types';

    // Komponent holati (state) uchun public o'zgaruvchilar
    public ?array $data = []; // Form ma'lumotlari uchun massiv

    public ?MarketingSource $editingSource = null; // Tahrirlanayotgan manba
    public string $search = ''; // Qidiruv uchun
    public string $sortField = 'name'; // Saralash maydoni
    public string $sortDirection = 'asc'; // Saralash yo'nalishi

    // Sahifa yuklanganda formani bo'shatish
    public function mount(): void
    {
        $this->form->fill(); // Formani bo'sh holatda boshlash
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    // Formani aniqlash
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Reklama turi')
                ->required()
                ->maxLength(255)
                ->placeholder('Reklama nomini kiriting'),
            Textarea::make('description')
                ->label('Tavsifi')
                ->placeholder('Reklama haqida qo\'shimcha ma\'lumot')
                ->rows(3) // Kerakli qatorlar soni
                ->nullable(), 
        ];
    }

    // Formani qayta ishlash (saqlash/yangilash)
    public function save(): void
    {
        $this->validate(); // Form validatsiyasi

        $formData = $this->form->getState();

        if ($this->editingSource) {
            // Yangilash
            $this->editingSource->update($formData);
            Notification::make()
                ->title('Muvaffaqiyatli yangilandi')
                ->success()
                ->send();
        } else {
            // Yaratish
            MarketingSource::create($formData);
            Notification::make()
                ->title('Muvaffaqiyatli saqlandi')
                ->success()
                ->send();
        }

        $this->resetForm(); // Formani tozalash
    }

    // Tahrirlash uchun manbani yuklash
    public function edit(MarketingSource $source): void
    {
        $this->editingSource = $source;
        $this->form->fill($source->toArray()); // Formani to'ldirish
    }

    // Formani va tahrirlash holatini tozalash
    public function resetForm(): void
    {
        $this->editingSource = null;
        $this->form->fill(); // Formani bo'shatish
    }

    // Saralash funksiyasi
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage(); // Saralashda birinchi sahifaga o'tish
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'sortField', 'sortDirection');
        $this->resetPage();
    }

    // Qidiruv o'zgarganda paginationni reset qilish
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Ma'lumotlarni olish (render metodida ishlatiladi)
    protected function getSources()
    {
        return MarketingSource::query()
            ->when($this->search, function (Builder $query, $search) {
                // 'name' yoki boshqa kerakli ustunlar bo'yicha qidirish
                $query->where('name', 'like', '%' . $search . '%');
                // Agar description bo'lsa:
                // ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10); // Har sahifada 10 ta element
    }
}
