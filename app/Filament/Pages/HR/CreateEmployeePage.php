<?php
 
 namespace App\Filament\Pages\HR;
 
 use App\Models\Branch;
 use App\Models\Department;
 use App\Models\Position;
 use App\Models\Role;
 use App\Models\Staff;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
 use Filament\Forms\Components\TextInput;
 use Filament\Forms\Concerns\InteractsWithForms;
 use Filament\Forms\Contracts\HasForms;
 use Filament\Notifications\Notification;
 use Filament\Pages\Page;
 use Illuminate\Support\Collection;
 use Illuminate\Validation\ValidationException;
 
 class CreateEmployeePage extends Page implements HasForms
 {
     use InteractsWithForms;
 
    protected static bool $shouldRegisterNavigation = false; // Navigatsiyada ko'rinmasin
     protected static ?string $title = 'Yangi Xodim Qo\'shish';
    protected static ?string $slug = 'hr/employees/create'; // Sahifa uchun unikal URL qismi
    protected static string $view = 'filament.pages.h-r.create-employee-page'; // Ishlatiladigan Blade fayli
 
     // --- Forma Ma'lumotlari uchun Public Xususiyatlar ---
     public ?string $first_name = null;
     public ?string $last_name = null;
     public ?int $position_id = null; // position_id ishlatiladi
     public ?int $branch_id = null;
     public ?int $role_id = null;
     public ?int $department_id = null;
     public ?string $email = null;
     public ?string $phone = null;
     public $profile_photo_path = null; // Rasm uchun
     public ?string $status = 'active'; // Boshlang'ich status
 
     public function mount(): void
     {
      // Sahifa yuklanganda formani boshlang'ich qiymatlar bilan to'ldirish
         $this->form->fill([
          'status' => $this->status, // Boshlang'ich statusni formaga o'rnatish
          'status' => $this->status, // Statusni 'active' qilib o'rnatish
         ]);
     }
 
  // --- Form Schema ---
  // --- Forma Sxemasi ---
     protected function getFormSchema(): array
     {
         return [
            FileUpload::make('profile_photo_path')
                ->label('Profil rasmi')
                ->image()
                ->directory('staff-photos') // Rasmlar saqlanadigan papka (storage/app/public/staff-photos)
                ->imagePreviewHeight('100')
                ->loadingIndicatorPosition('left')
                ->columnSpanFull(),
             TextInput::make('first_name')
                 ->label('Ism')
                 ->required()
              ->maxLength(255)
              ->maxLength(255)
              ->columnSpan(1), // Kichik ekranlarda to'liq kenglik
             TextInput::make('last_name')
                 ->label('Familiya')
                 ->required()
              ->maxLength(255)
              ->maxLength(255)
              ->columnSpan(1), // Kichik ekranlarda to'liq kenglik
             TextInput::make('email')
                 ->label('Email')
                 ->email()
                 ->required()
              ->unique(Staff::class, 'email') // Email unikal bo'lishi kerak
              ->maxLength(255)
              ->unique(Staff::class, 'email', ignoreRecord: true) // Unikal bo'lishi kerak
              ->maxLength(255)
              ->columnSpan(1),
             TextInput::make('phone')
                 ->label('Telefon')
                 ->tel()
                 ->required()
              ->unique(Staff::class, 'phone') // Telefon unikal bo'lishi kerak
              ->maxLength(20)
              ->unique(Staff::class, 'phone', ignoreRecord: true) // Unikal bo'lishi kerak
              ->maxLength(20)
              ->columnSpan(1),
             Select::make('branch_id')
                 ->label('Filial')
                 ->options($this->getBranchOptions())
              ->required()
              ->required()
              ->searchable() // Qidirish imkoniyati
              ->columnSpan(1),
             Select::make('department_id')
                 ->label('Bo\'lim')
                 ->options($this->getDepartmentOptions())
              ->required()
              ->required()
              ->searchable()
              ->columnSpan(1),
             Select::make('position_id')
                 ->label('Lavozim')
                 ->options($this->getPositionOptions())
              ->required()
              ->required()
              ->searchable()
              ->columnSpan(1),
             Select::make('role_id')
                 ->label('Rol')
                 ->options($this->getRoleOptions())
              ->required()
              ->required()
              ->searchable()
              ->columnSpan(1),
             Select::make('status')
                 ->label('Holati')
                 ->options([
                     'active' => 'Faol',
                     'inactive' => 'Faol emas',
                 ])
              ->required()
              ->required()
              ->columnSpanFull(), // To'liq kenglik
         ];
     }
 
  // --- Select Options ---
  // --- Select Maydonlari uchun Ma'lumotlar ---
     protected function getBranchOptions(): Collection
     {
      return Branch::pluck('name', 'id');
      return Branch::orderBy('name')->pluck('name', 'id');
     }
 
     protected function getDepartmentOptions(): Collection
     {
      return Department::pluck('name', 'id');
      return Department::orderBy('name')->pluck('name', 'id');
     }
 
     protected function getPositionOptions(): Collection
     {
      return Position::pluck('name', 'id');
      return Position::orderBy('name')->pluck('name', 'id');
     }
 
     protected function getRoleOptions(): Collection
     {
      return Role::pluck('name', 'id');
      return Role::orderBy('name')->pluck('name', 'id');
     }
 
     // --- Xodimni Saqlash Funksiyasi ---
     public function createEmployee(): void
     {
         try {
          // Formadan validatsiya qilingan ma'lumotlarni olish
             $validatedData = $this->form->getState();

             // Agar rasm yuklangan bo'lsa, pathni saqlash
            if ($this->profile_photo_path) {
                $validatedData['profile_photo_path'] = $this->profile_photo_path->store('staff-photos', 'public');
            }

          // Yangi xodimni yaratish
            unset($validatedData['profile_photo_path']); // Agar modelda bo'lmasa
             Staff::create($validatedData);
 
          // Muvaffaqiyatli xabarnoma yuborish
             Notification::make()
                 ->title('Xodim muvaffaqiyatli qo\'shildi')
                 ->success()
                 ->send();
 
          // Muvaffaqiyatli qo'shilgandan so'ng ro'yxat sahifasiga qaytish
          // Xodimlar ro'yxati sahifasiga qaytish
             redirect()->route('filament.admin.pages.hr.employees');
 
         } catch (ValidationException $e) {
          // Validatsiya xatosi avtomatik ko'rsatiladi
          // Validatsiya xatosi yuz berganda (Filament buni avtomatik boshqaradi, lekin xabar yuborish mumkin)
          Notification::make()
              ->title('Validatsiya xatosi')
              ->body('Iltimos, barcha maydonlarni to\'g\'ri to\'ldiring.')
              ->danger()
              ->send();
         } catch (\Exception $e) {
          // Boshqa kutilmagan xatoliklar uchun
             Notification::make()
                 ->title('Xatolik yuz berdi')
                 ->body('Xodimni qo\'shishda xatolik: ' . $e->getMessage())
                 ->danger()
                 ->send();
         }
     }
 }

