<?php

namespace App\Providers\Filament;

use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/app.css')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandName('CRM')
            ->darkMode(false)
            ->sidebarWidth('300px')
            ->maxContentWidth('1200px')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // O'quvchilar bo'limi
                \App\Filament\Pages\StudentsPage::class,
                \App\Filament\Pages\AddStudent::class,
                \App\Filament\Pages\Parents::class,
                \App\Filament\Pages\AuthorizationInfo::class,
                \App\Filament\Pages\DeletedStudents::class,
                \App\Filament\Pages\Students\StudentProfilePage::class,
                
                // To'lovlar bo'limi
                \App\Filament\Pages\PaymentPage::class,
                \App\Filament\Pages\DebtorsPage::class,

                //Chiqimlar bo'limi
                \App\Filament\Pages\AddExpensePage::class,
                \App\Filament\Pages\ExpenseCategoriesPage::class,
                \App\Filament\Pages\SearchExpensesPage::class,

                // Marketing bo'limi
                \App\Filament\Pages\marketing\MarketingReport::class,
                \App\Filament\Pages\marketing\AdvertisementTypes::class,

                // Imtihonlar bo'limi
                \App\Filament\Pages\Exams\ExamsList::class,

                // Ta'lim bo'limi
                \App\Filament\Pages\Education\Groups::class,
                \App\Filament\Pages\Education\Branches::class,
                \App\Filament\Pages\Education\Courses::class,
                \App\Filament\Pages\Education\KnowledgeLevels::class,
                \App\Filament\Pages\Education\Cabinets::class,

                ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
    public function boot()
    {
        FilamentAsset::register([
            Js::make('app', asset('js/filament/filament/app.js')),
        ]);
    }
}