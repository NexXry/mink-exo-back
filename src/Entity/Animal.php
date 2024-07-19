<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['animal_read']]),
        new GetCollection(normalizationContext: ['groups' => ['animal_read']]),
        new Post(normalizationContext: ['groups' => ['admin']], security: "is_granted('ROLE_ADMIN')"),
        new Patch(normalizationContext: ['groups' => ['admin']], security: "is_granted('ROLE_ADMIN')"),
        new Delete(normalizationContext: ['groups' => ['admin']], security: "is_granted('ROLE_ADMIN')")
    ],
    outputFormats: ['jsonld' => ['application/ld+json']],
)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['animal_read', 'admin'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['animal_read', 'admin'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['animal_read', 'admin'])]
    private ?int $age = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['animal_read', 'admin'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['admin'])]
    private ?float $priceHT = null;

    #[ORM\Column]
    #[Groups(['animal_read', 'admin'])]
    private ?float $priceTTC = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'animal')]
    #[Groups(['animal_read', 'admin'])]
    private Collection $images;

    #[ORM\ManyToOne(targetEntity: Species::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['animal_read', 'admin'])]
    private Species $species;

    #[ORM\ManyToOne(targetEntity: Race::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['animal_read', 'admin'])]
    private Race $race;

    #[ORM\Column(type: "string", enumType: Status::class)]
    #[Groups(['animal_read', 'admin'])]
    private Status $status = Status::NOT_READY;

    public function __construct()
    {
        $this->images = new ArrayCollection();
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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

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

    public function getPriceHT(): ?float
    {
        return $this->priceHT;
    }

    public function setPriceHT(float $priceHT): static
    {
        $this->priceHT = $priceHT;

        return $this;
    }

    public function getPriceTTC(): ?float
    {
        return $this->priceTTC;
    }

    public function setPriceTTC(float $priceTTC): static
    {
        $this->priceTTC = $priceTTC;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAnimal($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnimal() === $this) {
                $image->setAnimal(null);
            }
        }

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): static
    {
        $this->species = $species;
        return $this;
    }

    public function getRace(): Race
    {
        return $this->race;
    }

    public function setRace(Race $race): Animal
    {
        $this->race = $race;
        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): Animal
    {
        $this->status = $status;
        return $this;
    }
}
