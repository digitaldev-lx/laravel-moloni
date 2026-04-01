# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

## [1.0.1] - 2026-04-01

### Fixed
- Code style fixes (Pint formatting)
- PHPDoc annotations across all Resource classes
- PHPStan compliance (excluded Concerns from analysis, fixed static constructors in DTOs, typed config casts in ServiceProvider)
- MoloniToken model property annotations

## [1.0.0] - 2026-03-31

### Added
- Initial release
- OAuth2 authentication with automatic token refresh
- Full Moloni API v1 coverage (~300+ endpoints)
- PHP Attributes for endpoint definition
- Readonly DTOs for Customer, Product, Document, Supplier, Tax, Payment, Address
- Enums for DocumentType, DocumentStatus, ProductType, TaxType
- Events for mutations (DocumentCreated, DocumentCancelled, DocumentClosed, CustomerCreated, CustomerUpdated, ProductCreated, ProductUpdated, TokenRefreshed)
- HasMoloniDocuments trait for Eloquent models
- Moloni facade with full IDE autocompletion
- PHPStan level 6 compliance
- Pint code formatting
- Pest test suite
