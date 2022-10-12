<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class GetContactDetails extends CommandBase
{
    private array $contacts;

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function execute(array $clientOptions=[]): void
    {
        $url = "api/domains/" . $this->params["domain"] . "/contact";
        $this->setResponse($this->api->getClient($clientOptions)->get($url));

        $result = $this->getResult();
        $this->contacts = $result["data"];


        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not get contact! ',  $this->getResponse()->getBody());
        }
    }

    public function getContacts(): array
    {

        $registrant = array_values(array_filter($this->contacts, function ($k) {
            return $k['type'] == 'reg';
        }));

        $billing = array_values(array_filter($this->contacts, function ($k) {
            return $k['type'] == 'billing';
        }));

        $admin = array_values(array_filter($this->contacts, function ($k) {
            return $k['type'] == 'admin';
        }));
        $tech = array_values(array_filter($this->contacts, function ($k) {
            return $k['type'] == 'tech';
        }));


        return [
            "Registrant" => [
                'First Name' => $registrant[0]["name"],
                'Last Name' => '',
                'Company Name' => $registrant[0]["organization"] ?? '',
                'Email Address' => $registrant[0]["email"],
                'Address 1' => $registrant[0]["address1"],
                'Address 2' => $registrant[0]["address2"] ?? '',
                'City' => $registrant[0]["city"] ?? '',
                'State' => $registrant[0]["province"] ?? '',
                'Postcode' => $registrant[0]["zipcode"] ?? '',
                'Country' => $registrant[0]["country_code"],
                'Phone Number' => $registrant[0]["telephone"] ?? '',
                'Fax Number' => $registrant[0]["fax"] ?? '',
            ],
            "Technical" => [
                'First Name' => $tech[0]["name"],
                'Last Name' => '',
                'Company Name' => $tech[0]["organization"] ?? '',
                'Email Address' => $tech[0]["email"],
                'Address 1' => $tech[0]["address1"],
                'Address 2' => $tech[0]["address2"] ?? '',
                'City' => $tech[0]["city"] ?? '',
                'State' => $tech[0]["province"] ?? '',
                'Postcode' => $tech[0]["zipcode"] ?? '',
                'Country' => $tech[0]["country_code"],
                'Phone Number' => $tech[0]["telephone"] ?? '',
                'Fax Number' => $tech[0]["fax"] ?? '',
            ],
            "Billing" => [
                'First Name' => $billing[0]["name"],
                'Last Name' => '',
                'Company Name' => $billing[0]["organization"] ?? '',
                'Email Address' => $billing[0]["email"],
                'Address 1' => $billing[0]["address1"],
                'Address 2' => $billing[0]["address2"] ?? '',
                'City' => $billing[0]["city"] ?? '',
                'State' => $billing[0]["province"] ?? '',
                'Postcode' => $billing[0]["zipcode"] ?? '',
                'Country' => $billing[0]["country_code"],
                'Phone Number' => $billing[0]["telephone"] ?? '',
                'Fax Number' => $billing[0]["fax"] ?? '',
            ],
            "Admin" => [
                'First Name' => $admin[0]["name"],
                'Last Name' => '',
                'Company Name' => $admin[0]["organization"] ?? '',
                'Email Address' => $admin[0]["email"],
                'Address 1' => $admin[0]["address1"],
                'Address 2' => $admin[0]["address2"] ?? '',
                'City' => $admin[0]["city"] ?? '',
                'State' => $admin[0]["province"] ?? '',
                'Postcode' => $admin[0]["zipcode"] ?? '',
                'Country' => $admin[0]["country_code"],
                'Phone Number' => $admin[0]["telephone"] ?? '',
                'Fax Number' => $admin[0]["fax"] ?? '',
            ]
        ];

    }
}
