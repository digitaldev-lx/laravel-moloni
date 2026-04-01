# Laravel Moloni

[![Packagist Version](https://img.shields.io/packagist/v/digitaldev-lx/laravel-moloni)](https://packagist.org/packages/digitaldev-lx/laravel-moloni)
[![Tests](https://github.com/digitaldev-lx/laravel-moloni/actions/workflows/tests.yml/badge.svg)](https://github.com/digitaldev-lx/laravel-moloni/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%206-brightgreen)](https://phpstan.org/)
[![License](https://img.shields.io/packagist/l/digitaldev-lx/laravel-moloni)](LICENSE)

A Laravel package for integrating with the [Moloni invoicing API](https://www.moloni.pt/dev/). Provides a clean, fluent interface to manage companies, customers, products, invoices, and all other Moloni resources with full type safety through DTOs and enums.

**Official page:** [digitaldev.pt/packages/laravel-moloni](https://digitaldev.pt/packages/laravel-moloni)

## Requirements

- PHP 8.4+
- Laravel 12 or 13

## Installation

Install the package via Composer:

```bash
composer require digitaldev-lx/laravel-moloni
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=moloni-config
```

Run the migrations (required for OAuth token storage):

```bash
php artisan migrate
```

## Configuration

Add the following environment variables to your `.env` file:

```dotenv
MOLONI_CLIENT_ID=your-client-id
MOLONI_CLIENT_SECRET=your-client-secret
MOLONI_USERNAME=your-username
MOLONI_PASSWORD=your-password
MOLONI_COMPANY_ID=your-company-id
```

You can obtain your API credentials from the [Moloni Developer Portal](https://www.moloni.pt/dev/).

## Usage

### Using the Facade

All API interactions are available through the `Moloni` facade:

```php
use DigitaldevLx\LaravelMoloni\Facades\Moloni;
```

### Companies

```php
// List all companies
$companies = Moloni::companies()->getAll();
```

### Customers

```php
$companyId = config('moloni.company_id');

// List all customers
$customers = Moloni::customers()->getAll($companyId);

// Find a customer by VAT number
$customer = Moloni::customers()->getByVat($companyId, '123456789');

// Create a customer using a DTO
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Customer as CustomerDto;

$dto = new CustomerDto(
    vat: '123456789',
    number: 'C001',
    name: 'John Doe',
    email: 'john@example.com',
    address: '123 Main Street',
    city: 'Lisbon',
    zipCode: '1000-001',
    countryId: 1,
);

$customer = Moloni::customers()->insert($companyId, $dto);

// Create a customer using an array
$customer = Moloni::customers()->insert($companyId, [
    'vat' => '123456789',
    'number' => 'C001',
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);
```

### Products

```php
// List all products
$products = Moloni::products()->getAll($companyId);

// Find a product by reference
$product = Moloni::products()->getByReference($companyId, 'PROD-001');

// Create a product using a DTO
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Product as ProductDto;
use DigitaldevLx\LaravelMoloni\Enums\ProductType;

$dto = new ProductDto(
    name: 'Widget',
    reference: 'PROD-001',
    type: ProductType::Product,
    categoryId: 1,
    unitId: 1,
    price: 29.99,
);

$product = Moloni::products()->insert($companyId, $dto);
```

### Invoices

```php
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Document as DocumentDto;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\DocumentProduct;
use DigitaldevLx\LaravelMoloni\DataTransferObjects\Payment;

// Create an invoice
$dto = new DocumentDto(
    documentSetId: 1,
    customerId: 1,
    date: '2026-03-31',
    expirationDate: '2026-04-30',
    products: [
        new DocumentProduct(
            productId: 1,
            qty: 2,
            price: 29.99,
        ),
    ],
    payments: [
        new Payment(
            paymentMethodId: 1,
            value: 59.98,
            date: '2026-03-31',
        ),
    ],
);

$invoice = Moloni::invoices()->insert($companyId, $dto);

// Get PDF link for a document
$pdfLink = Moloni::invoices()->getPdfLink($companyId, $invoiceId);
```

### Other Document Types

The package supports all Moloni document types through dedicated resources:

- `Moloni::receipts()` - Receipts
- `Moloni::creditNotes()` - Credit Notes
- `Moloni::debitNotes()` - Debit Notes
- `Moloni::simplifiedInvoices()` - Simplified Invoices
- `Moloni::invoiceReceipts()` - Invoice Receipts
- `Moloni::deliveryNotes()` - Delivery Notes
- `Moloni::billsOfLading()` - Bills of Lading
- `Moloni::waybills()` - Waybills
- `Moloni::estimates()` - Estimates
- `Moloni::documents()` - Generic Documents

### Settings Resources

Access configuration resources for taxes, payment methods, document sets, and more:

```php
$taxes = Moloni::taxes()->getAll($companyId);
$paymentMethods = Moloni::paymentMethods()->getAll($companyId);
$documentSets = Moloni::documentSets()->getAll($companyId);
$warehouses = Moloni::warehouses()->getAll($companyId);
$units = Moloni::measurementUnits()->getAll($companyId);
$maturityDates = Moloni::maturityDates()->getAll($companyId);
$deliveryMethods = Moloni::deliveryMethods()->getAll($companyId);
$bankAccounts = Moloni::bankAccounts()->getAll($companyId);

// Global resources (no company_id needed)
$countries = Moloni::countries()->getAll();
$currencies = Moloni::currencies()->getAll();
$languages = Moloni::languages()->getAll();
$exemptions = Moloni::taxExemptions()->getAll();
$fiscalZones = Moloni::fiscalZones()->getAll($countryId);
```

### Using DTOs

The package provides readonly Data Transfer Objects for type-safe data handling:

- `Customer` - Customer data
- `Product` - Product data
- `Document` - Document header data
- `DocumentProduct` - Document line item data
- `Supplier` - Supplier data
- `Tax` - Tax data
- `Payment` - Payment data
- `Address` - Address data

All DTOs are located in the `DigitaldevLx\LaravelMoloni\DataTransferObjects` namespace.

### Using Events

The package dispatches events for all mutation operations:

| Event | Dispatched When |
|---|---|
| `DocumentCreated` | A document is successfully created |
| `DocumentCancelled` | A document is cancelled |
| `DocumentClosed` | A document is closed/finalized |
| `CustomerCreated` | A new customer is created |
| `CustomerUpdated` | A customer is updated |
| `ProductCreated` | A new product is created |
| `ProductUpdated` | A product is updated |
| `TokenRefreshed` | The OAuth token is refreshed |

All events are located in the `DigitaldevLx\LaravelMoloni\Events` namespace.

#### Auto-discovery (recommended)

Create a listener class in your `app/Listeners` directory. Laravel automatically discovers and registers listeners based on the type-hint in the `handle` method:

```php
use DigitaldevLx\LaravelMoloni\Events\DocumentCreated;

class SendInvoiceNotification
{
    public function handle(DocumentCreated $event): void
    {
        // $event->data contains the API response
        // $event->documentType contains the document type
    }
}
```

#### Manual registration

Register listeners manually in your `AppServiceProvider`:

```php
use DigitaldevLx\LaravelMoloni\Events\DocumentCreated;
use Illuminate\Support\Facades\Event;

public function boot(): void
{
    Event::listen(
        DocumentCreated::class,
        SendInvoiceNotification::class,
    );
}
```

#### Closure listeners

```php
use DigitaldevLx\LaravelMoloni\Events\DocumentCreated;
use Illuminate\Support\Facades\Event;

Event::listen(function (DocumentCreated $event) {
    // ...
});
```

### Error Handling

The package provides granular exception handling that maps directly to [Moloni's error system](https://www.moloni.pt/dev/controlo-de-erros/).

#### Exception Types

| Exception | When |
|---|---|
| `AuthenticationException` | OAuth2 errors (invalid credentials, expired tokens, invalid client) |
| `ValidationException` | Data validation errors (required fields, invalid NIF, invalid email, etc.) |
| `RateLimitException` | API rate limit exceeded (HTTP 429) |
| `MoloniException` | All other API errors |

All exceptions extend `MoloniException`, so you can catch them all with a single catch block or handle each type individually.

#### Authentication Errors

```php
use DigitaldevLx\LaravelMoloni\Exceptions\AuthenticationException;
use DigitaldevLx\LaravelMoloni\Enums\AuthError;

try {
    $customers = Moloni::customers()->getAll($companyId);
} catch (AuthenticationException $e) {
    $e->authError;        // AuthError enum (e.g. AuthError::InvalidGrant)
    $e->errorDescription; // Moloni's error description string
    $e->getMessage();     // Full formatted message
}
```

Available `AuthError` enum values: `InvalidClient`, `InvalidUri`, `RedirectUriMismatch`, `InvalidRequest`, `UnsupportedGrantType`, `UnauthorizedClient`, `InvalidGrant`, `InvalidScope`.

#### Validation Errors

```php
use DigitaldevLx\LaravelMoloni\Exceptions\ValidationException;
use DigitaldevLx\LaravelMoloni\Enums\ValidationErrorCode;

try {
    $customer = Moloni::customers()->insert($companyId, $data);
} catch (ValidationException $e) {
    $e->errors;             // Array of ['code' => int, 'field' => string, 'description' => string]
    $e->getFieldErrors();   // ['field_name' => 'description', ...]
    $e->hasFieldError('vat'); // true/false
}
```

Validation error codes (mapped via `ValidationErrorCode` enum):

| Code | Meaning |
|------|---------|
| 1 | Campo obrigatorio |
| 2 | Campo numerico invalido |
| 3 | Endereco de email invalido |
| 4 | Valor deve ser unico |
| 5 | Valor invalido |
| 6 | URL invalido |
| 7 | Codigo postal invalido |
| 8 | NIF portugues invalido |
| 9 | Data deve estar no formato AAAA-MM-DD |
| 10 | Associacao de documento invalida |
| 11 | Documento nao pode ser enviado para a AT |
| 12 | Data invalida |
| 13 | Numero de telefone invalido |
| 14 | Artigo tem taxas conflituantes |
| 15 | Artigo tem multiplas entradas de IVA |
| 16 | Identificacao do cliente obrigatoria (Art. 36 CIVA) |
| 17 | Limite de caracteres excedido |

#### Catching All Errors

```php
use DigitaldevLx\LaravelMoloni\Exceptions\MoloniException;
use DigitaldevLx\LaravelMoloni\Exceptions\AuthenticationException;
use DigitaldevLx\LaravelMoloni\Exceptions\ValidationException;
use DigitaldevLx\LaravelMoloni\Exceptions\RateLimitException;

try {
    $invoice = Moloni::invoices()->insert($companyId, $data);
} catch (AuthenticationException $e) {
    // Handle auth errors (invalid credentials, expired tokens)
} catch (ValidationException $e) {
    // Handle validation errors (invalid data)
} catch (RateLimitException $e) {
    // Handle rate limiting (retry later)
} catch (MoloniException $e) {
    // Handle all other API errors
}
```

### HasMoloniDocuments Trait

Add the `HasMoloniDocuments` trait to any Eloquent model to associate it with Moloni documents:

```php
use DigitaldevLx\LaravelMoloni\Concerns\HasMoloniDocuments;

class Order extends Model
{
    use HasMoloniDocuments;
}

// Then use it
$order->moloniDocuments;
```

## Available Resources

| Resource | Accessor Method |
|---|---|
| Companies | `Moloni::companies()` |
| Customers | `Moloni::customers()` |
| Suppliers | `Moloni::suppliers()` |
| Products | `Moloni::products()` |
| Product Categories | `Moloni::productCategories()` |
| Invoices | `Moloni::invoices()` |
| Receipts | `Moloni::receipts()` |
| Credit Notes | `Moloni::creditNotes()` |
| Debit Notes | `Moloni::debitNotes()` |
| Simplified Invoices | `Moloni::simplifiedInvoices()` |
| Invoice Receipts | `Moloni::invoiceReceipts()` |
| Delivery Notes | `Moloni::deliveryNotes()` |
| Bills of Lading | `Moloni::billsOfLading()` |
| Waybills | `Moloni::waybills()` |
| Estimates | `Moloni::estimates()` |
| Documents | `Moloni::documents()` |
| Taxes | `Moloni::taxes()` |
| Tax Exemptions | `Moloni::taxExemptions()` |
| Payment Methods | `Moloni::paymentMethods()` |
| Document Sets | `Moloni::documentSets()` |
| Warehouses | `Moloni::warehouses()` |
| Measurement Units | `Moloni::measurementUnits()` |
| Maturity Dates | `Moloni::maturityDates()` |
| Delivery Methods | `Moloni::deliveryMethods()` |
| Bank Accounts | `Moloni::bankAccounts()` |
| Countries | `Moloni::countries()` |
| Fiscal Zones | `Moloni::fiscalZones()` |
| Languages | `Moloni::languages()` |
| Currencies | `Moloni::currencies()` |

## Testing

```bash
vendor/bin/pest
```

## Static Analysis

```bash
vendor/bin/phpstan analyse
```

## Code Style

```bash
vendor/bin/pint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [DigitalDev LX](https://digitaldev.pt/packages/laravel-moloni)
