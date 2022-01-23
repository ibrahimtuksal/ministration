<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=Brand::class, mappedBy="category")
     */
    private $brands;

    /**
     * @ORM\OneToMany(targetEntity=CityWithCategory::class, mappedBy="Category", cascade={"remove"})
     */
    private $cityWithCategories;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    public function __construct()
    {
        $this->brands = new ArrayCollection();
        $this->cityWithCategories = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection|Brand[]
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    public function addBrand(Brand $brand): self
    {
        if (!$this->brands->contains($brand)) {
            $this->brands[] = $brand;
            $brand->setCategory($this);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): self
    {
        if ($this->brands->removeElement($brand)) {
            // set the owning side to null (unless already changed)
            if ($brand->getCategory() === $this) {
                $brand->setCategory(null);
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
            $cityWithCategory->setCategory($this);
        }

        return $this;
    }

    public function removeCityWithCategory(CityWithCategory $cityWithCategory): self
    {
        if ($this->cityWithCategories->removeElement($cityWithCategory)) {
            // set the owning side to null (unless already changed)
            if ($cityWithCategory->getCategory() === $this) {
                $cityWithCategory->setCategory(null);
            }
        }

        return $this;
    }

    public function getIsCity(): ?bool
    {
        return $this->is_city;
    }

    public function setIsCity(bool $is_city): self
    {
        $this->is_city = $is_city;

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
}
