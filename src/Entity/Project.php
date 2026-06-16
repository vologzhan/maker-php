<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $path;

    /** @var Collection<int, Controller> */
    #[ORM\OneToMany(targetEntity: Controller::class, mappedBy: 'project')]
    private Collection $controllers;

    public function __construct()
    {
        $this->controllers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Controller[]
     */
    public function getControllers(): array
    {
        return $this->controllers->getValues();
    }

    public function addController(Controller $controller): static
    {
        if (!$this->controllers->contains($controller)) {
            $this->controllers->add($controller);
            $controller->setProject($this);
        }

        return $this;
    }

    public function removeController(Controller $controller): static
    {
        $this->controllers->removeElement($controller);

        return $this;
    }
}
