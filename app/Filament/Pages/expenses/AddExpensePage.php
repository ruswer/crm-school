<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class AddExpensePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationGroup = 'Chiqimlar'; 
    protected static ?string $navigationLabel = 'Chiqim qo\'shish'; 
    protected static ?string $title = 'Chiqimlar';
    protected static ?int $navigationSort = 6; 

    protected static string $view = 'filament.pages.expenses.add-expense-page';
}