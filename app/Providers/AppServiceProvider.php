<?php

namespace App\Providers;

use App\Models\User;
use App\Services\MockService;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // MOCK API: override user provider to read from JSON for Auth::user()
        $this->app['auth']->provider('mock', function ($app, $config) {
            return new class($app['hash'], $config['model']) extends EloquentUserProvider {
                public function retrieveById($identifier)
                {
                    $user = (new User())->resolveRouteBinding((int) $identifier);
                    return $user;
                }

                public function retrieveByCredentials(array $credentials)
                {
                    $user = MockService::load('users')->firstWhere('email', $credentials['email'] ?? '');
                    if (!$user) return null;
                    return (new User())->resolveRouteBinding($user->id);
                }

                public function validateCredentials(\Illuminate\Contracts\Auth\Authenticatable $user, array $credentials)
                {
                    return ($credentials['password'] ?? '') === 'password';
                }
            };
        });

        $this->app['config']->set('auth.providers.users.driver', 'mock');
    }
}
