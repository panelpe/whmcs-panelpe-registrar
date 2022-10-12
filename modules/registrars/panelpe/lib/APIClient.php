<?php

namespace WHMCS\Module\Registrar\Panelpe;

use GuzzleHttp\Client;
use WHMCS\Domain\Registrar\Domain;
use Exception;

class APIClient
{

    /**
     * @var array<string, mixed>
     */
    public array $params = [];

    /**
     * @var Client
     */
    public ?Client $client = null;

    /**
     * @param array<string, mixed> $params
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @param array<string,mixed> $options
     */
    public function getClient(array $options = []): Client
    {
        if (!$this->client) {
            $headers = [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $this->params['panelpe_api_key'],
            ];
            if (isset($GLOBALS["CONFIG"]["Version"]) and is_string($GLOBALS["CONFIG"]["Version"])) {
                $headers['User-Agent'] = 'WHMCS(' . $GLOBALS["CONFIG"]["Version"] . ')-Panelpe-Registrar(' . PANELPE_VERSION . ')';
            } else {
                $headers['User-Agent'] = 'WHMCS-Panelpe-Registrar(' . PANELPE_VERSION . ')';
            }
            if (isset($GLOBALS["CONFIG"]["SystemURL"]) and is_string($GLOBALS["CONFIG"]["SystemURL"])) {
                $headers['Referer'] = $GLOBALS["CONFIG"]["SystemURL"];
            }
            if (isset($GLOBALS["CONFIG"]["CompanyName"]) and is_string($GLOBALS["CONFIG"]["CompanyName"])) {
                $headers['X-WHMCS-COMPANY-NAME'] = $GLOBALS["CONFIG"]["CompanyName"];
            }
            $this->client = new Client(array_replace_recursive(
                [
                    'headers' => $headers,
                    'base_uri' => $this->params['panelpe_api_url'],
                    'http_errors' => false
                ], $options
            ));
        }
        return $this->client;
    }
}