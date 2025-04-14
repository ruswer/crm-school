<?php

namespace App\Filament\Pages\Marketing;

use Filament\Pages\Page;

class AdvertisementTypes extends Page
{
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Reklama turlari';
    protected static ?string $title = 'Marketing';
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.marketing.advertisement-types';
}