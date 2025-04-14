<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DebtorsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle'; 
    protected static ?string $navigationGroup = 'To\'lovlar'; 
    protected static ?string $navigationLabel = 'Qarzdorlar';
    protected static ?string $title = 'Qarzdorlar'; 
    protected static ?int $navigationSort = 4; 

    protected static string $view = 'filament.pages.payments.debtors-page';
}