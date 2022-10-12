<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class RegisterNameserver extends CommandBase
{
    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {

        $url = "api/domains/" . $this->params["domain"] . "/nameservers/register";
        $params = [
            "nameserver" => $this->params['nameserver'],
            "ip_address" => $this->params['ipaddress']
        ];

        $this->setResponse($this->api->getClient($clientOptions)->post($url, [
            'form_params' => $params,
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not register nameserver!',  $this->getResponse()->getBody());
        }
    }

}
