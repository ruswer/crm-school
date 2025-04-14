<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ExpenseCategoriesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Chiqimlar'; 
    protected static ?string $navigationLabel = 'Chiqim kategoriyalari'; 
    protected static ?string $title = 'Chiqim kategoriyasi';
    protected static ?int $navigationSort = 8; 

    protected static string $view = 'filament.pages.expenses.expense-categories-page';
}