<?php

namespace App\Filament\Pages\Marketing;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use Carbon\Carbon;

class MarketingReport extends Page
{
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Marketing hisoboti';
    protected static ?string $title = 'Marketing hisoboti';
    protected static ?int $navigationSort = 9;

    protected static string $view = 'filament.pages.marketing.marketing-report';

    public ?string $startDate = null;
    public ?string $endDate = null;

    public array $advertisementReportData = [];
    public array $genderDistributionData = [];
    public int $totalStudents = 0;
    public int $totalAdvertisementCount = 0;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        $this->search();
    }

    public function search(): void
    {
        $validatedStartDate = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $validatedEndDate = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        $baseQuery = Student::query()
            ->when($validatedStartDate, function ($query, $date) {
                return $query->where('created_at', '>=', $date);
            })
            ->when($validatedEndDate, function ($query, $date) {
                return $query->where('created_at', '<=', $date);
            });

        $this->totalStudents = (clone $baseQuery)->count();

        $this->advertisementReportData = (clone $baseQuery)
            ->select('marketing_source_id', DB::raw('count(*) as count'))
            ->groupBy('marketing_source_id')
            ->with('marketingSource')
            ->get()
            ->map(function ($item) {
                 $name = $item->marketingSource?->name ?? 'Noma\'lum';
                 return ['name' => $name, 'count' => $item->count];
            })
            ->sortByDesc('count')
            ->toArray();

        $this->totalAdvertisementCount = array_sum(array_column($this->advertisementReportData, 'count'));

        $this->genderDistributionData = (clone $baseQuery)
            ->select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get()
            ->mapWithKeys(function ($item) {
                $genderName = match (strtolower($item->gender)) {
                    'male' => 'Erkak',
                    'female' => 'Ayol',
                    default => 'Noma\'lum',
                };
                return [$genderName => $item->count];
            })
            ->toArray();

        $this->dispatch(
            'gender-chart-update',
            labels: array_keys($this->genderDistributionData),
            values: array_values($this->genderDistributionData)
        );

        $this->dispatch(
            'advertisement-chart-update',
            labels: array_column($this->advertisementReportData, 'name'),
            values: array_column($this->advertisementReportData, 'count')
        );
    }
}
