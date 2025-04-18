<?php

namespace App\Filament\Pages\HR;

use Filament\Pages\Page;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Carbon;

class Salary extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Ish haqi';
    protected static ?string $title = 'Ish haqi';
    protected static ?string $slug = 'hr/salary';
    protected static ?int $navigationSort = 21;

    protected static string $view = 'filament.pages.h-r.salary';
}