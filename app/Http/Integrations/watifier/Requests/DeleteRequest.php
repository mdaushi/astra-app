<?php

namespace App\Http\Integrations\watifier\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteRequest extends Request
{
    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::DELETE;

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/instance/delete?key=' . $this->session;
    }

    public function __construct(
        protected string $session,
    ) { }
}
