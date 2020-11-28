<?php

namespace App\Entity;

use App\Repository\DVDRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Document;
/**
 * @ORM\Entity(repositoryClass=DVDRepository::class)
 */
class DVD extends Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasBonus;


    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getHasBonus(): ?bool
    {
        return $this->hasBonus;
    }

    public function setHasBonus(bool $hasBonus): self
    {
        $this->hasBonus = $hasBonus;

        return $this;
    }
    public function __toString(){
        return $this->getTitle();
    }
}
