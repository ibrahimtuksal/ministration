<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SliderController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class SliderController extends AbstractController
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
     * @Route("/admin/slider", name="admin_slider")
     */
    public function index(): Response
    {
        return $this->render('admin/slider/index.html.twig', [
            'controller_name' => 'SliderController',
        ]);
    }
}
