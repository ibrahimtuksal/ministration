<?php

namespace App\Entity;

use App\Repository\ContactTypeValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactTypeValueRepository::class)
 */
class ContactTypeValue
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=ContactType::class, mappedBy="value")
     */
    private $contactTypes;

    public function __construct()
    {
        $this->contactTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|ContactType[]
     */
    public function getContactTypes(): Collection
    {
        return $this->contactTypes;
    }

    public function addContactType(ContactType $contactType): self
    {
        if (!$this->contactTypes->contains($contactType)) {
            $this->contactTypes[] = $contactType;
            $contactType->setValue($this);
        }

        return $this;
    }

    public function removeContactType(ContactType $contactType): self
    {
        if ($this->contactTypes->removeElement($contactType)) {
            // set the owning side to null (unless already changed)
            if ($contactType->getValue() === $this) {
                $contactType->setValue(null);
            }
        }

        return $this;
    }
}
