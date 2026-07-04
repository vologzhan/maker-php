<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'project', cascade: ['persist', 'remove'])]
    private ?Dir $dir = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDir(): ?Dir
    {
        return $this->dir;
    }

    public function setDir(?Dir $dir): static
    {
        $this->dir = $dir;

        return $this;
    }
}
