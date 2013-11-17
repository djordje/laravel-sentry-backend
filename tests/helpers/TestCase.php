<?php namespace Djordje\LaravelSentryBackend\tests\helpers;

class TestCase extends \Orchestra\Testbench\TestCase {

	protected function getPackageProviders()
	{
		return array(
			'Djordje\\LaravelTwbsHelpers\\LaravelTwbsHelpersServiceProvider',
			'Djordje\\LaravelBackendLayout\\LaravelBackendLayoutServiceProvider',
			'Cartalyst\\Sentry\SentryServiceProvider',
			'Djordje\\LaravelSentryBackend\\LaravelSentryBackendServiceProvider'
		);
	}

}