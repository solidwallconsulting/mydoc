<?php

namespace App\Entity;

use App\Repository\ContactsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactsRepository::class)
 */
class Contacts
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
     * @ORM\OneToMany(targetEntity=ContactPages::class, mappedBy="contract")
     */
    private $contractPages;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="contacts")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=PrintHistory::class, mappedBy="contract")
     */
    private $printHistories;

    public function __construct()
    {
        $this->contractPages = new ArrayCollection();
        $this->printHistories = new ArrayCollection();
    }

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

    /**
     * @return Collection|ContactPages[]
     */
    public function getContractPages(): Collection
    {
        return $this->contractPages;
    }

    public function addContractPage(ContactPages $contractPage): self
    {
        if (!$this->contractPages->contains($contractPage)) {
            $this->contractPages[] = $contractPage;
            $contractPage->setContract($this);
        }

        return $this;
    }

    public function removeContractPage(ContactPages $contractPage): self
    {
        if ($this->contractPages->removeElement($contractPage)) {
            // set the owning side to null (unless already changed)
            if ($contractPage->getContract() === $this) {
                $contractPage->setContract(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|PrintHistory[]
     */
    public function getPrintHistories(): Collection
    {
        return $this->printHistories;
    }

    public function addPrintHistory(PrintHistory $printHistory): self
    {
        if (!$this->printHistories->contains($printHistory)) {
            $this->printHistories[] = $printHistory;
            $printHistory->setContract($this);
        }

        return $this;
    }

    public function removePrintHistory(PrintHistory $printHistory): self
    {
        if ($this->printHistories->removeElement($printHistory)) {
            // set the owning side to null (unless already changed)
            if ($printHistory->getContract() === $this) {
                $printHistory->setContract(null);
            }
        }

        return $this;
    }
}
