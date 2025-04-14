<?php

namespace App\Filament\Pages\Education;

use Filament\Pages\Page;

class Schedule extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $navigationLabel = 'Darslar jadvali';
    protected static ?string $title = 'Darslar jadvali';
    protected static ?string $slug = 'education/schedule';
    protected static ?int $navigationSort = 17;

    protected static string $view = 'filament.pages.education.schedule';

}