<?php namespace Djordje\LaravelSentryBackend;

use Illuminate\Support\ServiceProvider;

class LaravelSentryBackendServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('djordje/laravel-sentry-backend');

		include $this->app['config']->get('laravel-sentry-backend::routes_path');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['router']->filter('AllowForGroup', 'Djordje\LaravelSentryBackend\Filters\AllowForGroup');
		$this->app['router']->filter('AllowForSuperusers', 'Djordje\LaravelSentryBackend\Filters\AllowForSuperusers');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}