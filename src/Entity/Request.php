<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $className = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\OneToOne(inversedBy: 'request', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $file = null;

    /** @var Collection<int, Controller> */
    #[ORM\OneToMany(targetEntity: Controller::class, mappedBy: 'request')]
    private Collection $controllers;

    public function __construct()
    {
        $this->controllers = new ArrayCollection();
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function setClassName(string $className): static
    {
        $this->className = $className;

        return $this;
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

    # ------------------------------------------------------------------------------------------------------------------

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): static
    {
        $this->file = $file;

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------

    /**
     * @return Controller[]
     */
    public function getControllers(): array
    {
        return $this->controllers->getValues();
    }

    public function addController(Controller $controller): static
    {
        $this->controllers->add($controller);

        return $this;
    }

    public function removeController(Controller $controller): static
    {
        $this->controllers->removeElement($controller);

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------
}
