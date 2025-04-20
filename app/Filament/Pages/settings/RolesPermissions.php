<?php

namespace App\Filament\Pages\Settings;

use Filament\Pages\Page;
use App\Models\Role;
use App\Models\Permission;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;

class RolesPermissions extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    
    protected static ?string $navigationGroup = 'Tizimni sozlash';

    protected static ?string $navigationLabel = 'Rollar va ruxsatlar';

    protected static ?string $title = 'Rollar va ruxsatlar';

    protected static ?int $navigationSort = 25;

    protected static string $view = 'filament.pages.settings.roles-permissions';

    public $selectedRole = null;
    public $permissions = [];
    public $roleName;
    public $roleDescription;

    public function mount()
    {
        $this->permissions = Permission::all()
            ->groupBy('group')
            ->toArray();
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('selectedRole')
                                ->label('Rolni tanlang')
                                ->options(Role::pluck('name', 'id'))
                                ->reactive()
                                ->afterStateUpdated(fn ($state) => $this->loadRolePermissions($state)),

                            TextInput::make('roleName')
                                ->label('Yangi rol nomi')
                                ->placeholder('Rol nomini kiriting'),
                        ]),

                    TextInput::make('roleDescription')
                        ->label('Rol tavsifi')
                        ->placeholder('Rol haqida qisqacha ma\'lumot')
                ]),

            Section::make('Ruxsatlar')
                ->schema([
                    // Ruxsatlar guruhlar bo'yicha
                    ...collect($this->permissions)->map(function ($items, $group) {
                        return Section::make(Str::title($group))
                            ->schema(
                                collect($items)->map(function ($permission) {
                                    return Checkbox::make("permissions.{$permission['id']}")
                                        ->label($permission['name'])
                                        ->helperText($permission['description'] ?? '')
                                        ->inline();
                                })->toArray()
                            )
                            ->columns(3);
                    })->toArray(),
                ]),
        ];
    }

    public function loadRolePermissions($roleId)
    {
        if (!$roleId) return;

        $role = Role::with('permissions')->find($roleId);
        if (!$role) return;

        $this->roleName = $role->name;
        $this->roleDescription = $role->description;
        
        // Ruxsatlarni belgilash
        $this->permissions = Permission::all()
            ->map(function ($permission) use ($role) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description,
                    'checked' => $role->permissions->contains($permission->id),
                ];
            })
            ->groupBy('group')
            ->toArray();
    }

    public function saveRole()
    {
        // Rol yaratish yoki yangilash
        $role = $this->selectedRole 
            ? Role::find($this->selectedRole)
            : new Role();

        $role->name = $this->roleName;
        $role->description = $this->roleDescription;
        $role->slug = Str::slug($this->roleName);
        $role->save();

        // Ruxsatlarni saqlash
        $selectedPermissions = collect($this->permissions)
            ->flatten(1)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        $role->permissions()->sync($selectedPermissions);

        $this->notify('success', 'Rol muvaffaqiyatli saqlandi');
    }

    public function deleteRole()
    {
        if (!$this->selectedRole) return;

        Role::destroy($this->selectedRole);
        $this->selectedRole = null;
        $this->roleName = null;
        $this->roleDescription = null;

        $this->notify('success', 'Rol muvaffaqiyatli o\'chirildi');
    }
}