<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\DirectoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DirectoryRepository::class)]
class Directory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $path;

    #[ORM\OneToOne(mappedBy: 'dir', cascade: ['persist', 'remove'])]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'children')]
    private ?Directory $parent;

    /** @var Collection<int, Directory> */
    #[ORM\OneToMany(targetEntity: Directory::class, mappedBy: 'parent')]
    private Collection $children;

    /** @var Collection<int, File> */
    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'directory')]
    private Collection $files;

    # ------------------------------------------------------------------------------------------------------------------

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getId(): int
    {
        return $this->id;
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
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

    # ------------------------------------------------------------------------------------------------------------------

    /**
     * @return Directory[]
     */
    public function getChildren(): array
    {
        return $this->children->getValues();
    }

    public function addChild(Directory $child): self
    {
        $this->children->add($child);
        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files->getValues();
    }

    public function addFile(File $file): static
    {
        $this->files->add($file);
        return $this;
    }

    public function removeFile(File $file): static
    {
        $this->files->removeElement($file);

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getParent(): ?Directory
    {
        return $this->parent;
    }

    public function setParent(?Directory $dir): self
    {
        $this->parent = $dir;
        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------
}
