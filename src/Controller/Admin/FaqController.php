<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FaqController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("/admin/faq", name="admin_faq")
     */
    public function index(): Response
    {
        return $this->render('admin/faq/index.html.twig', [
            'controller_name' => 'FaqController',
        ]);
    }
}
