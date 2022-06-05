<?php

namespace App\Service;

use App\Entity\Contact;
use App\Entity\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;

class ContactService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var Contact
     */
    private Contact $contact;
    /**
     * @var ContactType
     */
    private ContactType $contactType;
    /**
     * @var ContactRepository
     */
    private ContactRepository $contactRepository;

    public function __construct(
        EntityManagerInterface $em,
        ContactRepository $contactRepository
    )
    {
        $this->em = $em;
        $this->contactRepository = $contactRepository;
    }

    public function contactIndex()
    {
        return $this->contactRepository->findBy(['is_index' => true]);
    }

    public function contactSidebar()
    {
        return $this->contactRepository->findBy(['is_sidebar' => true]);
    }

    public function contactFixed()
    {
        /** @var Contact $contact */
        return $this->contactRepository->findOneBy(['is_fixed' => true]);
    }

    public function contactWhatsApp()
    {
        /** @var Contact $contact */
        return $this->contactRepository->getWhatsApp();
    }
}