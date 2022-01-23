<?php

namespace App\Controller\Admin;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/city")
 * Class CityController
 * @package App\Controller\Admin
 */
class CityController extends AbstractController
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
     * @Route("/", name="admin_city")
     * @Template()
     */
    public function index()
    {
        $city = $this->entityManager->getRepository(City::class)->findAll();
        return [
            'citys' => $city
        ];
    }
}
