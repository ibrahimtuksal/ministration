<?php

namespace App\Controller;

use App\Entity\Corporate;
use App\Generator\GlobalGenerator;
use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CorporateController
 * @package App\Controller
 */
class CorporateController extends AbstractController
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
     * @Route("/kurumsal/{corporateSlug}", name="corporate")
     * @Template()
     * @param string $corporateSlug
     */
    public function index(string $corporateSlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        if ($globalGenerator->general->getIsReturnPhoneForAds()){
            $userLogService->userLogControl($request);
            return $this->redirect('tel:05061614265');
        }
        $corporate = $this->entityManager->getRepository(Corporate::class)->findOneBy(['slug' => $corporateSlug]);

        return [
            'corporate' => $corporate
        ];
    }
}
