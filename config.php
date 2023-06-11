<?php

return [
    'database' => [
        'name' => 'currency_exchange',
        'username' => 'currency_exchange_user',
        'password' => 'password',
        'connection' => 'mysql:host=db',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        ],
    ],
    'npb_api' => [
        'echange_rates_url' => 'http://api.nbp.pl/api/exchangerates/tables/:tableType/',
    ],
];
