<?php

namespace App\Filament\Pages\HR;

use Filament\Pages\Page;
use App\Models\Department;
use Illuminate\Support\Carbon;

class DepartmentPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Bo\'lim';
    protected static ?string $title = 'Bo\'lim';
    protected static ?string $slug = 'hr/department';
    protected static ?int $navigationSort = 22;

    protected static string $view = 'filament.pages.h-r.department';
}