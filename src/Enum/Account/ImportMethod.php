<?php

namespace App\Enum\Account;

enum ImportMethod: string
{
    case Monobank = 'monobank';
    case Privatbank = 'privatbank';
}
