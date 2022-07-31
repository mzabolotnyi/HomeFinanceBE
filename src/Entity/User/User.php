<?php

namespace App\Entity\User;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Mixin\TimestampableEntity;
use App\Repository\User\UserRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ["email"])]
#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        'get' => [
            'security' => "is_granted('ROLE_ADMIN') or object === user"
        ],
        'put' => [
            'security' => "is_granted('ROLE_ADMIN') or object === user"
        ]
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotNull]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    #[Groups('read')]
    private ?string $email;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(max: 255)]
    #[Groups(['read', 'write'])]
    private ?string $name;

    #[ORM\Column(type: Types::JSON)]
    #[Groups('internal')]
    private array $roles = [];

    #[ORM\Column(nullable: true, unique: true)]
    #[Groups('internal')]
    private ?string $token;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('internal')]
    private ?DateTime $tokenGeneratedAt;

    #[ORM\Column]
    #[Groups('internal')]
    private ?string $password;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups('internal')]
    private bool $enabled = false;

    #[Assert\Length(min: 6, max: 60)]
    #[Groups('internal')]
    private ?string $plainPassword = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    #[Groups('internal')]
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenGeneratedAt(): ?\DateTimeInterface
    {
        return $this->tokenGeneratedAt;
    }

    public function setTokenGeneratedAt(?\DateTimeInterface $tokenGeneratedAt): self
    {
        $this->tokenGeneratedAt = $tokenGeneratedAt;

        return $this;
    }

    /**
     * @param int $ttl in seconds
     * @return bool
     */
    public function tokenExpired(int $ttl): bool
    {
        return $this->tokenGeneratedAt < (new DateTime())->modify("-$ttl second");
    }

    public function generateToken(): void
    {
        $this->token            = bin2hex(random_bytes(16));
        $this->tokenGeneratedAt = new DateTime();
    }

    public function clearToken(): void
    {
        $this->token            = null;
        $this->tokenGeneratedAt = null;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
