<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Models\CurrencyRate;

class CurrencyController extends Controller
{
    /**
     * Return list of currencies.
     */
    public function index()
    {
        $date = CurrencyRate::max('date');

        $currencies = Currency::query()
            ->leftJoin('currency_rates', function ($join) use ($date) {
                $join->on('currency_rates.currency_id', '=', 'currencies.id')
                    ->where('currency_rates.date', $date);
            })
            ->selectRaw('currencies.*, currency_rates.rate')
            ->get();

        return CurrencyResource::collection($currencies);
    }
}
