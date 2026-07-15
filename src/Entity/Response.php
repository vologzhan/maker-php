<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ResponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'responses')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\Column(length: 255)]
    private string $className;

    #[ORM\OneToOne(inversedBy: 'response', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $file = null;

    /**
     * @var Collection<int, Field>
     */
    #[ORM\OneToMany(targetEntity: Field::class, mappedBy: 'response')]
    private Collection $fields;

    # ------------------------------------------------------------------------------------------------------------------

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getId(): int
    {
        return $this->id;
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

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName(string $className): static
    {
        $this->className = $className;

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): static
    {
        $this->file = $file;

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields->getValues();
    }

    public function addField(Field $field): static
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setResponse($this);
        }

        return $this;
    }

    public function removeField(Field $field): static
    {
        $this->fields->removeElement($field);

        return $this;
    }

    # ------------------------------------------------------------------------------------------------------------------
}
