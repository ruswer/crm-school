<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class AuthorizationInfo extends Page
{
    protected static ?string $navigationGroup = 'O\'quvchilar';
    protected static ?string $navigationLabel = 'Avtorizatsiya ma\'lumotlari';
    protected static ?string $title = 'Avtorizatsiya ma\'lumotlari';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.students.authorization-info';
}
