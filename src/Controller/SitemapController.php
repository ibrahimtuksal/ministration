<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\BrandWithCity;
use App\Entity\Category;
use App\Entity\CityWithCategory;
use App\Entity\District;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request): Response
    {

        $url = [];
        $categoryWithCity = $this->em->getRepository(CityWithCategory::class)->findAll();
        $categories = $this->em->getRepository(Category::class)->findAll();
        $brandWithCity = $this->em->getRepository(BrandWithCity::class)->findAll();
        /** @var Category $c */
        foreach ($categories as $c){
            $generateUrl = $this->get('router')->generate('category', ['categorySlug' => $c->getSlug()]);
            $url[] = ['loc' => $generateUrl, 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => '2021'];
        }
        /** @var CityWithCategory $cityCategory */
        foreach ($categoryWithCity as $cityCategory){
            $generateUrl = $this->get('router')->generate('category_city',
                [
                    'citySlug' => $cityCategory->getCity()->getSlug(),
                    'categorySlug' => $cityCategory->getCategory()->getSlug()
                ]
            );
            $url[] = ['loc' => $generateUrl, 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => '2021'];
        }

        /** @var CityWithCategory $cityCategory */
        foreach ($categoryWithCity as $cityCategory){
            foreach ($cityCategory->getCity()->getDistricts() as $district){
                $generateUrl = $this->get('router')->generate('category_district',
                    [
                        'districtSlug' => $district->getSlug(),
                        'categorySlug' => $cityCategory->getCategory()->getSlug()
                    ]
                );
                $url[] = ['loc' => $generateUrl, 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => '2021'];
            }
        }

        /** @var CityWithCategory $cityCategory */
        foreach ($categoryWithCity as $cityCategory){
            foreach ($cityCategory->getCity()->getDistricts() as $district){
                foreach ($district->getNeighborhoods() as $neighborbood){
                    $generateUrl = $this->get('router')->generate('category_neighborhood',
                        [
                            'neighborhoodSlug' => $neighborbood->getSlug(),
                            'categorySlug' => $cityCategory->getCategory()->getSlug()
                        ]
                    );
                    $url[] = ['loc' => $generateUrl, 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => '2021'];
                }
            }
        }
        //////////////////////////////////////////////////////////////////////////////////////////////
        foreach ($categories as $category){
            foreach ($category->getBrands() as $brand){
                $generateUrl = $this->get('router')->generate('brand',
                    [
                        'brandSlug' => $brand->getSlug(),
                        'categorySlug' => $category->getSlug()
                    ]
                );
                $url[] = ['loc' => $generateUrl, 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => '2021'];
            }
        }
        /** @var BrandWithCity $b */
        foreach ($brandWithCity as $b){
            $generateUrl = $this->get('router')->generate('brand_city',
                [
                    'brandSlug' => $b->getBrand()->getSlug(),
                    'citySlug' => $b->getCity()->getSlug(),
                    'categorySlug' => $b->getBrand()->getCategory()
                ]
            );
            $url[] = ['loc' => $generateUrl, 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => '2021'];
        }

        /** @var BrandWithCity $b */
        foreach ($brandWithCity as $b){
            foreach ($b->getCity()->getDistricts() as $district){
                $generateUrl = $this->get('router')->generate('brand_district',
                    [
                        'brandSlug' => $b->getBrand()->getSlug(),
                        'districtSlug' => $district->getSlug(),
                        'categorySlug' => $b->getBrand()->getCategory()
                    ]
                );
                $url[] = ['loc' => $generateUrl, 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => '2021'];
            }
        }

        /** @var BrandWithCity $b */
        foreach ($brandWithCity as $b){
            foreach ($b->getCity()->getDistricts() as $district){
                foreach ($district->getNeighborhoods() as $neighborhood){
                    $generateUrl = $this->get('router')->generate('brand_neighborhood',
                        [
                            'brandSlug' => $b->getBrand()->getSlug(),
                            'neighborhoodSlug' => $neighborhood->getSlug(),
                            'categorySlug' => $b->getBrand()->getCategory()
                        ]
                    );
                    $url[] = ['loc' => $generateUrl, 'changefreq' => 'weekly', 'priority' => '1.0', 'lastmod' => '2021'];
                }
            }
        }
        $response = new Response(
            $this->renderView('sitemap/index.xml.twig', array('urls' => $url,
                'hostname' => "https://".$request->getHttpHost())),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }

    /**
     * @Route("/robots.txt", name="robots")
     */
    public function robots()
    {

        return $this->render('robots.txt');
    }
}
