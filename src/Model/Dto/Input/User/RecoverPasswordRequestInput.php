<?php

namespace App\Model\Dto\Input\User;

use App\Model\Dto\Input\InputInterface;

class RecoverPasswordRequestInput implements InputInterface
{
    public string $email;
}