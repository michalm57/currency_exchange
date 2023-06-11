<?php

require_once 'core/App.php';
require 'vendor/autoload.php';
require 'core/bootstrap.php';

use App\Services\CurrencyService;
use App\Providers\NbpProvider;

$currencyController = new CurrencyService();

$currencyController->updateCurrencies(NbpProvider::TABLE_TYPE_A);
$currencyController->updateCurrencies(NbpProvider::TABLE_TYPE_B);
?>