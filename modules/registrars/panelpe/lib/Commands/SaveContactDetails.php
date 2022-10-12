<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use WHMCS\Database\Capsule;
use WHMCS\Module\Registrar\Panelpe\Exceptions\RunTimeException;

class SaveContactDetails extends CommandBase
{
    /**
     * @throws Exception
     */
    public function execute(array $clientOptions=[]): void
    {
        $url = "api/domains/" . $this->params["domain"] . "/contact";
        $contactDetails = $this->params['contactdetails'];

        $contacts = [
            "reg" => [
                "email" => $contactDetails['Registrant']['Email Address'],
                "telephone" =>  str_replace(".","",$contactDetails['Registrant']['Phone Number']),
                "fax" =>  str_replace(".","",$contactDetails['Registrant']['Fax Number']),
                "name" => $contactDetails['Registrant']['First Name'] . " " . $contactDetails['Registrant']['Last Name'],
                "address1" => $contactDetails['Registrant']['Address 1'],
                "address2" => $contactDetails['Registrant']['Address 2'],
                "city" => $contactDetails['Registrant']['City'],
                "province" => $contactDetails['Registrant']['State'],
                "zipcode" => $contactDetails['Registrant']['Postcode'],
                "country_code" => $contactDetails['Registrant']['Country'],
                "organization" => $contactDetails['Registrant']['Company Name'],
            ],
            "billing" => [
                "email" => $contactDetails['Billing']['Email Address'],
                "telephone" =>  str_replace(".","",$contactDetails['Billing']['Phone Number']),
                "fax" =>  str_replace(".","",$contactDetails['Billing']['Fax Number']),
                "name" => $contactDetails['Billing']['First Name'] . " " . $contactDetails['Registrant']['Last Name'],
                "address1" => $contactDetails['Billing']['Address 1'],
                "address2" => $contactDetails['Billing']['Address 2'],
                "city" => $contactDetails['Billing']['City'],
                "province" => $contactDetails['Billing']['State'],
                "zipcode" => $contactDetails['Billing']['Postcode'],
                "country_code" => $contactDetails['Billing']['Country'],
                "organization" => $contactDetails['Billing']['Company Name'],
            ],
            "admin" => [
                "email" => $contactDetails['Admin']['Email Address'],
                "telephone" =>  str_replace(".","",$contactDetails['Admin']['Phone Number']),
                "fax" =>  str_replace(".","",$contactDetails['Admin']['Fax Number']),
                "name" => $contactDetails['Admin']['First Name'] . " " . $contactDetails['Registrant']['Last Name'],
                "address1" => $contactDetails['Admin']['Address 1'],
                "address2" => $contactDetails['Admin']['Address 2'],
                "city" => $contactDetails['Admin']['City'],
                "province" => $contactDetails['Admin']['State'],
                "zipcode" => $contactDetails['Admin']['Postcode'],
                "country_code" => $contactDetails['Admin']['Country'],
                "organization" => $contactDetails['Admin']['Company Name'],
            ],
            "tech" => [
                "email" => $contactDetails['Technical']['Email Address'],
                "telephone" =>  str_replace(".","",$contactDetails['Technical']['Phone Number']),
                "fax" =>  str_replace(".","",$contactDetails['Technical']['Fax Number']),
                "name" => $contactDetails['Technical']['First Name'] . " " . $contactDetails['Registrant']['Last Name'],
                "address1" => $contactDetails['Technical']['Address 1'],
                "address2" => $contactDetails['Technical']['Address 2'],
                "city" => $contactDetails['Technical']['City'],
                "province" => $contactDetails['Technical']['State'],
                "zipcode" => $contactDetails['Technical']['Postcode'],
                "country_code" => $contactDetails['Technical']['Country'],
                "organization" => $contactDetails['Technical']['Company Name'],
            ]
        ];

        $this->setResponse($this->api->getClient($clientOptions)->post($url, [
            'form_params' => $contacts,
        ]));

        if (!$this->wasSuccessful()) {
            throw new RunTimeException('Panelpe: can not save contact!',  $this->getResponse()->getBody());
        }
    }


}
