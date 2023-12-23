<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Publication::class)]
    private Collection $publication;

    public function __construct()
    {
        $this->publication = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPublication(): Collection
    {
        return $this->publication;
    }

    public function addPublication(Publication $publication): static
    {
        if (!$this->publication->contains($publication)) {
            $this->publication->add($publication);
            $publication->setCategory($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): static
    {
        if ($this->publication->removeElement($publication)) {
            if ($publication->getCategory() === $this) {
                $publication->setCategory(null);
            }
        }

        return $this;
    }
}
