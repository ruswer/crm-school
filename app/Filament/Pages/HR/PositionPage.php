<?php

namespace App\Filament\Pages\HR;

use Filament\Pages\Page;
use App\Models\Position;

class PositionPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Kadrlar Bo\'limi';
    protected static ?string $navigationLabel = 'Lavozim';
    protected static ?string $title = 'Lavozim';
    protected static ?string $slug = 'hr/position';
    protected static ?int $navigationSort = 23;

    protected static string $view = 'filament.pages.h-r.position';
}