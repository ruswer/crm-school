<?php

namespace App\Filament\Pages\Education;

use Filament\Pages\Page;

class KnowledgeLevels extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Ta\'lim';
    protected static ?string $title = 'Bilim darajasi';
    protected static ?string $slug = 'education/knowledge-levels';
    protected static ?int $navigationSort = 15;

    protected static string $view = 'filament.pages.education.knowledge-levels';
}