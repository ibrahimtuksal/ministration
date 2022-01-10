<?php

namespace App\Entity;

use App\Repository\DistrictRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DistrictRepository::class)
 */
class District
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
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="districts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $City;

    /**
     * @ORM\OneToMany(targetEntity=Neighborhood::class, mappedBy="District")
     */
    private $neighborhoods;

    public function __construct()
    {
        $this->neighborhoods = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->City;
    }

    public function setCity(?City $City): self
    {
        $this->City = $City;

        return $this;
    }

    /**
     * @return Collection|Neighborhood[]
     */
    public function getNeighborhoods(): Collection
    {
        return $this->neighborhoods;
    }

    public function addNeighborhood(Neighborhood $neighborhood): self
    {
        if (!$this->neighborhoods->contains($neighborhood)) {
            $this->neighborhoods[] = $neighborhood;
            $neighborhood->setDistrict($this);
        }

        return $this;
    }

    public function removeNeighborhood(Neighborhood $neighborhood): self
    {
        if ($this->neighborhoods->removeElement($neighborhood)) {
            // set the owning side to null (unless already changed)
            if ($neighborhood->getDistrict() === $this) {
                $neighborhood->setDistrict(null);
            }
        }

        return $this;
    }
}
