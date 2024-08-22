<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class GetDnsRecords extends CommandBase
{
    private array $records;

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function execute(array $clientOptions = []): void
    {
        $url = "api/domains/".$this->params["domain"]."/records";
        $this->setResponse($this->api->getClient($clientOptions)->get($url));

        $result = $this->getResult();
        $this->records = $result["data"];


        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not get records! ', $this->getResponse()->getBody());
        }
    }

    public function getRecords(): array
    {

        return $this->records;

    }
}
