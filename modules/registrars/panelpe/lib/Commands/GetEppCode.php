<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class GetEppCode extends CommandBase
{
    private ?string $eppCode = null;
    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {
        $url="api/domains/".$this->params["domain"]."/eppcode";
        $this->setResponse($this->api->getClient($clientOptions)->get($url));
        $result = $this->getResult();

        $this->eppCode = $result["data"]["authorisation_code"];

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not get epp code! ',  $this->getResponse()->getBody());
        }
    }

    public function getEppCode(): array
    {
        return [
            'eppcode' => $this->eppCode
        ];
    }
}
