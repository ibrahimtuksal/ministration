<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    CONST ISTANBUL = 40;
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
     * @ORM\Column(type="string", nullable=true ,length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=District::class, mappedBy="City")
     */
    private $districts;

    /**
     * @ORM\OneToMany(targetEntity=CityWithCategory::class, mappedBy="city")
     */
    private $cityWithCategories;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_active;

    /**
     * @ORM\OneToMany(targetEntity=BrandWithCity::class, mappedBy="city")
     */
    private $brandWithCities;

    public function __construct()
    {
        $this->districts = new ArrayCollection();
        $this->cityWithCategories = new ArrayCollection();
        $this->brandWithCities = new ArrayCollection();
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
            $district->setCity($this);
        }

        return $this;
    }

    public function removeDistrict(District $district): self
    {
        if ($this->districts->removeElement($district)) {
            // set the owning side to null (unless already changed)
            if ($district->getCity() === $this) {
                $district->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CityWithCategory[]
     */
    public function getCityWithCategories(): Collection
    {
        return $this->cityWithCategories;
    }

    public function addCityWithCategory(CityWithCategory $cityWithCategory): self
    {
        if (!$this->cityWithCategories->contains($cityWithCategory)) {
            $this->cityWithCategories[] = $cityWithCategory;
            $cityWithCategory->setCity($this);
        }

        return $this;
    }

    public function removeCityWithCategory(CityWithCategory $cityWithCategory): self
    {
        if ($this->cityWithCategories->removeElement($cityWithCategory)) {
            // set the owning side to null (unless already changed)
            if ($cityWithCategory->getCity() === $this) {
                $cityWithCategory->setCity(null);
            }
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection|BrandWithCity[]
     */
    public function getBrandWithCities(): Collection
    {
        return $this->brandWithCities;
    }

    public function addBrandWithCity(BrandWithCity $brandWithCity): self
    {
        if (!$this->brandWithCities->contains($brandWithCity)) {
            $this->brandWithCities[] = $brandWithCity;
            $brandWithCity->setCity($this);
        }

        return $this;
    }

    public function removeBrandWithCity(BrandWithCity $brandWithCity): self
    {
        if ($this->brandWithCities->removeElement($brandWithCity)) {
            // set the owning side to null (unless already changed)
            if ($brandWithCity->getCity() === $this) {
                $brandWithCity->setCity(null);
            }
        }

        return $this;
    }
}
