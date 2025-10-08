<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Configure the authentication to work with our custom password field
        Auth::provider('custom', function ($app, array $config) {
            return new class($config) extends \Illuminate\Auth\EloquentUserProvider {
                public function validateCredentials($user, array $credentials)
                {
                    $plain = $credentials['PasswordKry'];
                    $hashed = $user->getAuthPassword();

                    return Hash::check($plain, $hashed);
                }
            };
        });
    }
}