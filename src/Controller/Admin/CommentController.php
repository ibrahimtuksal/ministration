<?php

namespace App\Controller\Admin;

use App\Entity\UserComment;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/comments")
 * Class CommentController
 * @package App\Controller\Admin
 */
class CommentController extends AbstractController
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
     * @Route("/", name="admin_comment")
     * @Template()
     */
    public function index()
    {
        $comments = $this->entityManager->getRepository(UserComment::class)->findAll();

        return [
            'comments' => $comments,
        ];
    }

    /**
     * @Route("/see/{comment}", name="admin_comment_see")
     * @Template()
     * @param int $comment
     * @return array
     */
    public function comment(int $comment)
    {
        $comment = $this->entityManager->getRepository(UserComment::class)->find($comment);

        return [
            'comment' => $comment
        ];
    }

    /**
     * @Route("/confirm/{comment}", name="admin_comment_confirm")
     * @param int $comment
     * @return RedirectResponse
     */
    public function confirm(int $comment)
    {
        $comment = $this->entityManager->getRepository(UserComment::class)->find($comment);
        $comment->setIsActive(true);
        $this->entityManager->flush();
        $this->addFlash('success', 'Yorum Yayına Alındı');
        return $this->redirectToRoute('admin_comment');
    }

    /**
     * @Route("/delete/{comment}", name="admin_comment_delete")
     * @param int $comment
     * @return RedirectResponse
     */
    public function delete(int $comment)
    {
        $comment = $this->entityManager->getRepository(UserComment::class)->find($comment);
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
        $this->addFlash('success', 'Yorum Kaldırıldı');
        return $this->redirectToRoute('admin_comment');
    }
}
