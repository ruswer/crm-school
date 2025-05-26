<?php

namespace App\Filament\Pages\Settings;

use App\Models\Role;
use App\Models\Permission;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;

class AssignPermissions extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Tizimni sozlash';
    protected static ?string $navigationLabel = 'Ruxsatlarni belgilash';
    protected static ?string $title = 'Ruxsatlarni belgilash';
    protected static ?string $slug = 'assign-permissions';
    protected static ?int $navigationSort = 26;
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.settings.assign-permissions';

    // public static function canAccess(): bool
    // {
    //     return auth()->user()->hasRole('super_admin');
    // }

    public $roleId;
    public $roleName = '';
    public $selectedPermissions = [];
    public $modules = [];

    public function mount(): void
    {
        $role_id = Request::query('role_id');

        if (!$role_id || !is_numeric($role_id)) {
            Notification::make()->danger()->title('Xatolik')->body('Rol ID topilmadi yoki noto‘g‘ri.')->send();
            $this->redirect(route('filament.admin.pages.roles-permissions'));
            return;
        }

        $this->roleId = $role_id;
        $role = Role::find($role_id);

        if (!$role) {
            Notification::make()->danger()->title('Xatolik')->body('Belgilangan rol topilmadi.')->send();
            $this->redirect(route('filament.admin.pages.roles-permissions'));
            return;
        }

        $this->roleName = $role->name;

        // Modullar va xususiyatlar ro‘yxati (yangilangan)
        $this->modules = [
            'O‘quvchilar' => [
                ['name' => 'O‘quvchilar ro‘yxati/ma\'lumotlari', 'slug' => 'students'],
                ['name' => 'Ota-onalar', 'slug' => 'parents'],
                ['name' => 'O‘quvchi holatini boshqarish (masalan, safdan chiqarish)', 'slug' => 'student_status'],
                ['name' => 'O‘quvchining saytga kirish ma‘lumotlari', 'slug' => 'student_login'],
            ],
            'To‘lovlar' => [
                ['name' => 'To‘lovlar (qo‘shish, ko‘rish, tahrirlash, o‘chirish)', 'slug' => 'payments'],
                ['name' => 'Hisob-kitob varaqasi (Balans)', 'slug' => 'balance_sheet'],
            ],
            'Chiqimlar' => [
                ['name' => 'Chiqimlar (qo‘shish, ko‘rish, tahrirlash, o‘chirish)', 'slug' => 'expenses'],
                ['name' => 'Chiqim kategoriyalari', 'slug' => 'expense_categories'],
            ],
            'Marketing' => [
                ['name' => 'Bildirishnomalar', 'slug' => 'notifications'],
                ['name' => 'SMS yuborish', 'slug' => 'sms_sending'],
                ['name' => 'SMS jurnali', 'slug' => 'sms_log'],
            ],
            'Imtihon' => [
                ['name' => 'Imtihonlar (qo‘shish, ko‘rish, tahrirlash, o‘chirish)', 'slug' => 'exams'],
                ['name' => 'Ballarni qo‘yish', 'slug' => 'grade_entry'],
                ['name' => 'Baholash tizimi', 'slug' => 'grading_system'],
                ['name' => 'Imtihonlar jadvali', 'slug' => 'exam_schedule'],
            ],
            'Ta‘lim' => [
                ['name' => 'Darslar jadvali', 'slug' => 'timetable'],
                ['name' => 'Filiallar', 'slug' => 'branches'],
                ['name' => 'Guruhlar', 'slug' => 'groups'],
                ['name' => 'Guruh rahbarini belgilash', 'slug' => 'assign_group_leader'],
            ],
            'Kadrlar bo‘limi' => [
                ['name' => 'Xodimlar (qo‘shish, ko‘rish, tahrirlash, o‘chirish)', 'slug' => 'employees'],
                ['name' => 'Xodimni ishdan bo‘shatish', 'slug' => 'employee_termination'],
                ['name' => 'Xodimlar davomati', 'slug' => 'employee_attendance'],
                ['name' => 'Xodimlar davomati hisoboti', 'slug' => 'employee_attendance_report'],
                ['name' => 'Ish haqi', 'slug' => 'salary'],
                ['name' => 'Ish-haqi hisoboti', 'slug' => 'salary_report'],
                ['name' => 'Bo‘limlar', 'slug' => 'departments'],
                ['name' => 'Lavozimlar', 'slug' => 'positions'],
                ['name' => 'Foydalanuvchilar profilini ko‘ra oladi', 'slug' => 'view_user_profiles'],
            ],
            'Tizimni sozlash' => [
                ['name' => 'Tillar', 'slug' => 'languages'],
                ['name' => 'Umumiy sozlamalar', 'slug' => 'general_settings'],
                ['name' => 'Billing Sozlamalari', 'slug' => 'billing_settings'],
                ['name' => 'Rollar va Ruxsatlar', 'slug' => 'roles_permissions'],
                ['name' => 'Rezerv nusxalash', 'slug' => 'backup'],
                ['name' => 'Tiklash', 'slug' => 'restore'],
            ],
            'Konsol va vidjetlar' => [
                ['name' => 'Sessiyani o‘zgartirish', 'slug' => 'change_session'],
                ['name' => 'Daromad va chiqimning oylik diagrammasi', 'slug' => 'monthly_income_expense_chart'],
                ['name' => 'Daromad va chiqimning yillik diagrammasi', 'slug' => 'yearly_income_expense_chart'],
                ['name' => 'Mablag‘ yig‘ish vidjeti', 'slug' => 'fund_collection_widget'],
                ['name' => 'Chiqimlar vidjeti', 'slug' => 'expense_widget'],
                ['name' => 'O‘quvchilar soni vidjeti', 'slug' => 'student_count_widget'],
                ['name' => 'Xodimlar soni vidjeti', 'slug' => 'employee_count_widget'],
                ['name' => 'Tug‘ilgan kun vidjeti', 'slug' => 'birthday_widget'],
            ],
        ];

        // Ruxsatlarni tayyorlash
        foreach ($this->modules as $module => &$features) {
            foreach ($features as &$feature) {
                $feature['permissions'] = [
                    'view' => "view_{$feature['slug']}",
                    'create' => "create_{$feature['slug']}",
                    'edit' => "edit_{$feature['slug']}",
                    'delete' => "delete_{$feature['slug']}",
                ];
            }
        }

        // Joriy rolning ruxsatlarini olish
        $currentRolePermissionNames = $role->permissions->pluck('name')->toArray();

        // $selectedPermissions massivini assotsiativ massiv sifatida tayyorlash
        $this->selectedPermissions = [];
        foreach ($this->modules as $module => $features) {
            foreach ($features as $feature) {
                // 'permissions' kaliti mavjudligini va massiv ekanligini tekshirish
                if (isset($feature['permissions']) && is_array($feature['permissions'])) {
                    foreach ($feature['permissions'] as $action => $permissionName) {
                        $this->selectedPermissions[$permissionName] = in_array($permissionName, $currentRolePermissionNames);
                    }
                }
            }
        }
    }
    

    public function savePermissions(): void
{
    try {
        $role = Role::findOrFail($this->roleId);

        // selectedPermissions massivini tozalash
        $selectedPermissionNames = collect($this->selectedPermissions)
            ->filter(fn($value) => $value === true || $value === '1' || $value === 'on')
            ->keys()
            ->toArray();

        // Tekshirish uchun log yoki dd
        \Log::info('Selected Permission Names:', $selectedPermissionNames);
        // yoki: dd($selectedPermissionNames);

        $selectedPermissionIds = Permission::whereIn('name', $selectedPermissionNames)->pluck('id')->toArray();

        // Permission ID'larni tekshirish
        \Log::info('Selected Permission IDs:', $selectedPermissionIds);
        // yoki: dd($selectedPermissionIds);

        $role->permissions()->sync($selectedPermissionIds);

        Notification::make()->success()->title('Ruxsatlar muvaffaqiyatli belgilandi')->send();
        $this->redirect(route('filament.admin.pages.roles-permissions'));
    } catch (\Exception $e) {
        \Log::error('Error saving permissions: ' . $e->getMessage());
        Notification::make()->danger()->title('Xatolik')->body('Ruxsatlarni belgilashda xatolik: ' . $e->getMessage())->send();
    }
}
}