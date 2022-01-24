<?php

namespace App\Controller;

use App\Entity\UserComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/yorumlar", name="comment")
     */
    public function index(): Response
    {
        /** @var UserComment $comment */
        $comment = $this->entityManager->getRepository(UserComment::class)->findBy(['is_active' => true], ['id' => 'DESC']);
        return $this->render('comment/index.html.twig', [
            'comment' => $comment,
        ]);
    }
}
