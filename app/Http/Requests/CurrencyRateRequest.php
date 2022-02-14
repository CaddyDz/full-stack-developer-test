<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRateRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'page' => 'bail|sometimes|integer',
			'page_size' => 'bail|required_with:page',
			'date' => 'bail|sometimes|date',
			'base_currency_id' => 'bail|sometimes|integer|exists:currencies,id',
		];
	}
}
