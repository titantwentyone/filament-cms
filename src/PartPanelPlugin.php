<?php

namespace Titantwentyone\FilamentCMS;

use Filament\Contracts\Plugin;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Orchestra\Testbench\Http\Middleware\VerifyCsrfToken;
use Titantwentyone\FilamentCMS\Domain\Part\Contracts\Manager;
use Titantwentyone\FilamentCMS\Filament\Resources\PartResource;

class PartPanelPlugin implements Plugin
{
    public static function make(string $manager): static
    {
        //@todo create command to generate empty PartManager class
        app()->bind(Manager::class, $manager);
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-cms-parts';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                PartResource::class
            ]);
    }

    public function boot(Panel $panel): void
    {

    }
}