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
    private ?Directory $directory = null;

    #[ORM\Column(nullable: true, enumType: FileType::class)]
    private ?FileType $type = null;

    #[ORM\OneToOne(mappedBy: 'file')]
    private ?Controller $controller = null;

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

    public function getDirectory(): ?Directory
    {
        return $this->directory;
    }

    public function setDirectory(?Directory $directory): static
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

    public function getController(): ?Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): static
    {
        // set the owning side of the relation if necessary
        if ($controller->getFile() !== $this) {
            $controller->setFile($this);
        }

        $this->controller = $controller;

        return $this;
    }
}
