<?php

namespace App\Services;

use App\Core\App;
use App\Providers\NpbProvider;

class CurrencyService
{
    /**
     * Get all currencies from the database.
     *
     * @return array The array of currencies from the 'exchange_rates' table.
     */
    public function getAllCurrencies()
    {
        return App::get('database')->selectAll('exchange_rates');
    }

    /**
     * Update the currencies in the database using the NpbProvider.
     */
    public function updateCurrencies()
    {
        $npbProvider = new NpbProvider();

        $data = $npbProvider->getResponse();

        $rates = $npbProvider->getRates($data);

        foreach ($rates as $rate) {
            App::get('database')->updateOrInsert(
                'exchange_rates',
                [
                    'name' => $rate['currency'],
                    'code' => $rate['code'],
                    'rate' => $rate['mid'],
                ],
                'code'
            );
        }
    }
}
