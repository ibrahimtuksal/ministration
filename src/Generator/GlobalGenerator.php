<?php

namespace App\Generator;

use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Corporate;
use App\Entity\General;
use App\Entity\Phone;
use App\Entity\Slider;
use App\Entity\UserLog;
use App\Service\ContactService;
use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public array $contactIndex;

    public array $contactSidebar;

    public Contact $contactFixed;

    public Contact $contactWhatsApp;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var ContactService
     */
    private ContactService $contactService;
    /**
     * @var UserLogService
     */
    private UserLogService $userLogService;

    public function __construct(EntityManagerInterface $em, ContactService $contactService, UserLogService $userLogService)
    {
        $this->em = $em;
        $this->name = $this->setName();
        $this->phone = $this->setPhone();
        $this->isSlider = $this->setIsSlider();
        $this->general = $this->setGeneral();
        $this->category = $this->setCategory();
        $this->contactService = $contactService;
        $this->contactIndex = $this->contactIndex();
        $this->contactSidebar = $this->contactSidebar();
        $this->contactFixed = $this->contactFixed();
        $this->contactWhatsApp = $this->contactWhatsApp();
        $this->userLogService = $userLogService;
    }

    public function contactIndex()
    {
        return $this->contactService->contactIndex();
    }

    public function contactFixed()
    {
        return $this->contactService->contactFixed();
    }

    public function contactSidebar()
    {
        return $this->contactService->contactSidebar();
    }

    public function contactWhatsApp()
    {
        return $this->contactService->contactWhatsApp();
    }

    public function die(Request $request)
    {
        $this->userLogService->userLogControl($request);
    }

    public function setGeneral(): General
    {
        return $this->em->getRepository(General::class)->find(General::GLOBAL);
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
        return $this->em->getRepository(Phone::class)->find(Phone::PHONE);
    }
    private function setCategory()
    {
        return $this->em->getRepository(Category::class)->findAll();
    }
    public function getCorporate()
    {
        return $this->em->getRepository(Corporate::class)->findAll();
    }
    public function getBlog()
    {
        return $this->em->getRepository(Blog::class)->findAll();
    }
    public function getSlider()
    {
        return $this->em->getRepository(Slider::class)->findBy([], ['queue' => 'ASC']);
    }

}