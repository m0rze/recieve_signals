<?php

namespace App\Entity;

use App\Repository\SignalsDataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SignalsDataRepository::class)
 */
class SignalsData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $signal_time;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $other;

    /**
     * @ORM\Column(type="text")
     */
    private $signal_type;

    /**
     * @ORM\Column(type="text")
     */
    private $signal_data;

    /**
     * @ORM\Column(type="text")
     */
    private $signal_currencies;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSignalTime(): ?string
    {
        return $this->signal_time;
    }

    public function setSignalTime(string $signal_time): self
    {
        $this->signal_time = $signal_time;

        return $this;
    }

    public function getOther(): ?string
    {
        return $this->other;
    }

    public function setOther(?string $other): self
    {
        $this->other = $other;

        return $this;
    }

    public function getSignalType(): ?string
    {
        return $this->signal_type;
    }

    public function setSignalType(string $signal_type): self
    {
        $this->signal_type = $signal_type;

        return $this;
    }

    public function getSignalData(): ?string
    {
        return $this->signal_data;
    }

    public function setSignalData(string $signal_data): self
    {
        $this->signal_data = $signal_data;

        return $this;
    }

    public function getSignalCurrencies(): ?string
    {
        return $this->signal_currencies;
    }

    public function setSignalCurrencies(string $signal_currencies): self
    {
        $this->signal_currencies = $signal_currencies;

        return $this;
    }
}
