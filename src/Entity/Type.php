<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(security: "is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_USER')")
    ],
    normalizationContext: ['groups' => ['type:read']],
    denormalizationContext: ['groups' => ['type:write']]
)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie:read', 'type:read', 'type:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'type:read', 'type:write'])]
    private ?string $name = null;

    /**
     * @var Collection<int, MovieHasType>
     */
    #[ORM\OneToMany(targetEntity: MovieHasType::class, mappedBy: 'type')]
    private Collection $movieHasTypes;

    public function __construct()
    {
        $this->movieHasTypes = new ArrayCollection();
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
            $movieHasType->setType($this);
        }

        return $this;
    }

    public function removeMovieHasType(MovieHasType $movieHasType): static
    {
        if ($this->movieHasTypes->removeElement($movieHasType)) {
            // set the owning side to null (unless already changed)
            if ($movieHasType->getType() === $this) {
                $movieHasType->setType(null);
            }
        }

        return $this;
    }
}
