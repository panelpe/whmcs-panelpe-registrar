<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Helpers\Contact;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class TransferDomain extends CommandBase
{

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function execute(array $clientOptions=[]): void
    {
        $clientId = Contact::getOrCreateContact($this->params, $this->params);

        if (!$clientId) {
            throw new RunTimeException('can not create contact on panelpe!', '');
        }
        $dnses = [];
        for ($i = 1; $i <= 5; $i++) {
            if (empty($this->params["ns$i"])) {
                continue;
            }
            $dnses[] = $this->params["ns$i"];
        }

        $params =  [
            "domain" => $this->params['sld'].".".$this->params['tld'],
            "client_id" => $clientId,
            "regperiod" => $this->params['regperiod'],
            "nameservers" => $dnses,
            "eppcode" => $this->params['eppcode']
        ];

        $this->setResponse($this->api->getClient($clientOptions)->post('api/order/domains/transfer', [
            'form_params' =>$params,
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not transfer domain!',  $this->getResponse()->getBody());
        }
    }
}
