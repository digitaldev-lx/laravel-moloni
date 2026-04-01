<?php

declare(strict_types=1);

namespace DigitaldevLx\LaravelMoloni\Enums;

enum ValidationErrorCode: int
{
    case Required = 1;
    case Numeric = 2;
    case InvalidEmail = 3;
    case Unique = 4;
    case InvalidValue = 5;
    case InvalidUrl = 6;
    case InvalidPostalCode = 7;
    case InvalidPortugueseNif = 8;
    case InvalidDateFormat = 9;
    case InvalidDocumentAssociation = 10;
    case CannotSendToTaxAuthority = 11;
    case InvalidDate = 12;
    case InvalidPhone = 13;
    case ConflictingTaxes = 14;
    case MultipleTaxEntries = 15;
    case CustomerIdentificationRequired = 16;
    case CharacterLimitExceeded = 17;

    public function label(): string
    {
        return match ($this) {
            self::Required => 'Campo obrigatorio',
            self::Numeric => 'Campo numerico invalido',
            self::InvalidEmail => 'Endereco de email invalido',
            self::Unique => 'Valor deve ser unico',
            self::InvalidValue => 'Valor invalido',
            self::InvalidUrl => 'URL invalido',
            self::InvalidPostalCode => 'Codigo postal invalido',
            self::InvalidPortugueseNif => 'NIF portugues invalido',
            self::InvalidDateFormat => 'Data deve estar no formato AAAA-MM-DD',
            self::InvalidDocumentAssociation => 'Associacao de documento invalida',
            self::CannotSendToTaxAuthority => 'Documento nao pode ser enviado para a AT',
            self::InvalidDate => 'Data invalida',
            self::InvalidPhone => 'Numero de telefone invalido',
            self::ConflictingTaxes => 'Artigo tem taxas conflituantes (outra + IVA)',
            self::MultipleTaxEntries => 'Artigo tem multiplas entradas de IVA',
            self::CustomerIdentificationRequired => 'Identificacao do cliente obrigatoria (Art. 36 CIVA)',
            self::CharacterLimitExceeded => 'Limite de caracteres excedido',
        };
    }
}
