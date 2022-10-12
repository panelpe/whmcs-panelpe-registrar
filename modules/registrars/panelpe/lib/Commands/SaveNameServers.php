<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class SaveNameServers extends CommandBase
{
    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {
        $url="api/domains/".$this->params["domain"]."/nameservers";

        $dnses = [];
        for ($i = 1; $i <= 5; $i++) {
            if (empty($this->params["ns$i"])) {
                continue;
            }
            $dnses["ns$i"] = $this->params["ns$i"];
        }

        $this->setResponse($this->api->getClient($clientOptions)->post($url,[
            'form_params' =>$dnses,
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not save nameserver! ' , $this->getResponse()->getBody());
        }
    }


}
