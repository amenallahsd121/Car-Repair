<?php

namespace App\Entity;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Utilisateur implements UserInterface,PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $roles;

    #[ORM\OneToMany(targetEntity: HorairesOuverture::class, mappedBy: "utilisateur")]
    private $horairesOuvertures;

    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: "utilisateur")]
    private $service;

    #[ORM\OneToMany(targetEntity: Voiture::class, mappedBy: "utilisateur")]
    private $voiture;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    // public function getRoles(): ?string
    // {
    //     return $this->roles;
    // }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return [$this->roles];
    }

    public function eraseCredentials()
    {
        // Implement if you need to erase sensitive data from the user
    }

    public function getSalt()
    {
        // We're not using a salt, as modern algorithms (bcrypt) handle salting internally
        return null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    
    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(Service $service): self
    {
        $this->service = $service;

        return $this;   
    }

    
    public function getHorairesOuvertures(): ?HorairesOuverture
    {
        return $this->horairesOuvertures;
    }

    public function setHorairesOuvertures(HorairesOuverture $horairesOuvertures): self
    {
        $this->horairesOuvertures = $horairesOuvertures;

        return $this;   
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(Voiture $voiture): self
    {
        $this->voiture = $voiture;

        return $this;   
    }
}