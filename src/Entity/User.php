<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    private ?float $poids = null;

    #[ORM\Column(nullable: true)]
    private ?float $taille = null;

    #[ORM\Column(length: 10)]
    private ?string $sexe = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Seance::class)]
    private Collection $seances;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Programme::class)]
    private Collection $programmes;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->programmes = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): static
    {
        $this->poids = $poids;
        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(?float $taille): static
    {
        $this->taille = $taille;
        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;
        return $this;
    }

    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setUser($this);
        }
        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            if ($seance->getUser() === $this) {
                $seance->setUser(null);
            }
        }
        return $this;
    }

    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): static
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->setUser($this);
        }
        return $this;
    }

    public function removeProgramme(Programme $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            if ($programme->getUser() === $this) {
                $programme->setUser(null);
            }
        }
        return $this;
    }

    // Implémentation de UserInterface

    public function getRoles(): array
    {
        return ['ROLE_USER']; // Ajoute des rôles si besoin
    }

    public function eraseCredentials(): void
    {
        // Rien à faire ici pour l'instant
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
