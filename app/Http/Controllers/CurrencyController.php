<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Resources\CurrencyResource;
use App\Models\{Currency, CurrencyRate};
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\{CurrencyRateRequest, CurrencyHistoricRateRequest};

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
                        $query->whereDate('currency_rates.date', $request->date)
                    )->when( // Otherwise use tha maximum date of a currency we have
                        $request->missing('date'),
                        fn (JoinClause $query): JoinClause =>
                        $query->whereDate('currency_rates.date', CurrencyRate::max('date'))
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

    /**
     * Return historic rates for a given currency.
     *
     * @param \App\Http\Requests\CurrencyHistoricRateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function history(CurrencyHistoricRateRequest $request)
    {
        $rates = CurrencyRate::whereCurrencyId($request->id)->when(
            // If the user specifies the date range, constrain by that
            $request->has('date_from'),
            fn (Builder $query): Builder =>
            $query->whereDate('date', '<=', $request->date_from)
        )->when(
            $request->has('date_to'),
            fn (Builder $query): Builder =>
            $query->whereDate('date', '>=', $request->date_to)
        )->select('date', 'rate')->groupBy('date', 'rate');

        if ($request->has('page')) {
            $rates = $rates->paginate($request->page_size);
        } else {
            $rates = $rates->get();
        }

        // Note that the default rate is in Russian Ruble
        // Nominal matter
        if ($request->has('base_currency_id')) {
            $base_rate = CurrencyRate::whereCurrencyId($request->base_currency_id)
                ->value('rate');
            $nominal = Currency::whereId($request->id)->value('nominal');
            $rates = $rates->each(function ($rate) use ($base_rate, $nominal) {
                $new_rate = $rate->rate / ($base_rate * $nominal);
                $rate->rate = number_format($new_rate, 4);
                return $rate;
            });
        }

        return response([
            'rates' => $rates,
        ]);
    }
}
