<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Resources\CurrencyResource;
use App\Models\{Currency, CurrencyRate};
use Illuminate\Database\Query\JoinClause;
use App\Http\Requests\CurrencyRateRequest;

class CurrencyController extends Controller
{
    /**
     * Return list of currencies.
     *
     * @param \App\Http\Requests\CurrencyRateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CurrencyRateRequest $request): Response
    {
        $currencies_query = Currency::query()
            ->leftJoin(
                'currency_rates',
                fn ($join) =>
                $join->on('currency_rates.currency_id', '=', 'currencies.id')
                    ->when( // If the user specifies the date, constrain by that
                        $request->has('date'),
                        fn (JoinClause $query): JoinClause =>
                        $query->where('currency_rates.date', $request->date)
                    )->when( // Otherwise use tha maximum date of a currency we have
                        $request->missing('date'),
                        fn (JoinClause $query): JoinClause =>
                        $query->where('currency_rates.date', CurrencyRate::max('date'))
                    )
            )->select(
                'currencies.id',
                'currencies.nominal',
                'currencies.name',
                'currency_rates.rate'
            );

        if ($request->has('page')) {
            $currencies = $currencies_query->paginate($request->page_size);
        } else {
            $currencies = $currencies_query->get();
        }

        // Note that the default rate is in Russian Ruble
        // Nominal matter
        if ($request->has('base_currency_id')) {
            $base_rate = CurrencyRate::whereCurrencyId($request->base_currency_id)
                ->value('rate');
            $currencies = $currencies->each(function ($currency) use ($base_rate) {
                $new_rate = $currency->rate / ($base_rate * $currency->nominal);


                $currency->rate = number_format($new_rate, 4);
                return $currency;
            });
        }
        $maximum_rate = $currencies->max('rate');
        $minimum_rate = $currencies->min('rate');
        $average_rate = $currencies->avg('rate');

        $currencies = CurrencyResource::collection($currencies);

        return response([
            'minimum_rate' => $minimum_rate,
            'maximum_rate' => $maximum_rate,
            'average_rate' => number_format($average_rate, 4),
            'currencies' => $currencies,
        ]);
    }
}
