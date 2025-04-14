<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PaymentPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'To\'lovlar'; 
    protected static ?string $navigationLabel = 'To\'lov'; 
    protected static ?string $title = 'To\'lov';
    protected static ?int $navigationSort = 3; 

    protected static string $view = 'filament.pages.payments.payment-page';
}