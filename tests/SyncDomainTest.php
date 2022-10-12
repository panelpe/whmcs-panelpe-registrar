<?php

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use WHMCS\Module\Registrar\Panelpe\Commands\GetDomainInfo;

class SyncDomainTest extends TestCase
{

    /**
     * @throws Exception|GuzzleException
     */
    public function testGetDomainInfo()
    {

        $infoParams = [
            "panelpe_api_key" => getenv("PANELPE_APIKEY"),
            "domain" => "test1p2.pe",
            ];


        $info = new GetDomainInfo($infoParams);
        $info->execute();


        $this->assertEquals(200, $info->getResponse()->getStatusCode());

    }


}