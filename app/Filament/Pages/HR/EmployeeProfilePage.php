<?php
namespace App\Filament\Pages\HR;

use Filament\Pages\Page;
use App\Models\Staff;

class EmployeeProfilePage extends Page
{
    protected static ?string $slug = 'hr/employee/{staff}/profile';
    protected static string $view = 'filament.pages.h-r.employee-profile-page';
    protected static bool $shouldRegisterNavigation = false;


    public $staff;
    public string $activeTab = 'profile';


    public function mount($staff)
    {
        $this->staff = Staff::findOrFail($staff);
    }

    public function setActiveTab(string $tabName): void
    {
        $this->activeTab = $tabName;
    }

}