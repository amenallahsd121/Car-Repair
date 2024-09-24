<?php

namespace App\Entity;

use App\Repository\HorairesOuvertureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HorairesOuvertureRepository::class)]
class HorairesOuverture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idHoraire = null;

    #[ORM\Column(type: 'string', length: 255)]
    private $Jour;

    #[ORM\Column(type: 'string', length: 255)]
    private $Horaire;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id")]
    private ?Utilisateur $utilisateur;

    public function getId(): ?int
    {
        return $this->idHoraire;

    }

    public function getJour(): ?string
    {
        return $this->Jour;
    }

    public function setJour(string $Jour): self
    {
        $this->Jour = $Jour;
        return $this;
    }

    public function getHoraire(): ?string
    {
        return $this->Horaire;
    }

    public function setHoraire(string $Horaire): self
    {
        $this->Horaire = $Horaire;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
}
