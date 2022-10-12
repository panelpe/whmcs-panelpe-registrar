<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class GetStatusDomain extends CommandBase
{
    private array $status = [];
    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {
        $url="api/domains/".$this->params["domain"]."/status";
        $this->setResponse($this->api->getClient($clientOptions)->get($url));
        $result = $this->getResult();
        $this->status=$result["data"];

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not get domain status!',  $this->getResponse()->getBody());
        }
    }

    public function isLocked(): bool
    {
        if(in_array("clientTransferProhibited",$this->status)){
            return true;
        }
        return false;
    }
}
