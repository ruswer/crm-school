<?php

namespace App\Filament\Pages\Education;

use Filament\Pages\Page;

class Cabinets extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Kabinetlar';
    protected static ?string $slug = 'education/cabinets';
    protected static ?int $navigationSort = 16;

    protected static string $view = 'filament.pages.education.cabinets';
}