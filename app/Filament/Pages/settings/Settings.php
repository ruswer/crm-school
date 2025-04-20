<?php

namespace App\Filament\Pages\settings;

use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Tizimni sozlash';
    protected static ?string $navigationLabel = 'Sozlamalar';
    protected static ?string $title = 'Sozlamalar';
    protected static ?int $navigationSort = 24;

    protected static string $view = 'filament.pages.settings.settings';
}