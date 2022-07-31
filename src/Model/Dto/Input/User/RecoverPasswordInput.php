<?php

namespace App\Model\Dto\Input\User;

use App\Model\Dto\Input\InputInterface;

class RecoverPasswordInput implements InputInterface
{
    public string $password;
}