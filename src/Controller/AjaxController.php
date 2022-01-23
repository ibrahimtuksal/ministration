<?php

namespace App\Controller;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ajax")
 * Class AjaxController
 * @package App\Controller
 */
class AjaxController extends AbstractController
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
     * @Route("/admin/city/change", name="ajax_admin_city_change", methods={"POST"})
     * @IsGranted("ROLE_ADMIN", message="404 not found")
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        /** @var City $city */
        $city = $this->entityManager->getRepository(City::class)->find($request->request->get('cityId'));

        $city->setIsActive($city->getIsActive() ? FALSE : TRUE);
        $this->entityManager->flush();

        return new JsonResponse(true);
    }
}
