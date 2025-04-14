<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class AddStudent extends Page
{
    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'O\'quvchini qo\'shish';
    protected static ?string $title = 'O\'quvchini qo\'shish';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.students.add-student';
}
