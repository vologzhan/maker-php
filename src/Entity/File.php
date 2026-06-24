<?php

namespace App\Entity;

use App\Enum\FileType;
use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $path;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[ORM\JoinColumn(nullable: false)]
    private Directory $directory;

    #[ORM\Column(nullable: true, enumType: FileType::class)]
    private ?FileType $type = null;

    # --------------------------------------------------------------------------------------------------------------

    public function getId(): int
    {
        return $this->id;
    }

    # --------------------------------------------------------------------------------------------------------------

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    # --------------------------------------------------------------------------------------------------------------

    public function getDirectory(): Directory
    {
        return $this->directory;
    }

    public function setDirectory(Directory $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    # --------------------------------------------------------------------------------------------------------------

    public function getType(): ?FileType
    {
        return $this->type;
    }

    public function setType(?FileType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
