<?php

namespace App\Filament\Pages\HR;

use Filament\Pages\Page;
use Livewire\Component;

class Attendance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Xodimlar davomati';
    protected static ?string $title = 'Xodimlar davomati';
    protected static ?string $slug = 'hr/attendance';
    protected static ?int $navigationSort = 19;

    protected static string $view = 'filament.pages.h-r.attendance';
}