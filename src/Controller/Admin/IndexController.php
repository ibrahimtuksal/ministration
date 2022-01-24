<?php

namespace App\Controller\Admin;

use App\Entity\CityWithCategory;
use App\Entity\UserComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class IndexController extends AbstractController
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
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        $cityWithCategory = $this->em->getRepository(CityWithCategory::class)->findBy(['city' => 40, 'Category' => 6]);

        return $this->render('admin/index/index.html.twig', [
            'cityWithCategory' => $cityWithCategory
        ]);
    }

    public function header(): Response
    {
        $commentCount = $this->em->getRepository(UserComment::class)->findBy(['is_active' => false]);
        return $this->render('admin/includes/header.html.twig', [
            'commentCount' => count($commentCount),
        ]);
    }
}
