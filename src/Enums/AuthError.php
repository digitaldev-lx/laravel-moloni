<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Enums;

enum AuthError: string
{
    case InvalidClient = 'invalid_client';
    case InvalidUri = 'invalid_uri';
    case RedirectUriMismatch = 'redirect_uri_mismatch';
    case InvalidRequest = 'invalid_request';
    case UnsupportedGrantType = 'unsupported_grant_type';
    case UnauthorizedClient = 'unauthorized_client';
    case InvalidGrant = 'invalid_grant';
    case InvalidScope = 'invalid_scope';

    public function label(): string
    {
        return match ($this) {
            self::InvalidClient => 'Client ID inexistente ou invalido',
            self::InvalidUri => 'URI de redireccionamento em falta ou invalido',
            self::RedirectUriMismatch => 'URI fornecido nao corresponde ao registado',
            self::InvalidRequest => 'Pedido invalido ou parametros em falta',
            self::UnsupportedGrantType => 'Tipo de grant nao suportado',
            self::UnauthorizedClient => 'Cliente sem acesso ao tipo de grant especificado',
            self::InvalidGrant => 'Token expirado, codigo invalido ou credenciais incorrectas',
            self::InvalidScope => 'Scope solicitado nao suportado',
        };
    }
}
