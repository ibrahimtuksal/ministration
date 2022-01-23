<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Phone;
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
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/{categorySlug}", name="category")
     * @Template()
     * @param string $categorySlug
     * @return array
     */
    public function index(string $categorySlug)
    {
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        /** @var Phone $phone */
        $phone = $this->em->getRepository(Phone::class)->find(1);

        /** @var City $city */
        $city = $this->em->getRepository(City::class)->findBy(['is_active' => true]);

        return [
            'category' => $category,
            'phone' => $phone,
            'city' => $city
        ];
    }
}
