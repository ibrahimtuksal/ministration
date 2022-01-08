<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/admin/blog", name="admin_blog")
     */
    public function index(): Response
    {
        return $this->render('admin/blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
}
