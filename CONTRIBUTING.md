# Contributing

Contributions are welcome and will be fully credited.

## Pull Requests

1. Fork the repository.
2. Create a feature branch from `main` (`git checkout -b feature/my-feature`).
3. Make your changes.
4. Ensure all tests pass.
5. Ensure code style is correct.
6. Ensure static analysis passes.
7. Commit your changes with a descriptive message.
8. Push to your fork and submit a pull request.

## Development Setup

Clone your fork and install dependencies:

```bash
git clone git@github.com:your-username/laravel-moloni.git
cd laravel-moloni
composer install
```

## Running Tests

```bash
vendor/bin/pest
```

To run tests with coverage:

```bash
vendor/bin/pest --coverage --min=80
```

## Static Analysis

Run PHPStan at level 6:

```bash
vendor/bin/phpstan analyse
```

## Code Style

This project uses Laravel Pint for code formatting. Run it before committing:

```bash
vendor/bin/pint
```

To check formatting without making changes:

```bash
vendor/bin/pint --test
```

## Guidelines

- Write tests for any new functionality.
- Follow existing code conventions and patterns.
- Keep pull requests focused on a single change.
- Update documentation when adding or changing features.
- Add an entry to CHANGELOG.md under the `[Unreleased]` section.
