<?php

namespace App\Filament\Pages\settings;

// Kerakli klasslarni import qilish
use App\Models\BillingSetting; // YANGI MODELNI IMPORT QILISH
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class BillingSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    // --- Navigatsiya va Sahifa Sozlamalari (o'zgarishsiz) ---
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Tizimni sozlash';
    protected static ?string $navigationLabel = 'Billing Sozlamalari';
    protected static ?string $title = 'Billing Sozlamalari';
    protected static ?int $navigationSort = 25;
    protected static string $view = 'filament.pages.settings.billing-settings-page';

    public ?array $data = [];

    // Billing sozlamalarining yagona instansiyasini saqlash uchun
    public BillingSetting $billingSetting;

    /**
     * Sahifa yuklanganda ishga tushadi.
     * Billing sozlamalarini bazadan o'qib, formani to'ldiradi.
     */
    public function mount(): void
    {
        // Billing sozlamalarining birinchi (va yagona) qatorini olish yoki yaratish
        // Bu sozlamalar jadvalida har doim kamida bitta yozuv bo'lishini ta'minlaydi
        $this->billingSetting = BillingSetting::firstOrCreate([], [
            'points_discount_rate' => 1000.00 // Agar yangi yaratilsa, standart qiymat
        ]);

        // Formani mavjud sozlamalar bilan to'ldirish
        $this->form->fill($this->billingSetting->toArray());
    }

    /**
     * Sahifadagi formani qurish uchun metod.
     *
     * @param Form $form Form obyekti
     * @return Form Form obyekti
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('points_discount_rate')
                    ->label('Ball Chegirma Stavkasi (1 ball uchun so\'m)')
                    ->helperText('O\'quvchi to\'lov qilganda 1 ball necha so\'m chegirma berishini belgilaydi.')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->step(100),
                // --- Kelajakda boshqa billing sozlamalari shu yerga qo'shiladi ---
            ])
            ->statePath('data') // Formadagi ma'lumotlarni $this->data ga bog'lash
            // Modelni formaga bog'lash (avtomatik to'ldirish va saqlash uchun qulay)
            ->model($this->billingSetting);
    }

    /**
     * "Saqlash" tugmasi bosilganda ishga tushadi.
     * Formadagi ma'lumotlarni validatsiya qiladi va bazaga saqlaydi.
     */
    public function save(): void
    {
        try {
            // Formadagi joriy ma'lumotlarni olish (validatsiya bilan birga)
            $formData = $this->form->getState();

            // Olingan ma'lumotlar bilan billing sozlamalarini yangilash
            $this->billingSetting->update($formData);

            // Muvaffaqiyatli saqlanganligi haqida bildirishnoma chiqarish
            Notification::make()
                ->title('Sozlamalar muvaffaqiyatli saqlandi')
                ->success()
                ->send();

        } catch (\Exception $e) {
            // Agar xatolik yuz bersa, bildirishnoma chiqarish
            Notification::make()
                ->title('Xatolik')
                ->body('Sozlamalarni saqlashda xatolik yuz berdi: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
