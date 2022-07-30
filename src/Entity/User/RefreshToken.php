<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;

#[ORM\Entity]
#[ORM\Table(name: 'user_refresh_token')]
class RefreshToken extends BaseRefreshToken
{
}
