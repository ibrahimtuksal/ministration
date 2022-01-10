<?php

namespace App\Entity;

use App\Repository\NeighborhoodRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NeighborhoodRepository::class)
 */
class Neighborhood
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
     * @ORM\ManyToOne(targetEntity=District::class, inversedBy="neighborhoods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $District;

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

    public function getDistrict(): ?District
    {
        return $this->District;
    }

    public function setDistrict(?District $District): self
    {
        $this->District = $District;

        return $this;
    }
}
