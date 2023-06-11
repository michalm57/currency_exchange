<?php

namespace App\Providers;

use App\Core\App;

class NpbProvider
{
    private $curlHandle;
    private $url;

    const TABLE_TYPE_A = 'A';
    const TABLE_TYPE_B = 'B';

    /**
     * NpbProvider constructor.
     *
     * Initializes a new instance of the NpbProvider class.
     * It sets up the cURL handle and retrieves the URL from the application configuration.
     */
    public function __construct()
    {
        $this->curlHandle = curl_init();
        $this->url = str_replace(':tableType', self::TABLE_TYPE_A, App::get('config')['npb_api']['echange_rates_url']);    
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

        if ($e = curl_error($this->curlHandle)) {
            echo "Problem with connection!";
        } else {
            $decodedData = json_decode($response, true);
            return $decodedData;
        }

        curl_close($this->curlHandle);
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
