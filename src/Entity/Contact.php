<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
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
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=ContactType::class, inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=District::class, mappedBy="contact")
     */
    private $districts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_index;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_sidebar;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_fixed;

    public function __construct()
    {
        $this->districts = new ArrayCollection();
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?ContactType
    {
        return $this->type;
    }

    public function setType(?ContactType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|District[]
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    public function addDistrict(District $district): self
    {
        if (!$this->districts->contains($district)) {
            $this->districts[] = $district;
            $district->setContact($this);
        }

        return $this;
    }

    public function removeDistrict(District $district): self
    {
        if ($this->districts->removeElement($district)) {
            // set the owning side to null (unless already changed)
            if ($district->getContact() === $this) {
                $district->setContact(null);
            }
        }

        return $this;
    }

    public function getIsIndex(): ?bool
    {
        return $this->is_index;
    }

    public function setIsIndex(bool $is_index): self
    {
        $this->is_index = $is_index;

        return $this;
    }

    public function getIsSidebar(): ?bool
    {
        return $this->is_sidebar;
    }

    public function setIsSidebar(?bool $is_sidebar): self
    {
        $this->is_sidebar = $is_sidebar;

        return $this;
    }

    public function getIsFixed(): ?bool
    {
        return $this->is_fixed;
    }

    public function setIsFixed(?bool $is_fixed): self
    {
        $this->is_fixed = $is_fixed;

        return $this;
    }
}
