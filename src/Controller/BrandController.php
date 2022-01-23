<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BrandController
 * @package App\Controller
 */
class BrandController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/{brandSlug}/{categorySlug}", name="brand")
     * @param string $categorySlug
     * @param string $brandSlug
     * @return array
     */
    public function index(string $brandSlug, string $categorySlug)
    {

        return [];
    }
}
