<?php

namespace App\Api\Dto\Input\User;

use App\Api\Dto\Input\InputInterface;

class RecoverPasswordRequestInput implements InputInterface
{
    public string $email;
}