<?php

namespace App\Http\Integrations\watifier\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Body\HasJsonBody;

class SendMessageRequest extends Request implements HasBody
{
    use HasJsonBody;
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    protected string $key;
    protected array $payload;

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/message/text';
    }

    public function __construct(string $key, array $payload) { 
        $this->key = $key;
        $this->payload = $payload;
    }

    protected function defaultQuery(): array
    {
        return [
            'key' => $this->key,
        ];
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
