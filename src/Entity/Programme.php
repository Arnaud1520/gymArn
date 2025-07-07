<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProgrammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\User;
use App\Entity\Seance;
use App\Entity\ProgrammeExercice;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['programme:read', 'seance:read']],
    denormalizationContext: ['groups' => ['programme:write']]
)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['programme:read', 'seance:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['programme:read', 'seance:read', 'programme:write'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'programme', targetEntity: ProgrammeExercice::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['programme:read', 'programme:write'])]
    private Collection $programmeExercices;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['programme:read', 'programme:write'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'programme', targetEntity: Seance::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->programmeExercices = new ArrayCollection();
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

    /**
     * @return Collection|ProgrammeExercice[]
     */
    public function getProgrammeExercices(): Collection
    {
        return $this->programmeExercices;
    }

    public function addProgrammeExercice(ProgrammeExercice $programmeExercice): static
    {
        if (!$this->programmeExercices->contains($programmeExercice)) {
            $this->programmeExercices->add($programmeExercice);
            $programmeExercice->setProgramme($this);
        }
        return $this;
    }

    public function removeProgrammeExercice(ProgrammeExercice $programmeExercice): static
    {
        if ($this->programmeExercices->removeElement($programmeExercice)) {
            // set the owning side to null (unless already changed)
            if ($programmeExercice->getProgramme() === $this) {
                $programmeExercice->setProgramme(null);
            }
        }
        return $this;
    }



    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
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
            $seance->setProgramme($this);
        }
        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            if ($seance->getProgramme() === $this) {
                $seance->setProgramme(null);
            }
        }
        return $this;
    }
}
