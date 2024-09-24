<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
#[Vich\Uploadable]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $Titre;

    #[ORM\Column(type: 'float')]
    private float $Prix;

    #[ORM\Column(type: 'integer')]
    private int $AnneeCirculation;

    #[ORM\Column(type: 'float')]
    private float $Kilometrage;


    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id")]
    private ?Utilisateur $utilisateur;


    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: Image::class, orphanRemoval: true, cascade: ['persist'])]
    private $images;


    #[ORM\OneToMany(targetEntity: FormulaireContact::class, mappedBy: "voiture")]
    private $formulairecontact;


    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): self
    {
        $this->Titre = $Titre;
        return $this;
    }

    public function getAnneeCirculation(): ?int
    {
        return $this->AnneeCirculation;
    }

    public function setAnneeCirculation(int $AnneeCirculation): self
    {
        $this->AnneeCirculation = $AnneeCirculation;
        return $this;
    }

    public function getKilometrage(): ?float
    {
        return $this->Kilometrage;
    }

    public function setKilometrage(float $Kilometrage): self
    {
        $this->Kilometrage = $Kilometrage;
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

    public function getFormulaireContact(): Collection
{
    return $this->formulairecontact;
}

public function addFormulaireContact(FormulaireContact $formulairecontact): self
{
    if (!$this->formulairecontact->contains($formulairecontact)) {
        $this->formulairecontact[] = $formulairecontact;
        $formulairecontact->setVoiture($this);
    }

    return $this;
}

public function removeFormulaireContact(FormulaireContact $formulairecontact): self
{
    if ($this->formulairecontact->removeElement($formulairecontact)) {
        // set the owning side to null (unless already changed)
        if ($formulairecontact->getVoiture() === $this) {
            $formulairecontact->setVoiture(null);
        }
    }

    return $this;
}






    
  /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProducts($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProducts() === $this) {
                $image->setProducts(null);
            }
        }

        return $this;
    }

   
    
}
