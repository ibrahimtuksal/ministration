<?php

namespace App\Controller\Admin;

use App\Entity\General;
use App\Form\GeneralFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/general")
 * Class GeneralController
 * @package App\Controller\Admin
 */
class GeneralController extends AbstractController
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
     * @Route("/", name="admin_general")
     */
    public function index(Request $request): Response
    {
        $general = $this->entityManager->getRepository(General::class)->find(General::GLOBAL);
        $form = $this->createForm(GeneralFormType::class, $general);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            $this->addFlash('success', 'Değişiklikler Kaydedildi.');
            return $this->redirectToRoute('admin_general');
        }

        return $this->render('admin/general/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
