<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;
use WHMCS\Module\Registrar\Panelpe\Helpers\Generators;

class AddContact extends CommandBase
{

    /**
     * @var array<string,string>
     */
    private array $contact;

    private array $client;

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed> $contact
     * @throws Exception
     */
    public function __construct(array $params, array $contact)
    {
        parent::__construct($params);
        $this->contact = $contact;
    }

    /**
     * @throws Exception|GuzzleException
     */
    public function execute(array $clientOptions=[]): void
    {
        $urlCheck = "/api/clients/" . $this->contact['email'] . "/exists";

        $clientCheck = $this->api->getClient($clientOptions)->get($urlCheck);

        if ($clientCheck->getStatusCode() === 404) {
            $client = false;
        } else {
            $client = json_decode($clientCheck->getBody(), true)["data"];
        }

        if (!$client) {

            $contact = [];
            $contact['first_name'] = $this->contact['firstname'];
            $contact['last_name'] = $this->contact['lastname'];
            $contact['email'] = $this->contact['email'];
            $contact['city'] = $this->contact['city'];
            $contact['country_code'] = $this->contact['countrycode'] ?? $this->contact['country'] ?? '';
            $contact['state'] = $this->contact['fullstate'] ?? $this->contact['state'] ?? $this->contact['statecode'] ?? '';
            $contact['address1'] = ($this->contact['address1'] ?? '') . ' ' . ($this->contact['address2'] ?? '') . ' ' . ($this->contact['address'] ?? '');
            $contact['zipcode'] = $this->contact['postcode'] ?? $this->contact['zip'] ?? '';
            $contact['phone'] = $this->contact['phonenumberformatted'] ?? $this->contact['fullphonenumber'] ?? $this->contact['phone'];
            $contact['phone'] = str_replace(".", "", $contact["phone"]);

            $contact['password'] = Generators::Password();
            $contact['password_confirmation'] = $contact['password'];

            $this->setResponse($this->api->getClient($clientOptions)->post('api/clients', [
                'form_params' => $contact,
            ]));

            $result = $this->getResult();

            if (!$this->wasSuccessful()) {
                 throw new RunTimeException('Panelpe: can not add contact in panelpe! ', $this->getResponse()->getBody());
            }

            $client = $result["data"];
        }

        $this->client = $client;

    }

    public function getContactHandle(): ?string
    {
        return $this->client["id"];
    }
}