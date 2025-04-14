<?php

namespace App\Filament\Pages\Education;

use Filament\Pages\Page;

class Courses extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Kurslar';
    protected static ?string $slug = 'education/courses';
    protected static ?int $navigationSort = 14;

    protected static string $view = 'filament.pages.education.courses';
}