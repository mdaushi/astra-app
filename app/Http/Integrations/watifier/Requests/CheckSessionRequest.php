<?php

namespace App\Http\Integrations\watifier\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class CheckSessionRequest extends Request
{
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::GET;

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/instance/info?key=' . $this->session;
    }

    public function __construct(
        protected string $session,
    ) { }
}
