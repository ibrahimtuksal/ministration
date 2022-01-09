<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Category;
use App\Form\BrandFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class BrandController
 * @package App\Controller\Admin
 * @Route("/admin/brand")
 */
class BrandController extends AbstractController
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
     * @Route("/", name="admin_brand")
     * @Template()
     */
    public function index()
    {
        $brands = $this->em->getRepository(Brand::class)->findAll();

        return [
            'brands' => $brands
        ];
    }

    /**
     * @Route("/add", name="admin_brand_add")
     * @Template()
     */
    public function add(Request $request)
    {
        $categorys = $this->em->getRepository(Category::class)->findAll();
        $brand = new Brand();
        $form = $this->createForm(BrandFormType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $category = $request->request->get('category');
            /** @var Category $category */
            $category = $this->em->getRepository(Category::class)->find($category);
            $brand->setSlug($this->slugger->slug($form->get('title')->getData()));

            $brochureFile  = $form->get('photo')->getData();
            $dir = $this->getParameter('uploads_dir');
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);

            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
            try {
                $brochureFile->move(
                    $dir,
                    $newFilename
                );
            } catch (FileException $e) {
                $this->addFlash('error', 'Hata');
                throw new UnprocessableEntityHttpException('Dosya Yüklenemedi');
            }
            $brand->setPhoto("/uploads/".$newFilename);
            $brand->setCategory($category);
            $this->em->persist($brand);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi');
            return $this->redirectToRoute('admin_brand');
        }

        return [
            'form' => $form->createView(),
            'categorys' => $categorys
        ];
    }

    /**
     * @Route("/update/{brand}", name="admin_brand_update")
     * @Template()
     * @param int $brand
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function update(int $brand, Request $request)
    {
        /** @var Category $categorys */
        $categorys = $this->em->getRepository(Category::class)->findAll();

        /** @var Brand $brand */
        $brand = $this->em->getRepository(Brand::class)->find($brand);

        $form = $this->createForm(BrandFormType::class, $brand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $category = $request->request->get('category');
            /** @var Category $category */
            $category = $this->em->getRepository(Category::class)->find($category);
            $brand->setSlug($this->slugger->slug($form->get('title')->getData()));

            if ($brochureFile = $form->get('photo')->getData()){

                $dir = $this->getParameter('uploads_dir');
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $dir,
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Hata');
                    throw new UnprocessableEntityHttpException('Dosya Yüklenemedi');
                }
                $brand->setPhoto("/uploads/".$newFilename);
            }

            $brand->setCategory($category);
            $this->em->persist($brand);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi');
            return $this->redirectToRoute('admin_brand');
        }

        return [
          'form' => $form->createView(),
          'categorys' => $categorys,
          'brand' => $brand
        ];
    }

    /**
     * @Route("/delete/{brand}", name="admin_brand_delete")
     * @param int $brand
     * @return RedirectResponse
     */
    public function delete(int $brand)
    {
        $brand = $this->em->getRepository(Brand::class)->find($brand);
        $this->em->remove($brand);
        $this->em->flush();
        $this->addFlash('success',"Marka Silindi");
        return $this->redirectToRoute('admin_brand');
    }
}
