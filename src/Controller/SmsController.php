<?php

namespace App\Controller;

use App\Service\UserLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractController
{

    private EntityManagerInterface $em;
    private UserLogService $userLogService;

    public function __construct(EntityManagerInterface $em, UserLogService $userLogService)
    {
        $this->em = $em;
        $this->userLogService = $userLogService;
    }

    /**
     * @Route("/acil-oto-cekici", name="sms")
     */
    public function index(Request $request): Response
    {
        $this->userLogService->userLogControl($request);
        return $this->redirect('tel:+90 506 161 4265');
    }
}
