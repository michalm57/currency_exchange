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

    /**
     * Getting all codes from exchange_rates table into array.
     * 
     * @return array $codesArray
     */
    public function getAllCodes()
    {
        $data = $this->getAllCurrencies();

        $codesArray = array();
        foreach ($data as $object) {
            $codesArray[$object->id] = $object->code . ' - ' . $object->name . ' - Value: ' . $object->rate . ' PLN';
        }

        asort($codesArray);

        return $codesArray;
    }

    /**
     * Exchange currencies and save data to exchange_history table.
     * 
     * @param array $data An array of exchange data.
     * @return flaot $data
     */
    public function exchange($data)
    {
        $convertedAmount = $this->convertCurrency($data['amount'], $data['source_currency_id'], $data['target_currency_id']);

        $this->updateExchangeHistory($data['amount'], $convertedAmount, $data['source_currency_id'], $data['target_currency_id']);

        return $convertedAmount;
    }

    /**
     * Get currency_exchanges.rate by id.
     * 
     * @param int $id
     * @return float|null
     */
    public function getCurrencyRateById($id)
    {
        $sourceRateCondition = "id = :id";
        $params = array(':id' => $id);
        $fetchedData = App::get('database')->selectWhere('exchange_rates', $sourceRateCondition, $params);

        if (!empty($fetchedData)) {
            return (float) $fetchedData[0]->rate;
        }

        return null;
    }

    /**
     * Get currency_exchanges.name by id.
     * 
     * @param int $id
     * @return string|null
     */
    public function getCurrencyCodeById($id)
    {
        $sourceRateCondition = "id = :id";
        $params = array(':id' => $id);
        $fetchedData = App::get('database')->selectWhere('exchange_rates', $sourceRateCondition, $params);

        if (!empty($fetchedData)) {
            return $fetchedData[0]->code;
        }

        return null;
    }

    /**
     * Convert currency based on given data
     *
     * @param float $amount The amount to be converted.
     * @param float $sourceRateId
     * @param $targetRateId
     * @return float The converted amount.
     */
    public function convertCurrency($amount, $sourceRateId, $targetRateId)
    {
        $sourceRate = $this->getCurrencyRateById($sourceRateId);
        $targetRate = $this->getCurrencyRateById($targetRateId);

        $convertedAmount = $amount * ($sourceRate / $targetRate);

        return $convertedAmount;
    }

    /**
     * Update exchange_history table.
     *
     * @param float $amount The amount to be converted.
     * @param float $sourceRate
     * @param float $targetRate
     */
    public function updateExchangeHistory($amount, $convertedAmount, $sourceRateId, $targetRateId)
    {
        $sourceName = $this->getCurrencyCodeById($sourceRateId);
        $targetName = $this->getCurrencyCodeById($targetRateId);

        App::get('database')->insert('exchange_history', [
            'amount' => $amount,
            'source_currency' => $sourceName,
            'target_currency' => $targetName,
            'amount_after_conversion' => $convertedAmount,
        ]);
    }

    /**
     * Get records from currency_table.
     *
     * @return array The array of exchange history from the 'exchange_history' table.
     */
    public function getHistory()
    {
        return App::get('database')->selectAll('exchange_history');
    }

    /**
     * Validate $data for currency echange.
     *
     * @return array $data
     * @return bool
     */
    public function validateData($data)
    {
        if (!is_numeric($data['amount']) || $data['amount'] < 1) {
            return [
                'is_valid' => false,
                'message' => 'The value should be numeric and greater than or equal to 1.'
            ];
        }

        $source = $this->getCurrencyRateById($data['source_currency_id']);
        $target = $this->getCurrencyRateById($data['target_currency_id']);
        if (is_null($source) || is_null($target)) {
            return [
                'is_valid' => false,
                'message' => 'The source currency or target currency are incorrect.'
            ];
        }

        if ($data['source_currency_id'] === $data['target_currency_id']) {
            return [
                'is_valid' => false,
                'message' => 'You cannot convert the same currencies.'
            ];
        }

        return [
            'is_valid' => true,
            'message' => 'The currency exchange operation was successful.'
        ];
    }
}
