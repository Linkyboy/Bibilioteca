<?php

namespace App\Entity;

use App\Repository\BorrowingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BorrowingRepository::class)
 */
class Borrowing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $borrowingDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expectedReturnDate;

    /**
     * @ORM\Column(type="datetime",nullable=true,nullable=true)
     */
    private $actualReturnDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="borrowings")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Copy::class, inversedBy="borrowings")
     */
    private $copy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorrowingDate(): ?\DateTimeInterface
    {
        return $this->borrowingDate;
    }

    public function setBorrowingDate(\DateTimeInterface $borrowingDate): self
    {
        $this->borrowingDate = $borrowingDate;

        return $this;
    }

    public function getExpectedReturnDate(): ?\DateTimeInterface
    {
        return $this->expectedReturnDate;
    }

    public function setExpectedReturnDate(\DateTimeInterface $expectedReturnDate): self
    {
        $this->expectedReturnDate = $expectedReturnDate;

        return $this;
    }

    public function getActualReturnDate(): ?\DateTimeInterface
    {
        return $this->actualReturnDate;
    }

    public function setActualReturnDate(\DateTimeInterface $actualReturnDate): self
    {
        $this->actualReturnDate = $actualReturnDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCopy(): ?Copy
    {
        return $this->copy;
    }

    public function setCopy(?Copy $copy): self
    {
        $this->copy = $copy;

        return $this;
    }
}
