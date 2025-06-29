<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\ExerciceController;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;

#[ORM\Entity(repositoryClass: ExerciceRepository::class)]


#[ApiResource(
    normalizationContext: ['groups' => ['programme:read']],
    denormalizationContext: ['groups' => ['programme:write']],
    operations: [new Get(), new GetCollection(), new Patch(),] // tu peux aussi retirer ça si tu veux tout gérer à la main
)]


#[Vich\Uploadable]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['programme:read', 'programme:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['programme:read', 'programme:write'])]
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    #[Groups(['programme:read', 'programme:write'])]
    private ?string $description = null;

    // 🔁 Ce champ contiendra le nom du fichier
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['programme:read'])]
    private ?string $image = null;

        // 🔁 Ce champ ne sera pas stocké en BDD, il sert uniquement à l'upload
    #[Vich\UploadableField(mapping: 'exercice_image', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Seance::class, mappedBy: 'exercices')]
    private Collection $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }

        public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile !== null) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

        public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
{
    return $this->updatedAt;
}

public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
{
    $this->updatedAt = $updatedAt;
    return $this;
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
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
            $seance->addExercice($this);
        }
        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            $seance->removeExercice($this);
        }
        return $this;
    }
}
