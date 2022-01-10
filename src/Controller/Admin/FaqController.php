<?php

namespace App\Controller\Admin;

use App\Entity\Faq;
use App\Form\FaqFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FaqController
 * @package App\Controller\Admin
 * @Route("/admin/faq")
 */
class FaqController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="admin_faq")
     * @Template()
     */
    public function index()
    {
        $faqs = $this->em->getRepository(Faq::class)->findAll();
        return[
            'faqs' => $faqs
        ];
    }

    /**
     * @Route("/add", name="admin_faq_add")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function add(Request $request)
    {
        $faq = new Faq();
        $form = $this->createForm(FaqFormType::class, $faq);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($faq);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi');
            return $this->redirectToRoute('admin_faq');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/update/{faq}", name="admin_faq_update")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function update(int $faq, Request $request)
    {
        $faq = $this->em->getRepository(Faq::class)->find($faq);
        $form = $this->createForm(FaqFormType::class, $faq);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($faq);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi');
            return $this->redirectToRoute('admin_faq');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/delete/{faq}", name="admin_faq_delete")
     * @param int $faq
     * @return RedirectResponse
     */
    public function delete(int $faq)
    {
        $faq = $this->em->getRepository(Faq::class)->find($faq);
        $this->em->remove($faq);
        $this->em->flush();
        $this->addFlash('success',"S.S.S Silindi");
        return $this->redirectToRoute('admin_faq');
    }
}
