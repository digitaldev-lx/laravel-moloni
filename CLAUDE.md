# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

Laravel package for integrating with the Moloni invoicing API (Portuguese invoicing platform). Provides a fluent interface via a `Moloni` facade to manage companies, customers, products, invoices, and other resources. PHP 8.4+, Laravel 12/13.

## Commands

```bash
# Run all tests
vendor/bin/pest

# Run a single test file
vendor/bin/pest tests/Unit/Resources/CustomersTest.php

# Run a specific test by name
vendor/bin/pest --filter="test name here"

# Static analysis (PHPStan level 6)
vendor/bin/phpstan analyse

# Code style (Pint)
vendor/bin/pint          # fix
vendor/bin/pint --test   # check only
```

## Architecture

### Request Flow

`Moloni` (manager) -> `Resource` subclass -> `MoloniClient` -> Moloni REST API

- **`Moloni`** (`src/Moloni.php`) - Main entry point, registered as singleton. Lazily resolves and caches Resource instances. Accessed via the `Moloni` facade.
- **`MoloniClient`** (`src/Http/MoloniClient.php`) - Handles OAuth2 authentication (password grant + refresh token), token persistence via `MoloniToken` model, and all HTTP POST requests to `api.moloni.pt/v1/`. Auto-refreshes expired tokens on 401.
- **`Resource`** (`src/Resources/Resource.php`) - Abstract base. Subclasses use `#[MoloniEndpoint(path: '...')]` PHP attributes on methods to declare API paths. The `call()` method reads the attribute via reflection and delegates to `MoloniClient::post()`.

### Key Patterns

- **Endpoint declaration via attributes**: Every API method uses `#[MoloniEndpoint(path: 'resource/action')]`. When adding new methods, always annotate with this attribute - the `call()` method resolves the endpoint from it.
- **DTOs** (`src/DataTransferObjects/`): Readonly classes implementing `DataTransferObject` contract with `toArray()`. Resource methods accept both DTOs and raw arrays.
- **Events** (`src/Events/`): Dispatched after mutation operations (insert/update/delete/close/cancel). Each event receives the API response array.
- **Exceptions** (`src/Exceptions/`): `MoloniException` base, with `AuthenticationException`, `ValidationException`, `RateLimitException` subtypes. `ValidationException::fromResponse()` parses Moloni's field-level error format.
- **Enums** (`src/Enums/`): Typed enums for `DocumentType`, `ProductType`, `TaxType`, `AuthError`, `ValidationErrorCode`, etc.

### Document Resources

Document types (invoices, receipts, credit notes, etc.) live under `src/Resources/Documents/` and follow the same pattern as other resources.

### Testing

Uses Orchestra Testbench with a custom `TestCase` (`tests/TestCase.php`) that registers the service provider, sets test config values, and loads package migrations. Tests use Pest.

### Package Registration

`MoloniServiceProvider` registers `MoloniClient` and `Moloni` as singletons, publishes config (`moloni-config` tag) and migrations (`moloni-migrations` tag), and auto-loads migrations from `database/migrations/`.
