<?php

namespace App\Filament\Pages\Settings;

use App\Models\Settings as SettingsModel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class SettingPage extends Page
{
    protected static ?string $navigationGroup = 'Tizimni sozlash';
    protected static ?string $navigationLabel = 'Sozlamalar';
    protected static ?string $title = 'Sozlamalar';
    protected static ?string $slug = 'settings';
    protected static ?int $navigationSort = 24;

    protected static string $view = 'filament.pages.settings.settings';

    use InteractsWithForms;
    use WithFileUploads;

    public $logo = null;
    public $settings = null;
    public $editSettings = [
        'center_name' => '',
        'address' => '',
        'phone' => '',
        'email' => '',
        'session' => '',
        'language' => '',
        'daily_payment' => '',
        'timezone' => '',
    ];
    public bool $showLogoModal = false;
    public bool $showSettingsModal = false;

    protected function getForms(): array
    {
        return [
            'logoForm' => $this->makeForm()
                ->schema([
                    FileUpload::make('logo')
                        ->label('Logotip')
                        ->image()
                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml'])
                        ->maxSize(2048)
                        ->directory('logos')
                        ->required()
                        ->preserveFilenames(),
                ]),
            'settingsForm' => $this->makeForm()
                ->schema([
                    TextInput::make('center_name')
                        ->label('O\'quv markazi nomi')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('address')
                        ->label('Manzil')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('phone')
                        ->label('Telefon raqam')
                        ->required()
                        ->maxLength(20),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('session')
                        ->label('Sessiya')
                        ->numeric()
                        ->required(),
                    TextInput::make('language')
                        ->label('Til')
                        ->required()
                        ->maxLength(50),
                    TextInput::make('daily_payment')
                        ->label('Kunlar bo\'yicha to\'lov')
                        ->numeric()
                        ->required(),
                    TextInput::make('timezone')
                        ->label('Vaqt mintaqasi')
                        ->required()
                        ->maxLength(100),
                ]),
        ];
    }

    public function mount(): void
    {
        $this->settings = SettingsModel::first() ?? new SettingsModel();
        $this->editSettings = [
            'center_name' => $this->settings->center_name ?? 'School Management',
            'address' => $this->settings->address ?? 'Toshkent sh., Chilonzor t., 1-uy',
            'phone' => $this->settings->phone ?? '+998 90 123 45 67',
            'email' => $this->settings->email ?? 'example@info.com',
            'session' => $this->settings->session ?? 2,
            'language' => $this->settings->language ?? 'Uzbek',
            'daily_payment' => $this->settings->daily_payment ?? 30,
            'timezone' => $this->settings->timezone ?? 'Asia/Tashkent (UTC+5)',
        ];
        $this->logo = null; // Boshlang'ich holatda null
        $this->logoForm->fill(['logo' => $this->settings->logo_path]);
    }

    public function openLogoModal(): void
    {
        $this->showLogoModal = true;
        $this->logoForm->fill(['logo' => null]); // Modal ochilganda formani tozalash
    }

    public function closeLogoModal(): void
    {
        $this->showLogoModal = false;
        $this->logo = null;
        $this->logoForm->fill(['logo' => $this->settings->logo_path]);
    }

    public function saveLogo(): void
    {
        $validatedData = $this->logoForm->getState();

        try {
            if ($this->settings->logo_path && Storage::exists($this->settings->logo_path)) {
                Storage::delete($this->settings->logo_path);
            }

            $this->settings->logo_path = $validatedData['logo'];
            $this->settings->save();

            $this->logo = null; // Saqlangandan keyin $logo ni tozalash
            Notification::make()->success()->title('Logotip muvaffaqiyatli saqlandi')->send();
            $this->closeLogoModal();
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Logotipni saqlashda xatolik: ' . $e->getMessage())->send();
        }
    }

    public function openSettingsModal(): void
    {
        $this->showSettingsModal = true;
    }

    public function saveSettings(): void
    {
        $this->validate([
            'editSettings.center_name' => 'required|string|max:255',
            'editSettings.address' => 'required|string|max:255',
            'editSettings.phone' => 'required|string|max:20',
            'editSettings.email' => 'required|email|max:255',
            'editSettings.session' => 'required|numeric',
            'editSettings.language' => 'required|string|max:50',
            'editSettings.daily_payment' => 'required|numeric',
            'editSettings.timezone' => 'required|string|max:100',
        ], [
            'editSettings.center_name.required' => 'O\'quv markazi nomi majburiy.',
            'editSettings.address.required' => 'Manzil majburiy.',
            'editSettings.phone.required' => 'Telefon raqam majburiy.',
            'editSettings.email.required' => 'Email majburiy.',
            'editSettings.email.email' => 'Email noto\'g\'ri formatda.',
            'editSettings.session.required' => 'Sessiya majburiy.',
            'editSettings.language.required' => 'Til majburiy.',
            'editSettings.daily_payment.required' => 'Kunlar bo\'yicha to\'lov majburiy.',
            'editSettings.timezone.required' => 'Vaqt mintaqasi majburiy.',
        ]);

        try {
            $this->settings->update($this->editSettings);
            Notification::make()->success()->title('Sozlamalar muvaffaqiyatli yangilandi')->send();
            $this->closeSettingsModal();
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Sozlamalarni yangilashda xatolik: ' . $e->getMessage())->send();
        }
    }

    public function closeSettingsModal(): void
    {
        $this->showSettingsModal = false;
        $this->editSettings = [
            'center_name' => $this->settings->center_name ?? 'School Management',
            'address' => $this->settings->address ?? 'Toshkent sh., Chilonzor t., 1-uy',
            'phone' => $this->settings->phone ?? '+998 90 123 45 67',
            'email' => $this->settings->email ?? 'example@info.com',
            'session' => $this->settings->session ?? 2,
            'language' => $this->settings->language ?? 'Uzbek',
            'daily_payment' => $this->settings->daily_payment ?? 30,
            'timezone' => $this->settings->timezone ?? 'Asia/Tashkent (UTC+5)',
        ];
    }
}