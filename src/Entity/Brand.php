<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 */
class Brand
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
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="brands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=BrandContent::class, mappedBy="brand")
     */
    private $brandContents;

    /**
     * @ORM\OneToMany(targetEntity=BrandWithCity::class, mappedBy="brand")
     */
    private $brandWithCities;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_city;

    public function __construct()
    {
        $this->brandContents = new ArrayCollection();
        $this->brandWithCities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActiveCity(array $city): ?array
    {

        $activeCity = [];
        /** @var BrandWithCity $value */
        foreach ($city as $value){
            if ($value->getCity()->getIsActive()){
                $activeCity[] = $value->getCity();
            }
        }
        return $activeCity;
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

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

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
     * @return Collection|BrandContent[]
     */
    public function getBrandContents(): Collection
    {
        return $this->brandContents;
    }

    public function addBrandContent(BrandContent $brandContent): self
    {
        if (!$this->brandContents->contains($brandContent)) {
            $this->brandContents[] = $brandContent;
            $brandContent->setBrand($this);
        }

        return $this;
    }

    public function removeBrandContent(BrandContent $brandContent): self
    {
        if ($this->brandContents->removeElement($brandContent)) {
            // set the owning side to null (unless already changed)
            if ($brandContent->getBrand() === $this) {
                $brandContent->setBrand(null);
            }
        }

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
            $brandWithCity->setBrand($this);
        }

        return $this;
    }

    public function removeBrandWithCity(BrandWithCity $brandWithCity): self
    {
        if ($this->brandWithCities->removeElement($brandWithCity)) {
            // set the owning side to null (unless already changed)
            if ($brandWithCity->getBrand() === $this) {
                $brandWithCity->setBrand(null);
            }
        }

        return $this;
    }

    public function getIsCity(): ?bool
    {
        return $this->is_city;
    }

    public function setIsCity(?bool $is_city): self
    {
        $this->is_city = $is_city;

        return $this;
    }
}
