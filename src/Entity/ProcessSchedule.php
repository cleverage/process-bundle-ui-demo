<?php

namespace App\Entity;

use App\Repository\ProcessScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProcessScheduleRepository::class)]
class ProcessSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $process = null;

    #[ORM\Column(length: 255)]
    private ?string $cronExpression = null;

    #[ORM\Column(type: 'json')]
    private string|array $context = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProcess(): ?string
    {
        return $this->process;
    }

    public function setProcess(string $process): static
    {
        $this->process = $process;

        return $this;
    }

    public function getCronExpression(): ?string
    {
        return $this->cronExpression;
    }

    public function setCronExpression(string $cronExpression): static
    {
        $this->cronExpression = $cronExpression;

        return $this;
    }

    public function getContext(): array
    {
        return is_array($this->context) ? $this->context : json_decode($this->context);
    }

    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    public function getNextExecution(): null
    {
        return null;
    }
}
