<?php

namespace WHMCS\Module\Registrar\Panelpe\Helpers;

use Exception;
use WHMCS\Module\Registrar\Panelpe\Commands\AddContact;

class Contact
{
    /**
     * @param array<string, mixed> $contactDetails
     * @param array<string, mixed> $params
     * @return string Contact Handle
     * @throws Exception
     */
    public static function getOrCreateContact(array $contactDetails, array $params, array $clientOptions=[]): ?string
    {

        $addContact =   new AddContact($params, $contactDetails);
        $addContact->execute($clientOptions);
        return $addContact->getContactHandle();
    }
}