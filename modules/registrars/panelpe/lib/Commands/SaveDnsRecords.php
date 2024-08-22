<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class SaveDnsRecords extends CommandBase
{
    /**
     * @throws Exception
     */
    public function execute(array $clientOptions = []): void
    {
        $url = "api/domains/".$this->params["domain"]."/replace-records";
        $records = $this->params['dnsrecords'];

        $this->setResponse($this->api->getClient($clientOptions)->post($url, [
            'form_params' => ["records" => $records],
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not save records!', $this->getResponse()->getBody());
        }
    }


}
