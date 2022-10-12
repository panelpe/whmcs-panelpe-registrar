<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class GetDomainInfo extends CommandBase
{
    private ?string $eppCode = null;

    public const DOMAIN_ACTIVE=1;
    public const DOMAIN_SUSPENDED=2;

    public array $domainInfo;
    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function execute(array $clientOptions=[]): void
    {
        $url="api/domains/".$this->params["domain"]."/info";
        $this->setResponse($this->api->getClient($clientOptions)->get($url));
        $result = $this->getResult();

        $this->domainInfo=$result["data"];

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not get epp code! ',  $this->getResponse()->getBody());
        }
    }

    public function getDomainInfo(): array
    {

        $expireDateTime= strtotime($this->domainInfo["expiration_date"]);
        $expireDate = date('Y-m-d',$expireDateTime);

        return [
            'expirydate' => $expireDate,
//            'active' => var_export($this->getStatus() == self::DOMAIN_ACTIVE, true),
//            'expired' => var_export($this->isExpired(), true)
        ];

    }

    private function getStatus(){
        if(empty($this->domainInfo["statuses"])){
            return self::DOMAIN_ACTIVE;
        }
        if (in_array("ok", $this->domainInfo["statuses"])){
            return self::DOMAIN_ACTIVE;
        }
        if (in_array("serverHold", $this->domainInfo["statuses"])){
            return self::DOMAIN_SUSPENDED;
        }
        return self::DOMAIN_ACTIVE;

    }

    private function isExpired(): bool
    {
        $expireDate = strtotime($this->domainInfo["expiration_date"]);
        $current = time();

        if($current>$expireDate && $this->getStatus() === self::DOMAIN_SUSPENDED ){
            return true;
        }
        return false;
    }
}
