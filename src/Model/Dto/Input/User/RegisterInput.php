<?php

namespace App\Model\Dto\Input\User;

use App\Model\Dto\Input\InputInterface;

class RegisterInput implements InputInterface
{
    public string $email;
    public string $name;
    public string $password;
}