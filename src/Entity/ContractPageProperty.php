<?php

namespace App\Entity;

use App\Repository\ContractPagePropertyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContractPagePropertyRepository::class)
 */
class ContractPageProperty
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
    private $feildName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $feildID;

    /**
     * @ORM\ManyToOne(targetEntity=ContactPages::class, inversedBy="contractPageProperties")
     */
    private $page;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeildName(): ?string
    {
        return $this->feildName;
    }

    public function setFeildName(string $feildName): self
    {
        $this->feildName = $feildName;

        return $this;
    }

    public function getFeildID(): ?string
    {
        return $this->feildID;
    }

    public function setFeildID(string $feildID): self
    {
        $this->feildID = $feildID;

        return $this;
    }

    public function getPage(): ?ContactPages
    {
        return $this->page;
    }

    public function setPage(?ContactPages $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
