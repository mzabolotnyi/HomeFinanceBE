<?php

namespace App\Api\Dto\Input\User;

use App\Api\Dto\Input\InputInterface;

class RegisterInput implements InputInterface
{
    public string $email;
    public string $name;
    public string $password;
}