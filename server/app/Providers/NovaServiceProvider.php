<?php

namespace App\Providers;

use App\Interfaces\RoleableInterface;
use App\Nova\Metrics\MentorRequestCount;
use App\Nova\Metrics\UsersPerRole;
use Laravel\Nova\Nova;
use Laravel\Nova\Cards\Help;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                //->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();

		// update route login to change Login controller
		$this->myLoginRoutes();
		$this->myLogoutRoutes();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

	/**
	 * Get the cards that should be displayed on the Nova dashboard.
	 *
	 * @return array
	 */
	protected function cards()
	{
		$user = auth()->user();

		return [
			//new Help,
			(new MentorRequestCount)->canSee(function () use ($user) {
				return $user->hasRole(RoleableInterface::ADMINISTRATOR);
			}),
			(new UsersPerRole)->canSee(function () use ($user) {
				return $user->hasRole(RoleableInterface::ADMINISTRATOR);
			}),
		];
	}

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

	/**
	 * Change LoginController class to use App\Nova\Http\Controllers\Auth\LoginController
	 *
	 * @param array $middleware, default ['web']
	 * @return void
	 */
	public function myLoginRoutes($middleware = ['web'])
	{
		Route::namespace('App\Nova\Http\Controllers\Auth')
			->middleware($middleware)
			->as('nova.')
			->prefix(Nova::path())
			->group(function () {
				Route::get('/login', 'LoginController@showLoginForm');
				Route::post('/login', 'LoginController@login')->name('login');
			});
	}

	public function myLogoutRoutes()
	{
		Route::namespace('App\Nova\Http\Controllers\Auth')
			->domain(config('nova.domain', null))
			->middleware(config('nova.middleware', []))
			->as('nova.')
			->prefix(Nova::path())
			->group(function () {
				Route::get(
					'/logout-then-go-to-front-page',
					'LoginController@logoutThenGoToFrontPage'
				)->name('logoutThenGoToFrontPage');

				Route::get(
					'/logout-then-go-to-client-logout-page',
					'LoginController@logoutThenGoToClientLogoutPage'
				)->name('logoutThenGoToClientLogoutPage');
			});
	}

}
