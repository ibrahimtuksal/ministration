<?php

namespace App\Controller\Admin;

use App\Entity\Corporate;
use App\Form\CorporateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class CorporateController
 * @package App\Controller\Admin
 * @Route("/admin/corporate")
 */
class CorporateController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->em = $entityManager;
        $this->slugger = $slugger;
    }

    /**
     * @Route("/", name="admin_corporate")
     * @Template()
     */
    public function index()
    {
        $corporates = $this->em->getRepository(Corporate::class)->findBy([],['is_index' => 'DESC']);
        return [
            'corporates' => $corporates
        ];
    }

    /**
     * @Route("/add", name="admin_corporate_add")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function add(Request $request)
    {
        $corporate = new Corporate();
        $form = $this->createForm(CorporateFormType::class, $corporate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $corporate->setSlug($this->slugger->slug($form->get('title')->getData()));
            $this->em->persist($corporate);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi');
            return $this->redirectToRoute('admin_corporate');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/update/{corporate}", name="admin_corporate_update")
     * @Template()
     * @param int $corporate
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function update(int $corporate, Request $request)
    {
        $corporate = $this->em->getRepository(Corporate::class)->find($corporate);
        $form = $this->createForm(CorporateFormType::class, $corporate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $corporate->setSlug($this->slugger->slug($form->get('title')->getData()));
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Düzenlendi');
            return $this->redirectToRoute('admin_corporate');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/delete/{corporate}", name="admin_corporate_delete")
     * @param int $corporate
     * @return RedirectResponse
     */
    public function delete(int $corporate)
    {
        $corporate = $this->em->getRepository(Corporate::class)->find($corporate);
        $this->em->remove($corporate);
        $this->em->flush();
        $this->addFlash('success',"Kurumsal Silindi");
        return $this->redirectToRoute('admin_corporate');
    }

    /**
     * @Route("/operation/{corporate}", name="admin_corporate_operation")
     * @param int $corporate
     * @return RedirectResponse
     */
    public function operation(int $corporate)
    {
        $indexCorporate = $this->em->getRepository(Corporate::class)->findOneBy(['is_index' => true]);
        if ($indexCorporate instanceof Corporate) {
            $indexCorporate->setIsIndex(false);
        }
        $corporate = $this->em->getRepository(Corporate::class)->find($corporate);
        $corporate->setIsIndex(true);

        $this->em->flush();

        return $this->redirectToRoute('admin_corporate');
    }

}
