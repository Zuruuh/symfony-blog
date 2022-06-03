<?php

namespace App\Entity;

use App\Common\Constant\UserRoles;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', 'user.email.already_in_use')]
#[UniqueEntity('username', 'user.username.already_taken')]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const USER_ROLE  = UserRoles::USER_ROLE;
    public const ADMIN_ROLE = UserRoles::ADMIN_ROLE;
    public const SUPER_ADMIN_ROLE = UserRoles::SUPER_ADMIN_ROLE;

    public const USERNAME_MAX_LENGTH = 32;
    public const USERNAME_MIN_LENGTH = 3;

    public const PASSWORD_MIN_LENGTH = 4;
    public const PASSWORD_MAX_LENGTH = 32;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email(message: 'user.email.invalid')]
    #[Assert\NotBlank(message: 'common.not_blank')]
    #[Assert\Type('string')]
    private string $email;

    #[ORM\Column(type: 'string', length: self::USERNAME_MAX_LENGTH, unique: true)]
    #[Assert\Length(min: self::USERNAME_MIN_LENGTH, max: self::USERNAME_MAX_LENGTH, minMessage: 'user.username.too_short', maxMessage: 'user.username.too_long')]
    #[Assert\Regex(pattern: '/^[a-zA-Z\-_]+$/')]
    #[Assert\NotBlank(message: 'common.not_blank')]
    #[Assert\Type('string')]
    private string $username;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'common.not_blank')]
    #[Assert\Type('string')]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        return array_unique([...$this->roles, self::USER_ROLE]);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string|string[] $roles
     */
    public function hasRoles(array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        foreach($roles as $role) {
            if (!in_array($role, $this->roles)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string|string[] $roles
     */
    public function addRoles(string|array $roles): self
    {
        $roles = is_array($roles) ? $roles : [$roles];
        $this->roles = array_unique([...$this->roles, ...$roles]);

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

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post) && $post->getAuthor() === $this) {
            $post->setAuthor(null);
        }

        return $this;
    }
}
