<x-filament::widget>
    <div>
        <h2>Payments Statistics</h2>
        <ul>
            <li>Total Payments: ${{ number_format($this->totalPayments, 2) }}</li>
            <li>Total Transactions: {{ $this->totalTransactions }}</li>
        </ul>
    </div>
</x-filament::widget>
