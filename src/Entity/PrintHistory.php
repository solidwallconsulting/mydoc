<?php

namespace App\Entity;

use App\Repository\PrintHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrintHistoryRepository::class)
 */
class PrintHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="printedHistories")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $printDate;

    /**
     * @ORM\ManyToOne(targetEntity=Contacts::class, inversedBy="printHistories")
     */
    private $contract;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getPrintDate(): ?\DateTimeInterface
    {
        return $this->printDate;
    }

    public function setPrintDate(?\DateTimeInterface $printDate): self
    {
        $this->printDate = $printDate;

        return $this;
    }

    public function getContract(): ?Contacts
    {
        return $this->contract;
    }

    public function setContract(?Contacts $contract): self
    {
        $this->contract = $contract;

        return $this;
    }
}
