<?php

namespace App\Controller;

use App\Entity\Faq;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/sikca-sorulan-sorular", name="faq")
     * @Template()
     */
    public function index()
    {
        $faqs = $this->entityManager->getRepository(Faq::class)->findAll();

        return [
            'faqs' => $faqs
        ];
    }
}
