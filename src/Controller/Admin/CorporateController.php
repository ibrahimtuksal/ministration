<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CorporateController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class CorporateController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/admin/corporate", name="admin_corporate")
     */
    public function index(): Response
    {
        return $this->render('admin/corporate/index.html.twig', [
            'controller_name' => 'CorporateController',
        ]);
    }
}
