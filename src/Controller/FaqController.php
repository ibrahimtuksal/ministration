<?php

namespace App\Controller;

use App\Entity\Faq;
use App\Generator\GlobalGenerator;
use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        if ($globalGenerator->general->getIsReturnPhoneForAds() && $request->query->get('ads') == "1"){
            $userLogService->userLogControl($request);
            return $this->redirect('tel:05061614265');
        }
        $faqs = $this->entityManager->getRepository(Faq::class)->findAll();

        return [
            'faqs' => $faqs
        ];
    }
}
