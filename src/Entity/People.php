<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PeopleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: PeopleRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(security: "is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_USER')")
    ],
    normalizationContext: ['groups' => ['people:read']],
    denormalizationContext: ['groups' => ['people:write']]
)]
class People
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie:read', 'movie:write', 'people:read', 'people:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'movie:write', 'people:read', 'people:write'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'movie:write', 'people:read', 'people:write'])]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['movie:read', 'movie:write', 'people:read', 'people:write'])]
    private ?\DateTimeInterface $dateOfBirth = null;

    /**
     * @var Collection<int, MovieHasPeople>
     */
    #[ORM\OneToMany(targetEntity: MovieHasPeople::class, mappedBy: 'people')]
    #[Groups(['people:read', 'people:write'])]
    private Collection $movieHasPeople;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'people:read', 'people:write'])]
    private ?string $nationality = null;

    public function __construct()
    {
        $this->movieHasPeople = new ArrayCollection();
    }

    #[ORM\Column(length: 255)]

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): static
    {
        $this->nationality = $nationality;

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
            $movieHasPerson->setPeople($this);
        }

        return $this;
    }

    public function removeMovieHasPerson(MovieHasPeople $movieHasPerson): static
    {
        if ($this->movieHasPeople->removeElement($movieHasPerson)) {
            // set the owning side to null (unless already changed)
            if ($movieHasPerson->getPeople() === $this) {
                $movieHasPerson->setPeople(null);
            }
        }

        return $this;
    }
}
