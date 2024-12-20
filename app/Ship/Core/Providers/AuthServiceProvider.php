<?php

declare(strict_types=1);

namespace App\Ship\Core\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as LaravelAuthServiceProvider;

abstract class AuthServiceProvider extends LaravelAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
