<?php

namespace App\Http\Integrations\watifier;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class WatifierConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return env('WATIFIER_API_URL');
    }

    // public function __construct(
    //     protected string $apiToken,
    // ){
    //    $this->withTokenAuth($this->apiToken); 
    // }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Default HTTP client options
     *
     * @return string[]
     */
    protected function defaultConfig(): array
    {
        return [];
    }
}
