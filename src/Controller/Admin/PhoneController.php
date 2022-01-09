<?php

namespace App\Controller\Admin;

use App\Entity\Phone;
use App\Form\PhoneFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhoneController
 * @package App\Controller\Admin
 * @Route("/admin/phone")
 */
class PhoneController extends AbstractController
{
    CONST PHONE_ID = 1;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="admin_phone")
     * @Template()
     */
    public function index(Request $request)
    {
        $phone = $this->em->getRepository(Phone::class)->find(self::PHONE_ID);
        $form = $this->createForm(PhoneFormType::class, $phone);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Kaydedildi.');
            return $this->redirectToRoute('admin_phone');
        }
        return [
            'form' => $form->createView()
        ];
    }
}
