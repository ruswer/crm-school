<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Parents extends Page
{
    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'Ota-ona';
    protected static ?string $title = 'Ota-ona';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.students.parents';
}
