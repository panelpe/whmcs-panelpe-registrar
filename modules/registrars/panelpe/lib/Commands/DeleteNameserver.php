<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class DeleteNameserver extends CommandBase
{

    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {

        $url = "api/domains/" . $this->params["domain"] . "/nameservers/delete";

        $params = [
            "nameserver" => $this->params['nameserver'],
        ];

        $this->setResponse($this->api->getClient($clientOptions)->post($url, [
            'form_params' => $params,
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not delete nameserver! ', $this->getResponse()->getBody());
        }
    }

}
