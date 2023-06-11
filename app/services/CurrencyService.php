<?php

namespace App\Services;

use App\Core\App;
use App\Providers\NbpProvider;

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
     * Update the currencies in the database using the NbpProvider.
     *
     * @param string $tableType The type of table for which the exchange rates are retrieved.
     */
    public function updateCurrencies($tableType)
    {
        $nbpProvider = new NbpProvider($tableType);

        $data = $nbpProvider->getResponse();

        $rates = $nbpProvider->getRates($data);

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
     * Get all currency codes with details.
     *
     * @return array The array of currency codes with details.
     */
    public function getAllCodes()
    {
        $currencies = $this->getAllCurrencies();

        $codesArray = [];
        foreach ($currencies as $currency) {
            $codesArray[$currency->id] = $currency->code . ' - ' . $currency->name;
        }

        asort($codesArray);

        return $codesArray;
    }

    /**
     * Convert currency and save data to exchange_history table.
     *
     * @param array $data An array of exchange data.
     * @return float The converted amount.
     */
    public function exchange($data)
    {
        $convertedAmount = $this->convertCurrency($data['amount'], $data['source_currency_id'], $data['target_currency_id']);

        $this->updateExchangeHistory($data['amount'], $convertedAmount, $data['source_currency_id'], $data['target_currency_id']);

        return $convertedAmount;
    }

    /**
     * Get the currency rate by ID.
     *
     * @param int $id The ID of the currency rate.
     * @return float|null The currency rate or null if not found.
     */
    public function getCurrencyRateById($id)
    {
        $condition = "id = :id";
        $params = [':id' => $id];
        $currency = App::get('database')->selectWhere('exchange_rates', $condition, $params);

        if (!empty($currency)) {
            return (float) $currency[0]->rate;
        }

        return null;
    }

    /**
     * Get the currency code by ID.
     *
     * @param int $id The ID of the currency.
     * @return string|null The currency code or null if not found.
     */
    public function getCurrencyCodeById($id)
    {
        $condition = "id = :id";
        $params = [':id' => $id];
        $currency = App::get('database')->selectWhere('exchange_rates', $condition, $params);

        if (!empty($currency)) {
            return $currency[0]->code;
        }

        return null;
    }

    /**
     * Convert currency based on given data.
     *
     * @param float $amount The amount to be converted.
     * @param float $sourceRateId The ID of the source currency rate.
     * @param float $targetRateId The ID of the target currency rate.
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
     * @param float $convertedAmount The converted amount.
     * @param float $sourceRateId The ID of the source currency rate.
     * @param float $targetRateId The ID of the target currency rate.
     */
    public function updateExchangeHistory($amount, $convertedAmount, $sourceRateId, $targetRateId)
    {
        $sourceCode = $this->getCurrencyCodeById($sourceRateId);
        $targetCode = $this->getCurrencyCodeById($targetRateId);

        App::get('database')->insert('exchange_history', [
            'amount' => $amount,
            'source_currency' => $sourceCode,
            'target_currency' => $targetCode,
            'amount_after_conversion' => $convertedAmount,
        ]);
    }

    /**
     * Get exchange history from the 'exchange_history' table.
     *
     * @return array The array of exchange history.
     */
    public function getHistory()
    {
        return App::get('database')->selectAll('exchange_history', 'created_at DESC');
    }

    /**
     * Validate exchange data.
     *
     * @param array $data An array of exchange data.
     * @return array The validation result.
     */
    public function validateData($data)
    {
        if (!is_numeric($data['amount']) || $data['amount'] < 1 || $data['amount'] > 1000000000) {
            return [
                'is_valid' => false,
                'message' => 'The value should be numeric and between 1-1000000000.',
            ];
        }

        $sourceRate = $this->getCurrencyRateById($data['source_currency_id']);
        $targetRate = $this->getCurrencyRateById($data['target_currency_id']);
        if (is_null($sourceRate) || is_null($targetRate)) {
            return [
                'is_valid' => false,
                'message' => 'The source currency or target currency is incorrect.',
            ];
        }

        if ($data['source_currency_id'] === $data['target_currency_id']) {
            return [
                'is_valid' => false,
                'message' => 'You cannot convert the same currencies.',
            ];
        }

        return [
            'is_valid' => true,
            'message' => 'The currency exchange operation was successful.',
        ];
    }
}
