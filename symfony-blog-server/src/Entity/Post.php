<?php

namespace App\Entity;

use App\Common\Paging\Entity\MatchableEntityInterface;
use App\Common\Timestamp\TimestampedInterface;
use App\Common\Timestamp\TimestampedTrait;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'post')]
class Post implements TimestampedInterface, MatchableEntityInterface
{
    use TimestampedTrait;

    public const TITLE_MAX_LENGTH = 128;
    public const CONTENT_MAX_LENGTH = 8196;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id; /** @phpstan-ignore-line */

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $author;

    #[ORM\Column(type: 'string', length: self::TITLE_MAX_LENGTH)]
    #[Assert\Length(max: self::TITLE_MAX_LENGTH, maxMessage: 'post.title.too_long')]
    #[Assert\NotBlank(message: 'common.not_blank')]
    private string $title;

    #[ORM\Column(type: 'string', unique: true)]
    private string $slug;

    #[ORM\Column(type: 'text', length: self::CONTENT_MAX_LENGTH)]
    #[Assert\Length(max: self::CONTENT_MAX_LENGTH, maxMessage: 'post.content.too_long')]
    #[Assert\NotBlank(message: 'common.not_blank')]
    private string $content;

    /** {@inheritdoc} */
    public static function getMatching(): array
    {
        return [
            'id' => 'post.id',
            'title' => 'post.title',
            'content' => 'post.content',
            'author' => 'author.username',
            'created_at' => 'post.created_at',
            'updated_at' => 'post.updated_at'
        ];
    }

    /** {@inheritdoc} */
    public static function getMatchingMeta(): array
    {
        return [
            'range' => [
                'created_at',
                'updated_at',
            ],
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public static function generateSlug(string $title): string
    {
        return u($title)
            ->lower()
            ->replaceMatches('/\s+|\W/', '-')
            ->replaceMatches('/-{2,}/', '-')
            ->trim("-\t\n\r\0\x0B\x0C\u{A0}\u{FEFF}")
            ->toString();
    }

    public function updateSlug(): self
    {
        $this->slug = self::generateSlug($this->title);

        return $this;
    }
}
