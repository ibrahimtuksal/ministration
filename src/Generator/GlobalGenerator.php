<?php

namespace App\Generator;

use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\General;
use App\Entity\Phone;
use App\Entity\UserLog;
use App\Service\ContactService;
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

    public function __construct(EntityManagerInterface $em, ContactService $contactService)
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

    public function die(Request $request){
        $log = new UserLog();
        $log->setIp($request->getClientIp());
        $log->setCreatedAt(new \DateTime());
        $log->setAgent($_SERVER['HTTP_USER_AGENT']);
        if ($request->query->get('ads') === "1")
        {
            $log->setIsWhat(true);
        } else {
            $log->setIsWhat(false);
        }
        $this->em->persist($log);
        $this->em->flush();
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
        /** @var array $category */
        $category = $this->em->getRepository(Category::class)->findAll();

        return $category;
    }

}