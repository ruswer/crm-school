<?php

namespace App\Filament\Pages\HR;

use Filament\Pages\Page;
use Livewire\Component;

class Employees extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Xodimlar';
    protected static ?string $title = 'Xodimlar ro\'yxati';
    protected static ?string $slug = 'hr/employees';
    protected static ?int $navigationSort = 18;

    protected static string $view = 'filament.pages.h-r.employees';
}