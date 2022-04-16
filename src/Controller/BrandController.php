<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\BrandContent;
use App\Entity\BrandWithCity;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\District;
use App\Entity\Neighborhood;
use App\Entity\Phone;
use App\Generator\GlobalGenerator;
use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/marka/{brandSlug}/{categorySlug}", name="brand")
     * @Template()
     * @param string $categorySlug
     * @param string $brandSlug
     */
    public function index(string $brandSlug, string $categorySlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        /** @var Brand $brand */
        $brand = $this->em->getRepository(Brand::class)->findOneBy(['slug' => $brandSlug, 'category' => $category]);

        /** @var BrandWithCity $brandWithCity */
        $brandWithCity = $this->em->getRepository(BrandWithCity::class)->findBy(['brand' => $brand]);

        /** @var Phone $phone */
        $phone = $this->em->getRepository(Phone::class)->find(1);

        return [
            'brand' => $brand,
            'category' => $category,
            'brandWithCity' => $brandWithCity,
            'phone' => $phone
        ];
    }


    /**
     * @Route("/sehir/{citySlug}/{brandSlug}/{categorySlug}", name="brand_city")
     * @Template()
     * @param string $citySlug
     * @param string $brandSlug
     * @param string $categorySlug
     */
    public function city(string $citySlug ,string $brandSlug, string $categorySlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        /** @var Brand $brand */
        $brand = $this->em->getRepository(Brand::class)->findOneBy(['slug' => $brandSlug, 'category' => $category]);

        /** @var City $city */
        $city = $this->em->getRepository(City::class)->findOneBy(['slug' => $citySlug]);

        /** @var BrandWithCity $brandWithCity */
        $brandWithCity = $this->em->getRepository(BrandWithCity::class)->findOneBy(['city' => $city, 'brand' => $brand]);

        if (!$brandWithCity->getCity()->getIsActive())
        {
            die("404 not found");
        }

        /** @var Phone $phone */
        $phone = $this->em->getRepository(Phone::class)->find(1);

        return [
            'brand' => $brand,
            'category' => $category,
            'brandWithCity' => $brandWithCity,
            'phone' => $phone
        ];
    }

    /**
     * @Route("/ilce/{districtSlug}/{brandSlug}/{categorySlug}", name="brand_district")
     * @Template()
     * @param string $districtSlug
     * @param string $brandSlug
     * @param string $categorySlug
     */
    public function district(string $districtSlug, string $brandSlug, string $categorySlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        /** @var Brand $brand */
        $brand = $this->em->getRepository(Brand::class)->findOneBy(['slug' => $brandSlug, 'category' => $category]);

        /** @var District $district */
        $district = $this->em->getRepository(District::class)->findOneBy(['slug' => $districtSlug]);

        /** @var BrandWithCity $brandWithCity */
        $brandWithCity = $this->em->getRepository(BrandWithCity::class)->findOneBy(['city' => $district->getCity(), 'brand' => $brand]);

        return [
            'brand' => $brand,
            'category' => $category,
            'brandWithCity' => $brandWithCity,
            'district' => $district
        ];
    }


    /**
     * @Route("/mahalle/{neighborhoodSlug}/{brandSlug}/{categorySlug}", name="brand_neighborhood")
     * @Template()
     * @param string $neighborhoodSlug
     * @param string $brandSlug
     * @param string $categorySlug
     */
    public function neighborhood(string $neighborhoodSlug, string $brandSlug, string $categorySlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        /** @var Brand $brand */
        $brand = $this->em->getRepository(Brand::class)->findOneBy(['slug' => $brandSlug, 'category' => $category]);

        /** @var Neighborhood $neighborhood */
        $neighborhood = $this->em->getRepository(Neighborhood::class)->findOneBy(['slug' => $neighborhoodSlug]);

        /** @var BrandWithCity $brandWithCity */
        $brandWithCity = $this->em->getRepository(BrandWithCity::class)->findOneBy(['city' => $neighborhood->getDistrict()->getCity(), 'brand' => $brand]);

        return [
            'brand' => $brand,
            'category' => $category,
            'brandWithCity' => $brandWithCity,
            'neighborhood' => $neighborhood
        ];
    }

    /**
     * @Route("/icerik/{brandSlug}/{categorySlug}/{brandContentSlug}", name="brand_content")
     * @Template()
     * @param string $brandSlug
     * @param string $categorySlug
     * @param string $brandContentSlug
     */
    public function content(string $brandSlug, string $categorySlug, string $brandContentSlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);
        /** @var Brand $brand */
        $brand = $this->em->getRepository(Brand::class)->findOneBy(['slug' => $brandSlug, 'category' => $category]);
        /** @var BrandContent $content */
        $content = $this->em->getRepository(BrandContent::class)->findOneBy(['brand' => $brand, 'slug' => $brandContentSlug]);

        return [
            'content' => $content
        ];
    }
}
