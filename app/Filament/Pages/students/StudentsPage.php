<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Navigation\NavigationGroup;


class StudentsPage extends Page
{
    protected static ?string $navigationGroup = 'O\'quvchilar'; // "Students Page" guruhiga joylashtirish
    protected static ?string $navigationLabel = 'O\'quvchilar'; // Tugma nomi
    protected static ?string $title = 'O\'quvchilar'; // Sahifa sarlavhasi
    protected static ?int $navigationSort = 1; // Tartibni belgilash

    protected static string $view = 'filament.pages.students.students-page';
}
