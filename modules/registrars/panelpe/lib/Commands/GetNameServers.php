<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class GetNameServers extends CommandBase
{
    private array $nameServers = [];
    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {

        $url="api/domains/".$this->params["domain"]."/nameservers";
        $this->setResponse($this->api->getClient($clientOptions)->get($url));

        $result = $this->getResult();

        for ($i = 1; $i <= count($result["data"]); $i++) {
            $this->nameServers["ns".$i]=$result["data"][$i-1];
        }

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not get nameserver!', $this->getResponse()->getBody());
        }
    }

    public function getNameservers(): array
    {
        return $this->nameServers;
    }
}
