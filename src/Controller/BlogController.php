<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
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
     * @Route("/markalar/{blogSlug}", name="blog")
     * @param string $blogSlug
     * @return Response
     */
    public function index(string $blogSlug): Response
    {
        /** @var Blog $blog */
        $blog = $this->entityManager->getRepository(Blog::class)->findOneBy(['slug' => $blogSlug]);

        return $this->render('blog/index.html.twig', [
            'blog' => $blog
        ]);
    }

    /**
     * @Route("/deneme", name="deneme")
     */
    public function deneme(UserLogService $userLogService, Request $request){
        $userLogService->userLogControl($request);
        return $this->redirect('tel:05413779956');
    }
}
