<?php

namespace App\Enum\Currency;

enum CurrencyCode: string
{
    case UAH = 'UAH';
    case USD = 'USD';
    case EUR = 'EUR';

    public static function getBasicValue(): string
    {
        return self::UAH->value;
    }
}
