<?php

namespace App\Filament\Pages\Marketing;

use Filament\Pages\Page;

class MarketingReport extends Page
{
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Marketing hisoboti';
    protected static ?string $title = 'Marketing hisoboti';
    protected static ?int $navigationSort = 9;

    protected static string $view = 'filament.pages.marketing.marketing-report';
}