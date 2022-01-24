<?php

namespace App\Controller;

use App\Entity\Corporate;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return array
     */
    public function index(string $corporateSlug): array
    {
        $corporate = $this->entityManager->getRepository(Corporate::class)->findOneBy(['slug' => $corporateSlug]);

        return [
            'corporate' => $corporate
        ];
    }
}
