<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DeletedStudents extends Page
{
    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'Bitiruvchilar';
    protected static ?string $title = 'Bitiruvchilar';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.students.deleted-students';
}
