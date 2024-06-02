<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['movie:read']],
    denormalizationContext: ['groups' => ['movie:write']],
    operations: [
        new Get(),
        new GetCollection(security: "is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_USER')")
    ],
)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie:read', 'movie:write', 'people:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'movie:write', 'people:read'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['movie:read', 'movie:write', 'people:read'])]
    private ?int $duration = null;

    /**
     * @var Collection<int, MovieHasPeople>
     */
    #[ORM\OneToMany(targetEntity: MovieHasPeople::class, mappedBy: 'movie')]
    #[Groups(['movie:read'])]
    private Collection $movieHasPeople;

    /**
     * @var Collection<int, MovieHasType>
     */
    #[ORM\OneToMany(targetEntity: MovieHasType::class, mappedBy: 'movie')]
    #[Groups(['movie:read'])]
    private Collection $movieHasTypes;

    
    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'movie:write', 'people:read'])]
    private $posterUrl;

    public function __construct()
    {
        $this->movieHasPeople = new ArrayCollection();
        $this->movieHasTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, MovieHasPeople>
     */
    public function getMovieHasPeople(): Collection
    {
        return $this->movieHasPeople;
    }

    public function addMovieHasPerson(MovieHasPeople $movieHasPerson): static
    {
        if (!$this->movieHasPeople->contains($movieHasPerson)) {
            $this->movieHasPeople->add($movieHasPerson);
            $movieHasPerson->setMovie($this);
        }

        return $this;
    }

    public function removeMovieHasPerson(MovieHasPeople $movieHasPerson): static
    {
        if ($this->movieHasPeople->removeElement($movieHasPerson)) {
            // set the owning side to null (unless already changed)
            if ($movieHasPerson->getMovie() === $this) {
                $movieHasPerson->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MovieHasType>
     */
    public function getMovieHasTypes(): Collection
    {
        return $this->movieHasTypes;
    }

    public function addMovieHasType(MovieHasType $movieHasType): static
    {
        if (!$this->movieHasTypes->contains($movieHasType)) {
            $this->movieHasTypes->add($movieHasType);
            $movieHasType->setMovie($this);
        }

        return $this;
    }

    public function removeMovieHasType(MovieHasType $movieHasType): static
    {
        if ($this->movieHasTypes->removeElement($movieHasType)) {
            // set the owning side to null (unless already changed)
            if ($movieHasType->getMovie() === $this) {
                $movieHasType->setMovie(null);
            }
        }

        return $this;
    }

    public function getPosterUrl(): ?string
    {
        return $this->posterUrl;
    }

    public function setPosterUrl(?string $posterUrl): self
    {
        $this->posterUrl = $posterUrl;

        return $this;
    }
}
