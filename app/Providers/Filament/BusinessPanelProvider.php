<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetTenantContext;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class BusinessPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('business')
            ->path('app')
            ->login()
            ->brandName('Social Commerce')
            ->colors(['primary' => Color::Teal])
            ->discoverResources(in: app_path('Filament/Business/Resources'), for: 'App\\Filament\\Business\\Resources')
            ->discoverPages(in: app_path('Filament/Business/Pages'), for: 'App\\Filament\\Business\\Pages')
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Business/Widgets'), for: 'App\\Filament\\Business\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Business\Widgets\StatsOverview::class,
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
                SetTenantContext::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
