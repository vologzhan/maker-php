<?php declare(strict_types=1);

namespace App\Entity;

use App\Enum\Type;
use App\Repository\FieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldRepository::class)]
class Field
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private Response $response;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(enumType: Type::class)]
    private Type $type;

    #[ORM\Column(length: 255)]
    private string $phpType;

    #[ORM\Column]
    private bool $isArray;

    #[ORM\Column]
    private bool $isNullable;

    #[ORM\ManyToOne]
    private ?Response $object = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPhpType(): string
    {
        return $this->phpType;
    }

    public function setPhpType(string $phpType): static
    {
        $this->phpType = $phpType;

        return $this;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function setIsArray(bool $isArray): static
    {
        $this->isArray = $isArray;

        return $this;
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function setIsNullable(bool $isNullable): static
    {
        $this->isNullable = $isNullable;

        return $this;
    }

    public function getObject(): ?Response
    {
        return $this->object;
    }

    public function setObject(?Response $object): static
    {
        $this->object = $object;

        return $this;
    }
}
