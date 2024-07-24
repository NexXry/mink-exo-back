<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['image_read']]),
        new GetCollection(normalizationContext: ['groups' => ['image_read']]),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            normalizationContext: ['groups' => ['admin']],
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ],
    outputFormats: ['jsonld' => ['application/ld+json']],
)]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['animal_read', 'admin','image_read'])]
    private ?int $id = null;

    #[Groups(['animal_read', 'admin','image_read'])]
    #[ORM\Column]
    private string $name;

    #[Groups(['animal_read', 'admin','image_read'])]
    #[Vich\UploadableField(mapping: 'animals', fileNameProperty: 'filePath')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['animal_read', 'admin','image_read'])]
    public ?string $filePath = null;

    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'images')]
    #[Assert\NotNull]
    #[Groups(['image_read'])]
    private Animal $animal;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAnimal(): Animal
    {
        return $this->animal;
    }

    public function setAnimal(Animal $animal): Image
    {
        $this->animal = $animal;
        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

}
