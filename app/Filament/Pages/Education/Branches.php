<?php

namespace App\Filament\Pages\Education;

use Filament\Pages\Page;

class Branches extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Filiallar';
    protected static ?string $slug = 'education/branches';
    protected static ?int $navigationSort = 13;

    protected static string $view = 'filament.pages.education.branches';
}
