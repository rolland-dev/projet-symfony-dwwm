<?php

namespace App\Entity;

use App\Entity\Ingredients;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecettesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RecettesRepository::class)]
#[UniqueEntity('nom')]
class Recettes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min:5, max:50)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(1441)]
    private ?float $temps = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(50)]
    private ?int $personne = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(5)]
    private ?int $difficulte = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(500)]
    private ?float $prix = null;

    #[ORM\Column(nullable: true)]
    private ?bool $favori = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'recettes')]
    private Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable;
        $this->updateAt = new \DateTimeImmutable;
    }

    #[ORM\PrePersist()]
    public function setUpdatedAtValue() : void
    {
        $this->updateAt = new \DateTimeImmutable;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTemps(): ?float
    {
        return $this->temps;
    }

    public function setTemps(?float $temps): static
    {
        $this->temps = $temps;

        return $this;
    }

    public function getPersonne(): ?int
    {
        return $this->personne;
    }

    public function setPersonne(?int $personne): static
    {
        $this->personne = $personne;

        return $this;
    }

    public function getDifficulte(): ?int
    {
        return $this->difficulte;
    }

    public function setDifficulte(?int $difficulte): static
    {
        $this->difficulte = $difficulte;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function isFavori(): ?bool
    {
        return $this->favori;
    }

    public function setFavori(?bool $favori): static
    {
        $this->favori = $favori;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }
}
