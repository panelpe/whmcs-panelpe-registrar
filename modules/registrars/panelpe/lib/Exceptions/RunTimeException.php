<?php

namespace WHMCS\Module\Registrar\Panelpe\Exceptions;

use WHMCS\Module\Registrar\Panelpe\IException;

class RunTimeException extends \RunTimeException implements IException
{
    public function __construct($message, $dataErrors = '')
    {
        $dataErrors = json_decode($dataErrors,true);
        $iErrors = "";

        if ($dataErrors && isset($dataErrors["errors"])) {
            foreach ($dataErrors["errors"] as $err) {
                $iErrors .= implode(" ", array_values($err)) . " ";
            }
        }

        parent::__construct($message . " - " . $iErrors);
    }
}

