<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ControllerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ControllerRepository::class)]
class Controller
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $path;

    #[ORM\Column(length: 255)]
    private string $method;

    #[ORM\ManyToOne(inversedBy: 'controllers')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\ManyToOne]
    private ?Response $response = null;

    #[ORM\OneToOne(inversedBy: 'controller', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private File $file;

    #[ORM\ManyToOne(inversedBy: 'controllers')]
    private ?Request $request = null;

    # ------------------------------------------------------------------------------------------------------------------

    public function getId(): int
    {
        return $this->id;
    }

    # ------------------------------------------------------------------------------------------------------------------

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

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): static
    {
        $this->response = $response;

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getFile(): File
    {
        return $this->file;
    }

    public function setFile(File $file): static
    {
        $this->file = $file;

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): static
    {
        $this->request = $request;

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------
}
