<?php

namespace App\Entity;

use App\Repository\InternshipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InternshipRepository::class)]
class Internship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $category = null;

    #[ORM\Column(length: 50)]
    private ?string $level = null;

    #[ORM\Column]
    private ?\DateTime $pubDate = null;

    /**
     * @var Collection<int, UserInternship>
     */
    #[ORM\OneToMany(targetEntity: UserInternship::class, mappedBy: 'internship')]
    private Collection $internship;

    public function __construct()
    {
        $this->internship = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getPubDate(): ?\DateTime
    {
        return $this->pubDate;
    }

    public function setPubDate(\DateTime $pubDate): static
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    /**
     * @return Collection<int, UserInternship>
     */
    public function getInternship(): Collection
    {
        return $this->internship;
    }

    public function addInternship(UserInternship $internship): static
    {
        if (!$this->internship->contains($internship)) {
            $this->internship->add($internship);
            $internship->setInternship($this);
        }

        return $this;
    }

    public function removeInternship(UserInternship $internship): static
    {
        if ($this->internship->removeElement($internship)) {
            // set the owning side to null (unless already changed)
            if ($internship->getInternship() === $this) {
                $internship->setInternship(null);
            }
        }

        return $this;
    }
}
