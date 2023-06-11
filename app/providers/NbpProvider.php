<?php

namespace App\Providers;

use App\Core\App;

class NbpProvider
{
    private $curlHandle;
    private $url;
    private $tableType;

    const TABLE_TYPE_A = 'A';
    const TABLE_TYPE_B = 'B';

    /**
     * NbpProvider constructor.
     * Initializes a new instance of the NbpProvider class.
     * It sets up the cURL handle and retrieves the URL from the application configuration.
     * 
     * @param string $tableType The type of table for which the exchange rates are retrieved.
     */
    public function __construct($tableType)
    {
        $this->curlHandle = curl_init();
        $this->tableType = $tableType;
        $this->url = str_replace(':tableType', $this->tableType, App::get('config')['nbp_api']['exchange_rates_url']);
    }

    /**
     * Get the response from the API.
     *
     * @return array|false Returns the decoded JSON response or false on error.
     */
    public function getResponse()
    {
        curl_setopt($this->curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($this->curlHandle);

        if ($error = curl_error($this->curlHandle)) {
            throw new Exception("Connection error: $error");
        }

        curl_close($this->curlHandle);

        return json_decode($response, true);
    }

    /**
     * Get the exchange rates from the decoded API response.
     *
     * @param array $decodedData The decoded JSON response.
     * @return array The exchange rates.
     */
    public function getRates($decodedData)
    {
        return $decodedData[0]['rates'];
    }
}
