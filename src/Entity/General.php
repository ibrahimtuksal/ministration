<?php

namespace App\Entity;

use App\Repository\GeneralRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GeneralRepository::class)
 * @ORM\Table(name="`general`")
 */
class General
{

    CONST GLOBAL = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $youtube;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categoryName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_slider;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $serviceName;

    /**
     * @ORM\Column(type="string", length=5000, nullable=true)
     */
    private $headCode;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_googletag;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $headCss;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    public function getIsSlider(): ?bool
    {
        return $this->is_slider;
    }

    public function setIsSlider(?bool $is_slider): self
    {
        $this->is_slider = $is_slider;

        return $this;
    }

    public function getSiteUrl(): ?string
    {
        return $this->siteUrl;
    }

    public function setSiteUrl(?string $siteUrl): self
    {
        $this->siteUrl = $siteUrl;

        return $this;
    }

    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    public function setServiceName(?string $serviceName): self
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function getHeadCode(): ?string
    {
        return $this->headCode;
    }

    public function setHeadCode(?string $headCode): self
    {
        $this->headCode = $headCode;

        return $this;
    }

    public function getIsGoogletag(): ?bool
    {
        return $this->is_googletag;
    }

    public function setIsGoogletag(?bool $is_googletag): self
    {
        $this->is_googletag = $is_googletag;

        return $this;
    }

    public function getHeadCss(): ?string
    {
        return $this->headCss;
    }

    public function setHeadCss(?string $headCss): self
    {
        $this->headCss = $headCss;

        return $this;
    }
}
