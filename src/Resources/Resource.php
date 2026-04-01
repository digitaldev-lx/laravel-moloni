<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Resources;

use DigitaldevLx\LaravelMoloni\Attributes\MoloniEndpoint;
use DigitaldevLx\LaravelMoloni\Http\MoloniClient;
use ReflectionMethod;
use RuntimeException;

abstract class Resource
{
    public function __construct(
        protected readonly MoloniClient $client,
    ) {}

    /**
     * Resolves the endpoint path from the MoloniEndpoint attribute
     * on the calling method, then dispatches the POST request.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    protected function call(string $method, array $params = []): array
    {
        $reflection = new ReflectionMethod($this, $method);
        $attributes = $reflection->getAttributes(MoloniEndpoint::class);

        if ($attributes === []) {
            throw new RuntimeException("Method {$method} has no MoloniEndpoint attribute.");
        }

        /** @var MoloniEndpoint $endpoint */
        $endpoint = $attributes[0]->newInstance();

        return $this->client->post($endpoint->path, $params);
    }
}
