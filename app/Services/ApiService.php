<?php

namespace App\Services;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use App\Exceptions\ApiException;

class ApiService
{
    protected $_errors = [
        500,
        405,
        404
    ];

    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => 'https://www.quandl.com/api/v3/',
            'connect_timeout' => 150,
            'timeout' => 150,
            'cookies' => false,
            'stream' => false,
            'http_errors' => false,
            'verify' => !env('DISABLE_SSL_VERIFIER', false),
            'delay' => 0,
        ]);
    }

    protected function checkResponse(Response $response)
    {
        $status = $response->getStatusCode();

        if (in_array($status, $this->_errors)) {
            throw new ApiException($status);
        }

        return $response->getBody()->getContents();
    }

    protected function client()
    {
        return $this->_client;
    }


    protected function get($url, $params = [])
    {

        $response = $this->_client->get($url, [
            'query' => $params
        ]);

        return $this->checkResponse($response);

    }

}