<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Exceptions;

use DigitaldevLx\LaravelMoloni\Enums\ValidationErrorCode;

final class ValidationException extends MoloniException
{
    /**
     * @param  array<int, array{code: int, field: string, description?: string}>  $errors
     */
    public function __construct(
        string $message,
        public readonly array $errors = [],
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param  array<string, mixed>  $response
     */
    public static function fromResponse(array $response): self
    {
        $parsedErrors = [];

        foreach ($response as $key => $value) {
            if (is_array($value) && isset($value[0]) && is_int($value[0])) {
                $errorCode = ValidationErrorCode::tryFrom($value[0]);
                $parsedErrors[] = [
                    'code' => $value[0],
                    'field' => $key,
                    'description' => $errorCode?->label() ?? "Error code {$value[0]}",
                ];
            }
        }

        $fieldNames = array_column($parsedErrors, 'field');
        $message = $parsedErrors !== []
            ? 'Moloni validation error on fields: '.implode(', ', $fieldNames)
            : 'Moloni validation error';

        return new self(
            message: $message,
            errors: $parsedErrors,
        );
    }

    /**
     * @return array<string, string>
     */
    public function getFieldErrors(): array
    {
        $result = [];

        foreach ($this->errors as $error) {
            $result[$error['field']] = $error['description'] ?? "Error code {$error['code']}";
        }

        return $result;
    }

    public function hasFieldError(string $field): bool
    {
        foreach ($this->errors as $error) {
            if ($error['field'] === $field) {
                return true;
            }
        }

        return false;
    }
}
