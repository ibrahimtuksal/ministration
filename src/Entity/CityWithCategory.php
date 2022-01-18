<?php

namespace App\Entity;

use App\Repository\CityWithCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityWithCategoryRepository::class)
 */
class CityWithCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="cityWithCategories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="cityWithCategories")
     */
    private $Category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

        return $this;
    }
}
