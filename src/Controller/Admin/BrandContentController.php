<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\BrandContent;
use App\Form\BrandContentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/brand/content")
 * Class BrandContentController
 * @package App\Controller\Admin
 */
class BrandContentController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;

    public function __construct(EntityManagerInterface $em, SluggerInterface $slugger )
    {
        $this->em = $em;
        $this->slugger = $slugger;
    }

    /**
     * @Route("/add/{brand}", name="admin_brand_content_add")
     * @Template()
     * @param int $brand
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function add(int $brand, Request $request)
    {
        /** @var Brand $brand */
        $brand = $this->em->getRepository(Brand::class)->find($brand);

        $brandContent = new BrandContent();
        $form = $this->createForm(BrandContentFormType::class, $brandContent);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ){
            $brandContent->setSlug($this->slugger->slug($form->get('title')->getData()));
            $brandContent->setBrand($brand);
            $this->em->persist($brandContent);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi');
            return $this->redirectToRoute('admin_brand');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/update/{brandContent}", name="admin_brand_content_update")
     * @Template()
     * @param int $brandContent
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function update(int $brandContent, Request $request)
    {
        $brandContent = $this->em->getRepository(BrandContent::class)->find($brandContent);
        $form = $this->createForm(BrandContentFormType::class, $brandContent);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ){
            $brandContent->setSlug($this->slugger->slug($form->get('title')->getData()));
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Güncellendi');
            return $this->redirectToRoute('admin_brand');
        }

        return [
            'form' => $form->createView()
        ];
    }


    /**
     * @Route("/delete/{brandContent}", name="admin_brand_content_delete")
     * @param int $brandContent
     * @return RedirectResponse
     */
    public function delete(int $brandContent)
    {
        $brandContent = $this->em->getRepository(BrandContent::class)->find($brandContent);
        $this->em->remove($brandContent);
        $this->em->flush();
        $this->addFlash('success',"Marka İçeriği Silindi");
        return $this->redirectToRoute('admin_brand');
    }
}
