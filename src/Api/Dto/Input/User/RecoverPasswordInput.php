<?php

namespace App\Api\Dto\Input\User;

use App\Api\Dto\Input\InputInterface;

class RecoverPasswordInput implements InputInterface
{
    public string $password;
}