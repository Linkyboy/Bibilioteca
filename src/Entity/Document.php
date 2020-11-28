<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"document"="Document","book"="Book","cd"="CD","dvd"="DVD"})
 */
abstract class Document
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
    private $referenceNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $inCollectionDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $availability;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $publisher;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPinned;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $illustration;

    /**
     * @ORM\OneToMany(targetEntity=Participates::class, mappedBy="document")
     */
    private $participates;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="documents")
     * @ORM\JoinTable(name="document_category")
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity=Copy::class, mappedBy="document")
     */
    private $copies;


    public function __construct()
    {
        $this->participates = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->copies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferenceNumber(): ?string
    {
        return $this->referenceNumber;
    }

    public function setReferenceNumber(string $referenceNumber): self
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
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

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getInCollectionDate(): ?\DateTimeInterface
    {
        return $this->inCollectionDate;
    }

    public function setInCollectionDate(\DateTimeInterface $inCollectionDate): self
    {
        $this->inCollectionDate = $inCollectionDate;

        return $this;
    }

    public function getAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getIsPinned(): ?bool
    {
        return $this->isPinned;
    }

    public function setIsPinned(bool $isPinned): self
    {
        $this->isPinned = $isPinned;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    /**
     * @return Collection|Participates[]
     */
    public function getParticipates(): Collection
    {
        return $this->participates;
    }

    public function addParticipate(Participates $participate): self
    {
        if (!$this->participates->contains($participate)) {
            $this->participates[] = $participate;
            $participate->setDocument($this);
        }

        return $this;
    }

    public function removeParticipate(Participates $participate): self
    {
        if ($this->participates->removeElement($participate)) {
            // set the owning side to null (unless already changed)
            if ($participate->getDocument() === $this) {
                $participate->setDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|Copy[]
     */
    public function getCopies(): Collection
    {
        return $this->copies;
    }

    public function addCopy(Copy $copy): self
    {
        if (!$this->copies->contains($copy)) {
            $this->copies[] = $copy;
            $copy->setDocument($this);
        }

        return $this;
    }

    public function removeCopy(Copy $copy): self
    {
        if ($this->copies->removeElement($copy)) {
            // set the owning side to null (unless already changed)
            if ($copy->getDocument() === $this) {
                $copy->setDocument(null);
            }
        }

        return $this;
    }

}
