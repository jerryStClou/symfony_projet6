<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(type: Types::OBJECT)]
    private ?object $vehicule = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(type: Types::OBJECT)]
    private ?object $user = null;

    #[ORM\Column(length: 255)]
    private ?string $startDate = null;

    #[ORM\Column(length: 255)]
    private ?string $endDate = null;

    #[ORM\Column(length: 255)]
    private ?string $orderId = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getVehicule(): ?object
    {
        return $this->vehicule;
    }

    public function setVehicule(object $vehicule): static
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getUser(): ?object
    {
        return $this->user;
    }

    public function setUser(object $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): static
    {
        $this->orderId = $orderId;

        return $this;
    }
}
