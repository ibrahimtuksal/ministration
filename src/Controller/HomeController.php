<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\Corporate;
use App\Entity\Phone;
use App\Entity\Slider;
use App\Entity\UserComment;
use App\Entity\UserLog;
use App\Generator\GlobalGenerator;
use App\Service\SmsService;
use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
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
     * @Route("/", name="home")
     * @Template()
     */
    public function index(UserLogService $userLogService, Request $request, GlobalGenerator $globalGenerator)
    {
        /** @var Corporate $corporateIndex */
        $corporateIndex = $this->em->getRepository(Corporate::class)->findOneBy(['is_index' => true]);
        /** @var Slider $sliders */
        $sliders = $this->em->getRepository(Slider::class)->findBy([], ['queue' => 'ASC']);
        /** @var Phone $phone */
        $phone = $this->em->getRepository(Phone::class)->find(1);
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findAll();

        $blogs = $this->em->getRepository(Blog::class)->findAll();

        return [
            'sliders' => $sliders,
            'phone' => $phone,
            'corporateIndex' => $corporateIndex,
            'categorys' => $category,
            'blogs' => $blogs
        ];
    }


    /**
     * @Route("/check-ads", name="check_ads")
     */
    public function checkAds(UserLogService $userLogService, Request $request, SmsService $smsService): Response
    {
        $userLogService->userLogControl($request);
        /** @var Corporate $corporateIndex */
        $corporateIndex = $this->em->getRepository(Corporate::class)->findOneBy(['is_index' => true]);
        /** @var Slider $sliders */
        $sliders = $this->em->getRepository(Slider::class)->findBy([], ['queue' => 'ASC']);
        /** @var Phone $phone */
        $phone = $this->em->getRepository(Phone::class)->find(1);
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->findAll();

        $blogs = $this->em->getRepository(Blog::class)->findAll();

        return $this->render('home/index.html.twig',[
            'sliders' => $sliders,
            'phone' => $phone,
            'corporateIndex' => $corporateIndex,
            'categorys' => $category,
            'blogs' => $blogs
        ]);
    }
}
