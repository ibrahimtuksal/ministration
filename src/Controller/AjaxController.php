<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\UserComment;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    /**
     * @Route("/user/comment", name="ajax_user_comment", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function userComment(Request $request)
    {

        if ($request->request->get('sendComment') == "true"){
            $userComment = new UserComment();
            $userComment->setIsActive(false);
            $userComment->setName($request->request->get('name'));
            $userComment->setPhone($request->request->get('phone'));
            $userComment->setComment($request->request->get('comment'));
            $userComment->setCreatedAt(new \DateTime());
            $this->entityManager->persist($userComment);
            $this->entityManager->flush();
            $this->addFlash('success', "Yorumunuz Başarıyla Eklendi");
            return $this->redirect($request->headers->get('referer'));
        }
        $this->addFlash('error', "Bir şeyler ters gitti");
        return $this->redirect($request->headers->get('referer'));
    }
}
