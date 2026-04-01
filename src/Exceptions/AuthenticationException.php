<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Exceptions;

use DigitaldevLx\LaravelMoloni\Enums\AuthError;

final class AuthenticationException extends MoloniException
{
    public function __construct(
        string $message,
        public readonly ?AuthError $authError = null,
        public readonly ?string $errorDescription = null,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param  array{error?: string, error_description?: string}  $response
     */
    public static function fromResponse(array $response): self
    {
        $authError = isset($response['error'])
            ? AuthError::tryFrom($response['error'])
            : null;

        $description = $response['error_description'] ?? null;
        $errorKey = $response['error'] ?? 'unknown';

        $message = $authError !== null
            ? "Moloni authentication error [{$errorKey}]: {$authError->label()}"
            : "Moloni authentication error: {$errorKey}";

        if ($description !== null) {
            $message .= " - {$description}";
        }

        return new self(
            message: $message,
            authError: $authError,
            errorDescription: $description,
            code: 400,
        );
    }
}
