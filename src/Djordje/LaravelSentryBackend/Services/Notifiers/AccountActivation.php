<?php namespace Djordje\LaravelSentryBackend\Services\Notifiers;

use Illuminate\Support\Facades\Mail;

class AccountActivation {

	public function send($user)
	{
		$code = $user->getActivationCode();

		Mail::queue(
			array(
				'laravel-sentry-backend::emails.activation_code_html',
				'laravel-sentry-backend::emails.activation_code_txt'
			),
			compact('user', 'code'),
			function($message) use ($user)
			{
				$message->to($user->email)->subject('Your account activation code');
			}
		);
	}

}