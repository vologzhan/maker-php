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

    #[ORM\OneToOne(inversedBy: 'projectNew', cascade: ['persist', 'remove'])]
    private ?Directory $dir = null;

    /** @var Collection<int, Controller> */
    #[ORM\OneToMany(targetEntity: Controller::class, mappedBy: 'project')]
    private Collection $controllers;

    /** @var Collection<int, Response> */
    #[ORM\OneToMany(targetEntity: Response::class, mappedBy: 'project')]
    private Collection $responses;

    # ------------------------------------------------------------------------------------------------------------------

    public function __construct()
    {
        $this->controllers = new ArrayCollection();
        $this->responses = new ArrayCollection();
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getId(): int
    {
        return $this->id;
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getDir(): ?Directory
    {
        return $this->dir;
    }

    public function setDir(?Directory $dir): static
    {
        $this->dir = $dir;

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

    # ------------------------------------------------------------------------------------------------------------------

    /**
     * @return Response[]
     */
    public function getResponses(): array
    {
        return $this->responses->getValues();
    }

    public function getResponsesMap(): array
    {
        $responseMap = [];
        foreach ($this->responses as $response) {
            $responseMap[$response->getClassName()] = $response;
        }

        return $responseMap;
    }

    public function addResponse(Response $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setProject($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): static
    {
        $this->responses->removeElement($response);

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------
}
