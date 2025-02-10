<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $cookingTime = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?float $prix = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $imageUrl = null;

    // Relation OneToMany avec Recette
    #[ORM\OneToMany(mappedBy: "plat", targetEntity: Recette::class, orphanRemoval: true)]
    private Collection $recettes;

    // Relation ManyToMany avec Ingredient
    #[ORM\ManyToMany(targetEntity: Ingredient::class)]
    #[ORM\JoinTable(name: "plat_ingredient")]
    private Collection $ingredients;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
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

    public function getCookingTime(): ?int
    {
        return $this->cookingTime;
    }

    public function setCookingTime(int $cookingTime): static
    {
        $this->cookingTime = $cookingTime;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

     public function getImageUrl(): ?string
     {
         return $this->imageUrl;
     }
 
     public function setImageUrl(?string $imageUrl): static
     {
         $this->imageUrl = $imageUrl;
         return $this;
     }
    // Getter pour les recettes
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    // Getter pour les ingrédients
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    // Ajouter un ingrédient
    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }
        return $this;
    }
    public function removeIngredient(Ingredient $ingredient): self
{
    if ($this->ingredients->contains($ingredient)) {
        $this->ingredients->removeElement($ingredient);
    }

    return $this;
}
}
