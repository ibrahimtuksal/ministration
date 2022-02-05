<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\BrandWithCity;
use App\Entity\Category;
use App\Entity\City;
use App\Form\BrandFormType;
use App\Form\BrandZoneContentFormType;
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

        $categorys = $this->em->getRepository(Category::class)->findAll();
        return [
            'brands' => $brands,
            'categorys' => $categorys
        ];
    }

    /**
     * @Route("/add", name="admin_brand_add")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
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
            $brand->setPhoto("/public/uploads/".$newFilename);
            $brand->setCategory($category);
            $brand->setIsCity(false);
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
                $brand->setPhoto("/public/uploads/".$newFilename);
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

    /**
     * @Route("/create-city/{brand}", name="admin_brand_city_create")
     * @param int $brand
     * @return RedirectResponse
     */
    public function CityWithBrandCreate(int $brand)
    {
        /** @var Brand $brand */
        $brand = $this->em->getRepository(Brand::class)->find($brand);

        $city = $this->em->getRepository(City::class)->findAll();

        foreach ($city as $value){
            $brandWithCity = new BrandWithCity();
            $brandWithCity->setBrand($brand);
            $brandWithCity->setCity($value);
            $this->em->persist($brandWithCity);
        }
        $brand->setIsCity(true);
        $this->em->flush();

        $this->addFlash('success', 'Şehirler oluşturuldu');
        return $this->redirectToRoute('admin_brand');
    }

    /**
     * @Route("/transfer", name="admin_brand_transfer", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function transfer(Request $request)
    {
        $quoted = $this->em->getRepository(Category::class)->find($request->request->get('one'));
        $transmitted = $this->em->getRepository(Category::class)->find($request->request->get('two'));

        $brands = $this->em->getRepository(Brand::class)->findBy(['category' => $quoted]);
        /** @var Brand $brand */
        foreach ($brands as $brand){
            $transferBrand = new Brand();
            $transferBrand->setIsCity(false);
            $transferBrand->setCategory($transmitted);
            $transferBrand->setTitle($brand->getTitle());
            $transferBrand->setSlug($brand->getSlug());
            $transferBrand->setPhoto($brand->getPhoto());
            $this->em->persist($transferBrand);
        }
        $this->em->flush();
        $this->addFlash('success', 'Transfer İşlemi Tamamlandı');
        return $this->redirectToRoute('admin_brand');
    }

    /**
     * @Route("/content/{brand}", name="admin_brand_content")
     * @Template()
     * @param int $brand
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function content(int $brand, Request $request)
    {
        $brand = $this->em->getRepository(Brand::class)->find($brand);
        $form = $this->createForm(BrandZoneContentFormType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Kaydedildi');
            return $this->redirectToRoute('admin_brand');
        }

        return ['form' => $form->createView()];
    }

}
