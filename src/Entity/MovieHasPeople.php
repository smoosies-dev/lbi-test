<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieHasPeopleRepository;
use App\Enum\Significance;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: MovieHasPeopleRepository::class)]
class MovieHasPeople
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie:read', 'people:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasPeople')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movie $movie = null;

    #[ORM\ManyToOne(inversedBy: 'movieHasPeople')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['movie:read', 'people:read'])]
    private ?People $people = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'movie:write', 'people:read'])]
    private ?string $role = null;

    #[ORM\Column(type: 'significance', nullable: true)]
    #[Groups(['movie:read', 'people:read'])]
    private ?Significance $significance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    public function getPeople(): ?People
    {
        return $this->people;
    }

    public function setPeople(?People $people): static
    {
        $this->people = $people;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getSignificance(): ?Significance
    {
        return $this->significance;
    }

    public function setSignificance(?Significance $significance): self
    {
        $this->significance = $significance;

        return $this;
    }
}
