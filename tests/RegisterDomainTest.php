<?php

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use WHMCS\Module\Registrar\Panelpe\Commands\RegisterDomain;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class RegisterDomainTest extends TestCase
{

    private array $params;

    protected function setUp(): void
    {
        $this->params = [
            "panelpe_api_key" => getenv("PANELPE_APIKEY"),
            "firstname" => "Test name",
            "lastname" => "Test name",
            "fullname" => "Test name",
            "companyname" => "Test name",
            "email" => "test@aqphost.com",
            "address1" => "test address",
            "city" => "city test",
            "state" => "state test",
            "fullstate" => "test",
            "countrycode" => "PE",
            "phonenumber" => "123456",
            "phonecc" => "1231654",
            "fullphonenumber" => "+51.2343434",
            "postcode" => "91474",
            "domain" => "testpe.pe",
            "regperiod" => "1",
            "ns1" => "ns1.aqphost.com",
            "ns2" => "ns2.aqphost.com"
        ];
    }

    /**
     * @throws Exception|GuzzleException
     */
    public function testRegisterUnExistingClient()
    {


        $mock = new MockHandler([
            new Response(404), // if client not exists
            new Response(201, [], '{"data": {"id": 1, "name": "test name"}}'), // add client
            new Response(201, [], ''), // add domain
            //new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $handlerStack = HandlerStack::create($mock);

        $register = new RegisterDomain($this->params);
        $register->execute(['handler' => $handlerStack]);

        $this->assertEquals(201, $register->getResponse()->getStatusCode());

    }

    /**
     * @throws Exception|GuzzleException
     */
    public function testRegisterExistingClient()
    {

        $mock = new MockHandler([
            new Response(200, [], '{"data": {"id": 1, "name": "test name"}}'), // existing client
            new Response(201, [], '') // add domain
        ]);

        $handlerStack = HandlerStack::create($mock);

        $register = new RegisterDomain($this->params);
        $register->execute(['handler' => $handlerStack]);

        $this->assertEquals(201, $register->getResponse()->getStatusCode());

    }
}