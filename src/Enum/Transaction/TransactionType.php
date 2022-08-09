<?php

namespace App\Enum\Transaction;

enum TransactionType: string
{
    case Income = 'Income';
    case Expense = 'Expense';
    case TransferFrom = 'TransferFrom';
    case TransferTo = 'TransferTo';
}
