<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SearchExpensesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationGroup = 'Chiqimlar'; 
    protected static ?string $navigationLabel = 'Chiqimlarni izlash'; 
    protected static ?string $title = 'Chiqimlarni izlash';
    protected static ?int $navigationSort = 7; 

    protected static string $view = 'filament.pages.expenses.search-expenses-page';
}