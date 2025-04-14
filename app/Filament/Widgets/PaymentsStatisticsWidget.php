<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Payment;

class PaymentsStatisticsWidget extends Widget
{
    protected static string $view = 'filament.widgets.payments-statistics-widget';

    public function getData(): array
    {
        return [
            'totalPayments' => Payment::sum('amount'),
            'totalTransactions' => Payment::count(),
        ];
    }
}
