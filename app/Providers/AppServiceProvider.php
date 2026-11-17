<?php

namespace App\Providers;

use App\Models\VoiceCall;
use App\Policies\VoiceCallPolicy;
use App\Services\ModuleManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class);
    }

    public function boot(): void
    {
        Gate::policy(VoiceCall::class, VoiceCallPolicy::class);
    }
}
