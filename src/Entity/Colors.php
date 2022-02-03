<?php

namespace App\Entity;

use App\Repository\ColorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ColorsRepository::class)
 */
class Colors
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $bg_name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $text_name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $btn_name;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $btn_outline_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bg_light_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $border_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $turkish_name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="banner_color_id")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=ContactType::class, mappedBy="color")
     */
    private $contactTypes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->contactTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBgName(): ?string
    {
        return $this->bg_name;
    }

    public function setBgName(?string $bg_name): self
    {
        $this->bg_name = $bg_name;

        return $this;
    }

    public function getTextName(): ?string
    {
        return $this->text_name;
    }

    public function setTextName(?string $text_name): self
    {
        $this->text_name = $text_name;

        return $this;
    }

    public function getBtnName(): ?string
    {
        return $this->btn_name;
    }

    public function setBtnName(?string $btn_name): self
    {
        $this->btn_name = $btn_name;

        return $this;
    }

    public function getBtnOutlineName(): ?string
    {
        return $this->btn_outline_name;
    }

    public function setBtnOutlineName(?string $btn_outline_name): self
    {
        $this->btn_outline_name = $btn_outline_name;

        return $this;
    }

    public function getBgLightName(): ?string
    {
        return $this->bg_light_name;
    }

    public function setBgLightName(?string $bg_light_name): self
    {
        $this->bg_light_name = $bg_light_name;

        return $this;
    }

    public function getBorderName(): ?string
    {
        return $this->border_name;
    }

    public function setBorderName(?string $border_name): self
    {
        $this->border_name = $border_name;

        return $this;
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

    public function getTurkishName(): ?string
    {
        return $this->turkish_name;
    }

    public function setTurkishName(string $turkish_name): self
    {
        $this->turkish_name = $turkish_name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setBannerColor($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getBannerColor() === $this) {
                $user->setBannerColor(null);
            }
        }

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
            $contactType->setColor($this);
        }

        return $this;
    }

    public function removeContactType(ContactType $contactType): self
    {
        if ($this->contactTypes->removeElement($contactType)) {
            // set the owning side to null (unless already changed)
            if ($contactType->getColor() === $this) {
                $contactType->setColor(null);
            }
        }

        return $this;
    }
}
