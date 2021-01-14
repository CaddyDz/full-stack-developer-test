<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $xml = simplexml_load_file(config('currency.url'));

        $date = Carbon::createFromFormat('d.m.Y', (string)$xml->attributes()->Date)
            ->startOfDay();

        for ($i = 0; $i < $xml->Valute->count(); $i++) {
            $valute = $xml->Valute[$i];

            $currency = Currency::updateOrCreate(
                [
                    'char_code' => (string)$valute->CharCode,
                    'nominal' => (string)$valute->Nominal,
                ],
                [
                    'num_code' => (string)$valute->NumCode,
                    'name' => (string)$valute->Name,
                ]
            );

            $rate = str_replace(',', '.', (string)$valute->Value);

            $currency->rates()->updateOrCreate(compact('date'), compact('rate'));
        }

        $this->info('Currencies imported.');
    }
}
