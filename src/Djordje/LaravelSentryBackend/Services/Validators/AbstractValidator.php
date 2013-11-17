<?php namespace Djordje\LaravelSentryBackend\Services\Validators;

use Validator;

abstract class AbstractValidator {

	protected $rules = array();

	protected $errors = array();

	public function validate($data)
	{
		$validator = Validator::make($data, $this->rules);

		if ($validator->fails())
		{
			$this->errors = $validator->messages();

			return false;
		}

		return true;
	}

	public function getErrors()
	{
		return $this->errors;
	}

}