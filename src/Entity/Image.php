<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Voiture::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private $voiture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProducts(): ?Voiture
    {
        return $this->voiture;
    }

    public function setProducts(?Voiture $voiture): self
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function getImageUrl(string $imagesDirectory): ?string
    {
        if (!$this->name) {
            return null;
        }

        return $imagesDirectory . '/' . $this->name;
    }
    
}