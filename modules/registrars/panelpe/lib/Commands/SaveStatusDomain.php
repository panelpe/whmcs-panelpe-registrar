<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class SaveStatusDomain extends CommandBase
{

    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {
        $url = "api/domains/" . $this->params["domain"] . "/status";
        $locked=[];
        if($this->params["lockenabled"]==="locked"){
            $locked=[
                "clientTransferProhibited"
            ];
        }
        $params = [
            "lockstatus" => $locked
        ];

        $this->setResponse($this->api->getClient($clientOptions)->post($url, [
            'json' => $params,
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not save status!',  $this->getResponse()->getBody());
        }
    }

}

