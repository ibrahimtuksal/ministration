<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\CityWithCategory;
use App\Entity\District;
use App\Entity\Neighborhood;
use App\Entity\Phone;
use App\Generator\GlobalGenerator;
use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/hizmet/{categorySlug}", name="category")
     * @Template()
     * @param string $categorySlug
     */
    public function index(string $categorySlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        if ($request->query->get('ads') == "1"){
            $userLogService->userLogControl($request);
            return $this->redirect('tel:05061614265');
        }
        if ($globalGenerator->general->getIsReturnPhoneForAds() && $request->query->get('ads') == "1"){
            $userLogService->userLogControl($request);
            return $this->redirect('tel:05061614265');
        }
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        /** @var City $city */
        $city = $this->em->getRepository(City::class)->findBy(['is_active' => true]);

        return [
            'category' => $category,
            'city' => $city
        ];
    }

    /**
     * @Route("/sehir/{citySlug}/{categorySlug}", name="category_city")
     * @Template()
     * @param string $citySlug
     * @param string $categorySlug
     */
    public function city(string $citySlug, string $categorySlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        if ($globalGenerator->general->getIsReturnPhoneForAds() && $request->query->get('ads') == "1"){
            $userLogService->userLogControl($request);
            return $this->redirect('tel:05061614265');
        }
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        /** @var City $city */
        $city = $this->em->getRepository(City::class)->findBy(['slug' => $citySlug]);

        $cityWithCategory = $this->em->getRepository(CityWithCategory::class)->findOneBy(['city' => $city, 'Category' => $category]);
        return [
            'cityWithCategory' => $cityWithCategory
        ];
    }

    /**
     * @Route("/ilce/{districtSlug}/{categorySlug}", name="category_district")
     * @Template()
     * @param string $districtSlug
     * @param string $categorySlug
     */
    public function district(string $districtSlug, string $categorySlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        if ($globalGenerator->general->getIsReturnPhoneForAds() && $request->query->get('ads') == "1"){
            $userLogService->userLogControl($request);
            return $this->redirect('tel:05061614265');
        }
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        /** @var District $district */
        $district = $this->em->getRepository(District::class)->findOneBy(['slug' => $districtSlug]);

        $cityWithCategory = $this->em->getRepository(CityWithCategory::class)->findOneBy(['city' => $district->getCity(), 'Category' => $category]);
        return [
            'cityWithCategory' => $cityWithCategory,
            'district' => $district
        ];
    }

    /**
     * @Route("/mahalle/{neighborhoodSlug}/{categorySlug}", name="category_neighborhood")
     * @Template()
     * @param string $neighborhoodSlug
     * @param string $categorySlug
     */
    public function neighborhood(string $neighborhoodSlug, string $categorySlug, UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        if ($globalGenerator->general->getIsReturnPhoneForAds() && $request->query->get('ads') == "1"){
            $userLogService->userLogControl($request);
            return $this->redirect('tel:05061614265');
        }
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        /** @var Neighborhood $neighborhood */
        $neighborhood = $this->em->getRepository(Neighborhood::class)->findOneBy(['slug' => $neighborhoodSlug]);

        $cityWithCategory = $this->em->getRepository(CityWithCategory::class)->findOneBy(
            [
                'city' => $neighborhood->getDistrict()->getCity(),
                'Category' => $category
            ]);

        return [
            'cityWithCategory' => $cityWithCategory,
            'neighborhood' => $neighborhood,
            'district' => $neighborhood->getDistrict()
        ];
    }
}
