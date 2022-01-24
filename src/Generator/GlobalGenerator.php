<?php

namespace App\Generator;

use App\Entity\Category;
use App\Entity\General;
use App\Entity\Phone;
use Doctrine\ORM\EntityManagerInterface;

class GlobalGenerator
{

    /**
     * @var string $name
     */
    public string $name;

    /**
     * @var Phone $phone
     */
    public Phone $phone;

    /**
     * @var bool $isSlider
     */
    public bool $isSlider;

    /**
     * @var General $general
     */
    public General $general;

    public array $category;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->name = $this->setName();
        $this->phone = $this->setPhone();
        $this->isSlider = $this->setIsSlider();
        $this->general = $this->setGeneral();
        $this->category = $this->setCategory();
    }

    public function setGeneral(): General
    {
        /** @var General $general */
        $general = $this->em->getRepository(General::class)->find(General::GLOBAL);
        return $general;
    }

    public function setIsSlider()
    {
        $general = $this->em->getRepository(General::class)->find(General::GLOBAL);
        return (bool) $general->getIsSlider();
    }

    private function setName(): string
    {
        $general = $this->em->getRepository(General::class)->find(General::GLOBAL);

        return (string) $general->getName();
    }

    private function setPhone(): Phone
    {
        /** @var Phone $phone */
        $phone = $this->em->getRepository(Phone::class)->find(Phone::PHONE);

        return $phone;
    }
    private function setCategory()
    {
     $category = $this->em->getRepository(Category::class)->findAll();
     return $category;
    }

}