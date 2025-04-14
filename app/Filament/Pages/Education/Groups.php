<?php

namespace App\Filament\Pages\Education;

use Filament\Pages\Page;

class Groups extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Guruhlar';
    protected static ?string $slug = 'education/groups';
    protected static ?int $navigationSort = 12;

    protected static string $view = 'filament.pages.education.groups';
}