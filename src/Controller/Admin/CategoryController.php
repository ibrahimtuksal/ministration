<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\CityWithCategory;
use App\Form\CategoryFormType;
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
 * Class CategoryController
 * @package App\Controller\Admin
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
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
     * @Route("/", name="admin_category")
     * @Template()
     */
    public function index(): array
    {
        $category = $this->em->getRepository(Category::class)->findAll();
        return [
            'categorys' => $category
        ];
    }

    /**
     * @Route("/add", name="admin_category_add")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function add(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $category->setSlug($this->slugger->slug($form->get('title')->getData()));

            $brochureFile  = $form->get('photo')->getData();
            if ($brochureFile) {
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
                $category->setPhoto("/public/uploads/".$newFilename);
            }

            $category->setIsCity(false);
            $this->em->persist($category);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi.');
            return $this->redirectToRoute('admin_category');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/update/{category}", name="admin_category_update")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function update(int $category, Request $request)
    {
        $category = $this->em->getRepository(Category::class)->find($category);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
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
                $category->setPhoto("/public/uploads/".$newFilename);
            }

            $category->setSlug($this->slugger->slug($form->get('title')->getData()));
            $this->em->persist($category);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi.');
            return $this->redirectToRoute('admin_category');
        }
        return [
            'form' => $form->createView(),
            'category' => $category
        ];
    }

    /**
     * @Route("/delete/{category}", name="admin_category_delete")
     * @param int $category
     * @return RedirectResponse
     */
    public function delete(int $category)
    {
        $category = $this->em->getRepository(Category::class)->find($category);
        $this->em->remove($category);
        $this->em->flush();
        $this->addFlash('success',"Kategori Silindi");
        return $this->redirectToRoute('admin_category');
    }

    /**
     * @Route("/create-city/{category}", name="admin_category_city_create")
     * @param int $category
     * @return RedirectResponse
     */
    public function CityWithCategoryCreate(int $category)
    {
        /** @var Category $category */
        $category = $this->em->getRepository(Category::class)->find($category);

        $city = $this->em->getRepository(City::class)->findAll();

        foreach ($city as $value){
            $cityWithCategory = new CityWithCategory();
            $cityWithCategory->setCategory($category);
            $cityWithCategory->setCity($value);
            $this->em->persist($cityWithCategory);
        }
        $category->setIsCity(true);
        $this->em->flush();

        $this->addFlash('success', 'Şehirler oluşturuldu');
        return $this->redirectToRoute('admin_category');
    }
}
