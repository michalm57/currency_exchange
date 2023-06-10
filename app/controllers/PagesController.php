<?php

namespace App\Controllers;

use App\Services\CurrencyService;

class PagesController
{
    /**
     * @var CurrencyService $currencyService
     */
    private $currencyService;

    /**
     * PagesController constructor.
     * Initializes a new instance of the PagesController class.
     */
    public function __construct()
    {
        $this->currencyService = new CurrencyService;
    }

    /**
     * Home Page Action
     *
     * This function is responsible for handling the home page request.
     *
     * @return string The rendered view for the home page.
     */
    public function home()
    {

        return view('index');
    }

    /**
     * Currencies Page Action
     *
     * This function is responsible for handling the currencies page request.
     * It retrieves all the currencies from the CurrencyService and passes them to the view.
     *
     * @return string The rendered view for the currencies page.
     */
    public function currencies()
    {
        $this->currencyService->updateCurrencies();

        $currencies = $this->currencyService->getAllCurrencies();

        return view('currencies', compact('currencies'));
    }

    /**
     * Echchange Page Action
     *
     * This function is responsible for handling the echange page request.
     *
     * @return string The rendered view for the exchange page.
     */
    public function exchange()
    {
        $codesValues = $this->currencyService->getAllCodes();

        return view('exchange', compact('codesValues'));
    }

    /**
     * Calculate exchange
     *
     * This function is responsible for calculatiing exchnages.
     *
     */
    public function calculateExchange()
    {
        $data = [
            'amount' => $_POST['amount'],
            'source_currency_id' => $_POST['source_currency_id'],
            'target_currency_id' => $_POST['target_currency_id'],
        ];

        //TODO
        // $this->currencyService->validate($data);

        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'The currency exchange operation was successful.'
        ];

        $this->currencyService->exchange($data);

        return $this->history(true);
    }

    /**
     * Exchange History
     *
     * This function is responsible for calculatiing exchnages.
     *
     */
    public function history($exchangeRedirect = false)
    {
        $historyRecords = $this->currencyService->getHistory();

        return view('history', [
            'historyRecords' => $historyRecords,
            'exchangeRedirect' => $exchangeRedirect
        ]);
    }
}
