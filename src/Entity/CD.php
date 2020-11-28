<?php

namespace App\Entity;

use App\Repository\CDRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Document;
/**
 * @ORM\Entity(repositoryClass=CDRepository::class)
 */
class CD extends Document
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
    private $totalDuration;

    /**
     * @ORM\OneToMany(targetEntity=Track::class, mappedBy="cd")
     */
    private $tracks;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
    }



    public function getTotalDuration(): ?\DateTimeInterface
    {
        return $this->totalDuration;
    }

    public function setTotalDuration(\DateTimeInterface $totalDuration): self
    {
        $this->totalDuration = $totalDuration;

        return $this;
    }

    /**
     * @return Collection|Track[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): self
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
            $track->setCd($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getCd() === $this) {
                $track->setCd(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getTitle();
    }
}
