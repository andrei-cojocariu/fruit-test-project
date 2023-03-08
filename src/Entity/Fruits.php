<?php

namespace App\Entity;

use App\Repository\FruitsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Fruits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $fv_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $family = null;

    #[ORM\Column(length: 255)]
    private ?string $fruit_order = null;

    #[ORM\Column(length: 255)]
    private ?string $genus = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_updated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\OneToOne(mappedBy: 'fruit', cascade: ['persist', 'remove'])]
    private ?FruitNutritions $fn_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    } 

    public function getFvId(): ?int
    {
        return $this->fv_id;
    }

    public function setFvId(int $fv_id): self
    {
        $this->fv_id = $fv_id;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getFruitOrder(): ?string
    {
        return $this->fruit_order;
    }

    public function setFruitOrder(string $fruit_order): self
    {
        $this->fruit_order = $fruit_order;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->date_updated;
    }

    public function setDateUpdated(\DateTimeInterface $date_updated): self
    {
        $this->date_updated = $date_updated;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFnId(): ?FruitNutritions
    {
        return $this->fn_id;
    }

    public function setFnId(FruitNutritions $fn_id): self
    {
        $this->fn_id = $fn_id;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->date_created = new \DateTime("now");
        $this->date_updated = $this->date_created;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->date_updated = new \DateTime("now");
    }
}
