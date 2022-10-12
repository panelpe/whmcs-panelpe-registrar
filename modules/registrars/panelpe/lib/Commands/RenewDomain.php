<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class RenewDomain extends CommandBase
{
    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {
        $params =  [
            "domain" => $this->params['domain'],
            "regperiod" => $this->params['regperiod'],
        ];

        $this->setResponse($this->api->getClient($clientOptions)->post('api/order/domains/renew', [
            'form_params' =>$params,
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not renew domain!' , $this->getResponse()->getBody());
        }
    }
}
