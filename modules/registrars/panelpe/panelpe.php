<?php
/**
 * WHMCS Panelpe Registrar Module
 *
 * @author Panelpe Development Team <dev@panelpe.com>
 *
 *
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Carbon;
use WHMCS\Database\Capsule;
use WHMCS\Domain\Registrar\Domain;
use WHMCS\Domains\DomainLookup\ResultsList;
use WHMCS\Domains\DomainLookup\SearchResult;
use WHMCS\Exception\Module\InvalidConfiguration;
use WHMCS\Module\Registrar\Panelpe\Commands\AddDomain;
use WHMCS\Module\Registrar\Panelpe\Commands\DeleteNameserver;
use WHMCS\Module\Registrar\Panelpe\Commands\GetContactDetails;
use WHMCS\Module\Registrar\Panelpe\Commands\GetDomainInfo;
use WHMCS\Module\Registrar\Panelpe\Commands\SaveContactDetails;
use WHMCS\Module\Registrar\Panelpe\Commands\GetEppCode;
use WHMCS\Module\Registrar\Panelpe\Commands\GetNameServers;
use WHMCS\Module\Registrar\Panelpe\Commands\GetStatusDomain;
use WHMCS\Module\Registrar\Panelpe\Commands\SaveStatusDomain;
use WHMCS\Module\Registrar\Panelpe\Commands\RegisterDomain;
use WHMCS\Module\Registrar\Panelpe\Commands\RegisterNameserver;
use WHMCS\Module\Registrar\Panelpe\Commands\ModifyNameserver;
use WHMCS\Module\Registrar\Panelpe\Commands\RenewDomain;
use WHMCS\Module\Registrar\Panelpe\Commands\SaveNameServers;
use WHMCS\Module\Registrar\Panelpe\Commands\TransferDomain;
use WHMCS\Module\Registrar\Panelpe\Http;

const PANELPE_VERSION = "1.0.0";

require_once __DIR__ . '/vendor/autoload.php';

/**
 * @param array<string, mixed> $params
 * @return array<string, mixed>
 */
function panelpe_getConfigArray(array $params): array
{

    $msgRegister = "Panel.pe Domain Registrar";

    return [
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => 'PanelPe (v' . PANELPE_VERSION . ')',
        ],
        'Description' => [
            'Type' => 'System',
            'Value' => $msgRegister,
        ],
        'panelpe_api_url' => [
            'FriendlyName' => 'Panelpe API Url',
            'Type' => 'text',
            'Size' => '200',
            'Default' => 'https://app.panel.pe/',
            'Description' => 'Enter your panelpe API URL!',
        ],
        'panelpe_api_key' => [
            'FriendlyName' => 'Panelpe API Key',
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your panelpe API key here!',
        ],
    ];
}

function panelpe_config_validate($params): void
{
    if (!isset($params['panelpe_api_key']) or !$params['panelpe_api_key']) {
        throw new InvalidConfiguration('You Should Give an API Key!');
    }
    if (!is_string($params['panelpe_api_key'])) {
        throw new InvalidConfiguration('The API Key Should Be String!');
    }
}

/**
 * @throws Exception
 */
function panelpe_RegisterDomain(array $params): array
{
    $register = new RegisterDomain($params);
    try {
        $register->execute();
        return ['success' => true];
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}


/**
 * @throws Exception
 */
function panelpe_GetNameservers(array $params): array
{
    $domain = new GetNameServers($params);
    try {
        $domain->execute();
        return $domain->getNameservers();
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}


/**
 * @throws Exception
 */
function panelpe_GetEPPCode($params)
{
    $register = new GetEppCode($params);
    try {
        $register->execute();
        return $register->getEppCode();
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}

/**
 * @throws Exception
 */
function panelpe_SaveNameservers($params)
{

    $register = new SaveNameservers($params);
    try {
        $register->execute();
        return ['success' => true];
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}

function panelpe_RenewDomain(array $params): array
{
    try {
        $renew = new RenewDomain($params);
        $renew->execute();
        return ['success' => true];
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}


function panelpe_GetRegistrarLock(array $params)
{
    try {
        $command = new GetStatusDomain($params);
        $command->execute();
        return $command->isLocked() ? "locked" : "unlocked";
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}

function panelpe_SaveRegistrarLock($params)
{
    try {
        $renew = new SaveStatusDomain($params);
        $renew->execute();
        return ['success' => true];
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}


function panelpe_TransferDomain($params)
{
    try {
        $command = new TransferDomain($params);
        $command->execute();
        return ['success' => true];
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}


function panelpe_RegisterNameserver($params)
{
    try {
        $command = new RegisterNameserver($params);
        $command->execute();
        return ['success' => true];
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}

function panelpe_ModifyNameserver($params)
{
    try {
        $command = new ModifyNameserver($params);
        $command->execute();
        return ['success' => true];
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];

    }
}

function panelpe_DeleteNameserver($params)
{
    try {
        $command = new DeleteNameserver($params);
        $command->execute();
        return ['success' => true];
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];

    }
}

function panelpe_GetContactDetails($params)
{
    try {
        $command = new GetContactDetails($params);
        $command->execute();
        return $command->getContacts();
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}

function panelpe_SaveContactDetails($params)
{
    try {
        $command = new SaveContactDetails($params);
        $command->execute();
        return ['success' => true];
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}

function panelpe_Sync($params)
{
    try {
        $info = new GetDomainInfo($params);
        $info->execute();
        return $info->getDomainInfo();
    } catch (Exception $ex) {
        return ['error' => $ex->getMessage()];
    }
}