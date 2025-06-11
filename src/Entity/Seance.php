<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SeanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Programme;
use App\Entity\User;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['seance:read']],
    denormalizationContext: ['groups' => ['seance:write']]
)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['seance:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['seance:read', 'seance:write'])] // <-- Ajouté ici
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['seance:read', 'seance:write'])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Programme::class, inversedBy: 'seances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['seance:read', 'seance:write'])] // <-- Ajouté ici
    private ?Programme $programme = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
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

    public function getProgramme(): ?Programme
    {
        return $this->programme;
    }

    public function setProgramme(?Programme $programme): static
    {
        $this->programme = $programme;
        return $this;
    }
}
