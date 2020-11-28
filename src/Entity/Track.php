<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrackRepository::class)
 */
class Track
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity=CD::class, inversedBy="tracks")
     */
    private $cd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCd(): ?CD
    {
        return $this->cd;
    }

    public function setCd(?CD $cd): self
    {
        $this->cd = $cd;

        return $this;
    }
    public function __toString(){
        return $this->title;
    }
}
