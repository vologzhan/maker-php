<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Directory $directory = null;

    # --------------------------------------------------------------------------------------------------------------
    public function __construct(Directory $directory, string $path)
    {
        $this->directory = $directory;
        $this->path = $path;
    }

    # --------------------------------------------------------------------------------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    # --------------------------------------------------------------------------------------------------------------

    public function getPath(): ?string
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
}
