<?php

namespace App\Filament\Pages\HR;

use Filament\Pages\Page;
use App\Models\Employee;

class AttendanceReport extends Page
{
// Yangi versiya
protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Xodimlar davomat hisoboti';
    protected static ?string $title = 'Xodimlar davomat hisoboti';
    protected static ?string $slug = 'hr/attendance-report';
    protected static ?int $navigationSort = 20;
    
    protected static string $view = 'filament.pages.h-r.attendance-report';
}