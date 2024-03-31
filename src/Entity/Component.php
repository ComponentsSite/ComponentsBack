<?php

namespace App\Entity;

use App\Repository\ComponentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
//Serializer groups
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ComponentRepository::class)]
class Component
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllWithinName"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getAllWithinName"])]
    #[Assert\NotBlank(message:"Un composant doit avoir un nom")]
    #[Assert\NotNull(message:"Un composant doit avoir un nom")]
    #[Assert\Length(min:5, minMessage:"Le nom d'un composant forcément faire plus de {{limit}} charactères")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getAllWithinName"])]
    private ?string $type = null;

    #[ORM\Column(length: 24)]
    #[Groups(["getAllWithinName"])]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["getAllWithinName"])]
    private ?\DateTimeInterface $creatAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["getAllWithinName"])]
    private ?\DateTimeInterface $updateAt = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

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
        if($this->getStatus() !== "abandonné"){

            $this->name = $name;
        }
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->creatAt;
    }

    public function setCreatAt(\DateTimeInterface $creatAt): static
    {
        $this->creatAt = $creatAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): static
    {
        if($this->getStatus() !== "abandonné"){

            $this->updateAt = $updateAt;
        }
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}
