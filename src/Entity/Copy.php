<?php

namespace App\Entity;

use App\Repository\CopyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CopyRepository::class)
 */
class Copy
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
    private $copyReference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Document::class, inversedBy="copies")
     */
    private $document;

    /**
     * @ORM\OneToMany(targetEntity=Borrowing::class, mappedBy="copy")
     */
    private $borrowings;

    public function __construct()
    {
        $this->borrowings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCopyReference(): ?string
    {
        return $this->copyReference;
    }

    public function setCopyReference(string $copyReference): self
    {
        $this->copyReference = $copyReference;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    /**
     * @return Collection|Borrowing[]
     */
    public function getBorrowings(): Collection
    {
        return $this->borrowings;
    }

    public function addBorrowing(Borrowing $borrowing): self
    {
        if (!$this->borrowings->contains($borrowing)) {
            $this->borrowings[] = $borrowing;
            $borrowing->setCopy($this);
        }

        return $this;
    }

    public function removeBorrowing(Borrowing $borrowing): self
    {
        if ($this->borrowings->removeElement($borrowing)) {
            // set the owning side to null (unless already changed)
            if ($borrowing->getCopy() === $this) {
                $borrowing->setCopy(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->copyReference;
    }
}
