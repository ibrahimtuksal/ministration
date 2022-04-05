<?php

namespace App\Controller;

use App\Entity\UserComment;
use App\Generator\GlobalGenerator;
use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator): Response
    {
        if ($globalGenerator->general->getIsReturnPhoneForAds() && $request->query->get('ads') == "1"){
            $userLogService->userLogControl($request);
            return $this->redirect('tel:05061614265');
        }
        /** @var UserComment $comment */
        $comment = $this->entityManager->getRepository(UserComment::class)->findBy(['is_active' => true], ['id' => 'DESC']);
        return $this->render('comment/index.html.twig', [
            'comment' => $comment,
        ]);
    }
}
