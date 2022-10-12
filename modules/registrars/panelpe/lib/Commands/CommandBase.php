<?php

namespace WHMCS\Module\Registrar\Panelpe\Commands;

use Exception;
use Psr\Http\Message\ResponseInterface;
use WHMCS\Module\Registrar\Panelpe\APIClient;

abstract class CommandBase
{
    public APIClient $api;

    /**
     * @var array<string,mixed>
     */
    public array $params;

    private ?ResponseInterface $response = null;

    /**
     * @var array<string,mixed>|null
     */
    private ?array $result = null;

    /**
     * @param array<string, mixed> $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        $this->api = new APIClient($params);
        $this->params = $params;
    }


    /**
     * @return void
     * @throws Exception
     */
    abstract public function execute(array $clientOptions=[]): void;

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
        $this->result = json_decode((string)$response->getBody(), true);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return array<string,mixed>|null
     */
    public function getResult(): ?array
    {
        return $this->result;
    }

    /**
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return in_array($this->response->getStatusCode(), [201, 200]);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->result['error'] ?? [];
    }
}