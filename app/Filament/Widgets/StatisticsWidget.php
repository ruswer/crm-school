<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatisticsWidget extends Widget
{
    protected static string $view = 'filament.widgets.statistics-widget';

    public function getData(): array
    {
        return [
            'studentsCount' => Student::count(),
            'coursesCount' => Course::count(),
            'enrollmentsCount' => Enrollment::count(),
        ];
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Jami talabalar', '24k')
                ->description('3% o\'sish')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            Stat::make('O\'rtacha davomat', '98%')
                ->description('2% ko\'tarilish')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            Stat::make('Faol talabalar', '95%')
                ->description('Oxirgi 7 kun')
                ->descriptionIcon('heroicon-m-information-circle')
                ->color('info'),
        ];
    }
}
