<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Moloni API Credentials
    |--------------------------------------------------------------------------
    |
    | OAuth2 credentials for the Moloni API. Register your application at
    | https://www.moloni.pt/developers/ to obtain these values.
    |
    */

    'client_id' => env('MOLONI_CLIENT_ID'),
    'client_secret' => env('MOLONI_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Moloni User Credentials
    |--------------------------------------------------------------------------
    |
    | Used for the OAuth2 password grant flow.
    |
    */

    'username' => env('MOLONI_USERNAME'),
    'password' => env('MOLONI_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Default Company ID
    |--------------------------------------------------------------------------
    |
    | The default company ID to use when none is provided. Most API endpoints
    | require a company_id parameter. Set this to avoid passing it every time.
    |
    */

    'company_id' => env('MOLONI_COMPANY_ID'),

];
