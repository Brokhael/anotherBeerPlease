<?php

namespace App\Entity;

use App\Repository\RevenueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RevenueRepository::class)]
class Revenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $dispenser_id = null;

    #[ORM\Column]
    private ?float $service_time = null;

    #[ORM\Column]
    private ?float $service_money = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDispenserId(): ?int
    {
        return $this->dispenser_id;
    }

    public function setDispenserId(int $dispenser_id): static
    {
        $this->dispenser_id = $dispenser_id;

        return $this;
    }

    public function getServiceTime(): ?float
    {
        return $this->service_time;
    }

    public function setServiceTime(float $service_time): static
    {
        $this->service_time = $service_time;

        return $this;
    }

    public function getServiceMoney(): ?float
    {
        return $this->service_money;
    }

    public function setServiceMoney(float $service_money): static
    {
        $this->service_money = $service_money;

        return $this;
    }
}
