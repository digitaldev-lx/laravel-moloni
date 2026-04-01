<?php

declare(strict_types=1);

use DigitaldevLx\LaravelMoloni\Http\MoloniClient;
use DigitaldevLx\LaravelMoloni\Moloni;
use DigitaldevLx\LaravelMoloni\Resources\Companies;
use DigitaldevLx\LaravelMoloni\Resources\Customers;
use DigitaldevLx\LaravelMoloni\Resources\Documents\Invoices;
use DigitaldevLx\LaravelMoloni\Resources\Products;
use DigitaldevLx\LaravelMoloni\Resources\Taxes;

/** @covers Moloni */
describe('Moloni manager', function (): void {
    beforeEach(function (): void {
        $this->moloni = app(Moloni::class);
    });

    it('returns a Companies resource', function (): void {
        expect($this->moloni->companies())->toBeInstanceOf(Companies::class);
    });

    it('returns a Customers resource', function (): void {
        expect($this->moloni->customers())->toBeInstanceOf(Customers::class);
    });

    it('returns a Products resource', function (): void {
        expect($this->moloni->products())->toBeInstanceOf(Products::class);
    });

    it('returns an Invoices resource', function (): void {
        expect($this->moloni->invoices())->toBeInstanceOf(Invoices::class);
    });

    it('returns a Taxes resource', function (): void {
        expect($this->moloni->taxes())->toBeInstanceOf(Taxes::class);
    });

    it('returns the same resource instance on repeated calls', function (): void {
        $first = $this->moloni->customers();
        $second = $this->moloni->customers();

        expect($first)->toBe($second);
    });

    it('exposes the underlying MoloniClient', function (): void {
        expect($this->moloni->client())->toBeInstanceOf(MoloniClient::class);
    });

    it('is registered as a singleton in the container', function (): void {
        $first = app(Moloni::class);
        $second = app(Moloni::class);

        expect($first)->toBe($second);
    });
});
