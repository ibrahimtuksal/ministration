<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhoneController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class PhoneController extends AbstractController
{
    /**
     * @Route("/admin/phone", name="admin_phone")
     */
    public function index(): Response
    {
        return $this->render('admin/phone/index.html.twig', [
            'controller_name' => 'PhoneController',
        ]);
    }
}
