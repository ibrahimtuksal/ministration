<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use App\Form\SliderFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class SliderController
 * @package App\Controller\Admin
 * @Route("/admin/slider")
 */
class SliderController extends AbstractController
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
     * @Route("/", name="admin_slider")
     * @Template()
     */
    public function index()
    {
        $sliders = $this->em->getRepository(Slider::class)->findAll();

        return [
          'sliders' => $sliders
        ];
    }

    /**
     * @Route("/add", name="admin_slider_add")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function add(Request $request)
    {
        $slider = new Slider();
        $form = $this->createForm(SliderFormType::class, $slider);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
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
                $slider->setPhoto("/uploads/".$newFilename);
            }

            $this->em->persist($slider);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi.');
            return $this->redirectToRoute('admin_slider');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/update/{slider}", name="admin_slider_update")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function update(int $slider, Request $request)
    {
        $slider = $this->em->getRepository(Slider::class)->find($slider);
        $form = $this->createForm(SliderFormType::class, $slider);
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
                $slider->setPhoto("/uploads/".$newFilename);
            }

            $this->em->persist($slider);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi.');
            return $this->redirectToRoute('admin_category');
        }
        return [
            'form' => $form->createView(),
            'slider' => $slider
        ];
    }

    /**
     * @Route("/delete/{slider}", name="admin_slider_delete")
     * @param int $slider
     * @return RedirectResponse
     */
    public function delete(int $slider)
    {
        $slider = $this->em->getRepository(Slider::class)->find($slider);
        $this->em->remove($slider);
        $this->em->flush();
        $this->addFlash('success',"Slider Silindi");
        return $this->redirectToRoute('admin_slider');
    }


}
