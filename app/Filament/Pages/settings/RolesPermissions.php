<?php

namespace App\Filament\Pages\Settings;

use App\Models\Role;
use App\Models\Permission;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class RolesPermissions extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Tizimni sozlash';
    protected static ?string $navigationLabel = 'Rollar va ruxsatlar';
    protected static ?string $title = 'Rollar va ruxsatlar';
    protected static ?string $slug = 'roles-permissions';
    protected static ?int $navigationSort = 25;

    protected static string $view = 'filament.pages.settings.roles-permissions';

    public $roleName = '';
    public $permissions = [];
    public $editName = '';
    public $selectedPermissions = [];
    public $roleToDeleteName = '';
    public $modalType = '';
    public $selectedRoleId = null;
    public bool $showModal = false;

    #[Url]
    public $search = '';

    #[Url]
    public $sortField = 'name';

    #[Url]
    public $sortDirection = 'asc';

    public function mount(): void
    {
        $this->permissions = Permission::all()
            ->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description,
                ];
            })
            ->groupBy('group')
            ->toArray();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('roleName')
                ->label('Rol nomi')
                ->required()
                ->maxLength(255)
                ->placeholder('Rol nomini kiriting'),
        ];
    }

    public function getRolesProperty()
    {
        return Role::query()
            ->when($this->search, fn ($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function createRole(): void
    {
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name',
        ], [
            'roleName.required' => 'Rol nomi majburiy.',
            'roleName.unique' => 'Bu rol nomi allaqachon mavjud.',
        ]);

        try {
            $role = new Role();
            $role->name = $this->roleName;
            $role->slug = Str::slug($this->roleName);
            $role->save();

            $this->reset(['roleName']);
            Notification::make()->success()->title('Rol muvaffaqiyatli yaratildi')->send();
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Rolni yaratishda xatolik: ' . $e->getMessage())->send();
        }
    }

    public function assignPermissions($roleId): void
    {
        $this->redirect(route('filament.admin.pages.assign-permissions', ['role_id' => $roleId]));
    }

    public function openModal($type, $roleId): void
    {
        $this->modalType = $type;
        $this->selectedRoleId = $roleId;
        $role = Role::with('permissions')->findOrFail($roleId);

        if ($type === 'edit') {
            $this->editName = $role->name;
            $this->selectedPermissions = [];
            foreach ($this->permissions as $group) {
                foreach ($group as $permission) {
                    $this->selectedPermissions[$permission['id']] = $role->permissions->contains($permission['id']);
                }
            }
        } else {
            $this->roleToDeleteName = $role->name;
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['modalType', 'selectedRoleId', 'editName', 'selectedPermissions', 'roleToDeleteName']);
    }

    public function updateRole(): void
    {
        $this->validate([
            'editName' => 'required|string|max:255|unique:roles,name,' . $this->selectedRoleId,
            'selectedPermissions.*' => 'nullable|boolean',
        ], [
            'editName.required' => 'Rol nomi majburiy.',
            'editName.unique' => 'Bu rol nomi allaqachon mavjud.',
        ]);

        try {
            $role = Role::findOrFail($this->selectedRoleId);
            $role->name = $this->editName;
            $role->slug = Str::slug($this->editName);
            $role->save();

            $selectedPermissionIds = collect($this->selectedPermissions)
                ->filter(fn ($value) => $value)
                ->keys()
                ->toArray();
            $role->permissions()->sync($selectedPermissionIds);

            $this->closeModal();
            Notification::make()->success()->title('Rol muvaffaqiyatli yangilandi')->send();
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Rolni yangilashda xatolik: ' . $e->getMessage())->send();
        }
    }

    public function deleteRole(): void
    {
        try {
            $role = Role::findOrFail($this->selectedRoleId);
            $role->delete();

            $this->closeModal();
            Notification::make()->success()->title('Rol muvaffaqiyatli o\'chirildi')->send();
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Xatolik')->body('Rolni o\'chirishda xatolik: ' . $e->getMessage())->send();
        }
    }
}