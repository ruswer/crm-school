<?php

namespace App\Filament\Pages\Exams;

use Filament\Pages\Page;

class ExamsList extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Imtihonlar';
    protected static ?string $navigationLabel = 'Imtihonlar ro\'yxati';
    protected static ?string $title = 'Imtihonlar ro\'yxati';
    protected static ?int $navigationSort = 11;

    protected static string $view = 'filament.pages.exams.exams-list';
}