<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class ModifyNameserver extends CommandBase
{

    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {
        $url = "api/domains/" . $this->params["domain"] . "/nameservers/modify";

        $params = [
            "nameserver" => $this->params['nameserver'],
            "new_ip_address" => $this->params['newipaddress'],
            "current_ip_address" => $this->params['currentipaddress']
        ];

        $this->setResponse($this->api->getClient($clientOptions)->post($url, [
            'form_params' => $params,
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not modify nameserver!',  $this->getResponse()->getBody());
        }
    }

}
