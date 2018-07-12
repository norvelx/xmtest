<?php

namespace App\Services;

class RatesService extends ApiService
{

    public function getRates($companySymbol, array $params)
    {
        array_merge([
            'order' => 'asc'
        ], $params);

    	$response = $this->get(sprintf('datasets/WIKI/%s.csv', $companySymbol), $params);

        $lines = explode(PHP_EOL, $response);
        $data = [];
        foreach ($lines as $line) {
            $data[] = str_getcsv($line);
        }
        array_pop($data);
        array_shift($data);

        return $data;
    }
}