<?php

namespace App\Entity;

use App\Repository\DispenserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DispenserRepository::class)]
class Dispenser
{
    const STATUS_CLOSED = 'closed';
    const STATUS_OPEN = 'open';
    const PRICE_DEFAULT = 0;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $flow_volume = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $total_time_open = null;

    #[ORM\Column]
    private ?float $total_money = null;

    #[ORM\Column]
    private ?int $usage_count = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_open_time = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlowVolume(): ?float
    {
        return $this->flow_volume;
    }

    public function setFlowVolume(float $flow_volume): static
    {
        $this->flow_volume = $flow_volume;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalTimeOpen(): ?int
    {
        return $this->total_time_open;
    }

    public function setTotalTimeOpen(int $total_time_open): static
    {
        $this->total_time_open = $total_time_open;

        return $this;
    }

    public function getTotalMoney(): ?float
    {
        return $this->total_money;
    }

    public function setTotalMoney(float $total_money): static
    {
        $this->total_money = $total_money;

        return $this;
    }

    public function getUsageCount(): ?int
    {
        return $this->usage_count;
    }

    public function setUsageCount(int $usage_count): static
    {
        $this->usage_count = $usage_count;

        return $this;
    }

    public function getLastOpenTime(): ?\DateTimeInterface
    {
        return $this->last_open_time;
    }

    public function setLastOpenTime(?\DateTimeInterface $last_open_time): static
    {
        $this->last_open_time = $last_open_time;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

}
