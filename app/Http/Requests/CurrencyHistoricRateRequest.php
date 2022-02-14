<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyHistoricRateRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'id' => 'bail|required|integer|exists:currencies,id',
			'page' => 'bail|sometimes|integer',
			'page_size' => 'bail|required_with:page',
			'date_from' => 'bail|sometimes|date',
			'date_to' => 'bail|sometimes|date',
			'base_currency_id' => 'bail|sometimes|integer|exists:currencies,id',
		];
	}
}
