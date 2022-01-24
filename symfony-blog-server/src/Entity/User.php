<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email', User::EMAIL_ALREADY_TAKEN_MESSAGE)]
#[UniqueEntity('username', User::USERNAME_ALREADY_IN_USE_MESSAGE)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const EMAIL_MAX_LENGTH                = 180;
    public const EMAIL_MAX_LENGTH_MESSAGE        = 'Your email cannot be longer than {{ limit }} characters.';
    public const EMAIL_ALREADY_TAKEN_MESSAGE     = 'This email is already taken.';
    public const EMAIL_BLANK_MESASGE             = 'You must specify an email address';
    public const EMAIL_INVALID_MESSAGE           = 'You must use a valid email address';

    public const USERNAME_VALIDATOR              = '/^[A-Za-z0-9-_]*$/';
    public const USERNAME_MIN_LENGTH             = 3;
    public const USERNAME_MAX_LENGTH             = 48;
    public const USERNAME_MIN_LENGTH_MESSAGE     = 'Your username must be at least {{ limit }} characters long.';
    public const USERNAME_MAX_LENGTH_MESSAGE     = 'Your username cannot be longer than {{ limit }} characters.';
    public const USERNAME_ALREADY_IN_USE_MESSAGE = 'This username is already taken.';
    public const USERNAME_BLANK_MESSAGE          = 'You must specify an username';
    public const USERNAME_INVALID_MESSAGE        = 'You must not use any special characters in your username.';

    public const PASSWORD_MIN_LENGTH             = 4;
    public const PASSWORD_MAX_LENGTH             = 4096;
    public const PASSWORD_MIN_LENGTH_MESSAGE     = 'Your password must be at least {{ limit }} characters long.';
    public const PASSWORD_MAX_LENGTH_MESSAGE     = 'Your password cannot be longer than {{ limit }} characters.';
    public const PASSWORD_BLANK_MESSAGE          = 'You must specify a password';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['display'])]
    private $id;

    #[Assert\Email(message: self::EMAIL_INVALID_MESSAGE)]
    #[Assert\Length(max: self::EMAIL_MAX_LENGTH, maxMessage: self::EMAIL_MAX_LENGTH_MESSAGE)]
    #[Assert\NotBlank(message: self::USERNAME_BLANK_MESSAGE)]
    #[ORM\Column(type: 'string', length: self::EMAIL_MAX_LENGTH, unique: true)]
    #[Groups(['display'])]
    private $email;

    #[Assert\Regex(pattern: self::USERNAME_VALIDATOR, message: self::USERNAME_INVALID_MESSAGE)]
    #[Assert\Length(min: self::USERNAME_MIN_LENGTH, max: self::USERNAME_MAX_LENGTH, minMessage: self::USERNAME_MIN_LENGTH_MESSAGE, maxMessage: self::USERNAME_MAX_LENGTH_MESSAGE)]
    #[Assert\NotBlank(message: self::USERNAME_BLANK_MESSAGE)]
    #[ORM\Column(type: 'string', length: self::USERNAME_MAX_LENGTH, unique: true)]
    #[Groups(['display'])]
    private $username;

    #[ORM\Column(type: 'json')]
    #[Groups(['display'])]
    private $roles = [];

    #[Assert\Length(min: self::PASSWORD_MIN_LENGTH, max: self::PASSWORD_MAX_LENGTH, minMessage: self::PASSWORD_MIN_LENGTH_MESSAGE, maxMessage: self::PASSWORD_MAX_LENGTH_MESSAGE)]
    #[Assert\NotBlank(message: self::PASSWORD_BLANK_MESSAGE)]
    #[ORM\Column(type: 'string', length: self::PASSWORD_MAX_LENGTH)]
    private $password;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['display'])]
    private $registeredAt;

    public function __construct()
    {
        $this->registeredAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = (string) $password;

        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = (string) $username;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
