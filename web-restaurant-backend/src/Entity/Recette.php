<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "text")]
    private ?string $recetteText = null;

    // Relation ManyToOne avec Plat
    #[ORM\ManyToOne(targetEntity: Plat::class, inversedBy: "recettes")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plat $plat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecetteText(): ?string
    {
        return $this->recetteText;
    }

    public function setRecetteText(string $recetteText): static
    {
        $this->recetteText = $recetteText;
        return $this;
    }

    // Getter et Setter pour la relation ManyToOne avec Plat
    public function getPlat(): ?Plat
    {
        return $this->plat;
    }

    public function setPlat(?Plat $plat): static
    {
        $this->plat = $plat;
        return $this;
    }
}
