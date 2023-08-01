<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Helpers\Contact;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class RegisterDomain extends CommandBase
{
    /**
     * @throws Exception|GuzzleException
     */
    public function execute(array $clientOptions=[]): void
    {

        $clientId = Contact::getOrCreateContact($this->params, $this->params, $clientOptions);

        if (!$clientId) {
            throw new RunTimeException('can not create contact on panelpe!');
        }
        $dnses = [];
        for ($i = 1; $i <= 5; $i++) {
            if (empty($this->params["ns$i"])) {
                continue;
            }
            $dnses[] = $this->params["ns$i"];
        }

        $sld = $this->params['sld'];
        $tld = $this->params['tld'];

        $params =  [
//            "domain" => $this->params['domain'],
            "domain" =>  $sld . '.' . $tld,
            "client_id" => $clientId,
            "regperiod" => $this->params['regperiod'],
            "nameservers" => $dnses,
        ];


        $this->setResponse($this->api->getClient($clientOptions)->post('api/order/domains/register', [
            'form_params' =>$params,
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not register domain!',  $this->getResponse()->getBody());
        }
    }
}
