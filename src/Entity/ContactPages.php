<?php

namespace App\Entity;

use App\Repository\ContactPagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactPagesRepository::class)
 */
class ContactPages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Contacts::class, inversedBy="contractPages")
     */
    private $contract;

    /**
     * @ORM\OneToMany(targetEntity=ContractPageProperty::class, mappedBy="page")
     */
    private $contractPageProperties;

    public function __construct()
    {
        $this->contractPageProperties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
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

    public function getContract(): ?Contacts
    {
        return $this->contract;
    }

    public function setContract(?Contacts $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * @return Collection|ContractPageProperty[]
     */
    public function getContractPageProperties(): Collection
    {
        return $this->contractPageProperties;
    }

    public function addContractPageProperty(ContractPageProperty $contractPageProperty): self
    {
        if (!$this->contractPageProperties->contains($contractPageProperty)) {
            $this->contractPageProperties[] = $contractPageProperty;
            $contractPageProperty->setPage($this);
        }

        return $this;
    }

    public function removeContractPageProperty(ContractPageProperty $contractPageProperty): self
    {
        if ($this->contractPageProperties->removeElement($contractPageProperty)) {
            // set the owning side to null (unless already changed)
            if ($contractPageProperty->getPage() === $this) {
                $contractPageProperty->setPage(null);
            }
        }

        return $this;
    }
}
