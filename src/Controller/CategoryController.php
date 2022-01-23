<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 */
class CategoryController extends AbstractController
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
     * @Route("/{category}", name="category")
     * @Template()
     */
    public function index(int $category)
    {
        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['slug' => $category]);

        /** @var Brand $brands */
        $brands = $this->entityManager->getRepository(Brand::class)->findBy(['category' => $category]);

        return [
            'brands' => $brands
        ];
    }
}
