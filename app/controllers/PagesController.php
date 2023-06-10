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
        $currencies = $this->currencyService->getAllCurrencies();
        
        return view('currencies', compact('currencies'));
    }

    public function updateCurrencies()
    {
        return $this->currencyService->updateCurrencies();
    }
}
