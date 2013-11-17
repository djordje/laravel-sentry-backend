<?php namespace Djordje\LaravelSentryBackend\Services\Notifiers;

use Illuminate\Support\Facades\Mail;

class PasswordReset {

	public function send($user)
	{
		$code = $user->getResetPasswordCode();

		Mail::queue(
			array(
				'laravel-sentry-backend::emails.password_reset_html',
				'laravel-sentry-backend::emails.password_reset_txt'
			),
			compact('user', 'code'),
			function($message) use ($user)
			{
				$message->to($user->email)->subject('Your password reset link');
			}
		);
	}

}