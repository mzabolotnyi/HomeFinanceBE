<?php

namespace App\Enum\Transaction;

enum TransactionType: string
{
    case Income = 'income';
    case Expense = 'expense';
    case TransferFrom = 'transferFrom';
    case TransferTo = 'transferTo';
}
