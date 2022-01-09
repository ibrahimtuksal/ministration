<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Form\BlogFormType;
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
 * Class BlogController
 * @package App\Controller\Admin
 * @Route("/admin/blog")
 */
class BlogController extends AbstractController
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
     * @Route("/", name="admin_blog")
     * @Template()
     */
    public function index()
    {
        $blog = $this->em->getRepository(Blog::class)->findAll();
        return [
            'blogs' => $blog
        ];
    }

    /**
     * @Route("/add", name="admin_blog_add")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function add(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm(BlogFormType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $blog->setSlug($this->slugger->slug($form->get('title')->getData()));

            if ($brochureFile  = $form->get('photo')->getData()){
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
                $blog->setPhoto("/uploads/".$newFilename);
            }

            $this->em->persist($blog);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi.');
            return $this->redirectToRoute('admin_blog');
        }

        return [
            'form' => $form->createView()
        ];
    }


    /**
     * @Route("/update/{blog}", name="admin_blog_update")
     * @Template()
     * @param int $blog
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function update(int $blog, Request $request)
    {
        $blog = $this->em->getRepository(Blog::class)->find($blog);
        $form = $this->createForm(BlogFormType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $blog->setSlug($this->slugger->slug($form->get('title')->getData()));

            if ($brochureFile  = $form->get('photo')->getData()){
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
                $blog->setPhoto("/uploads/".$newFilename);
            }

            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi.');
            return $this->redirectToRoute('admin_blog');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/delete/{blog}", name="admin_blog_delete")
     * @param int $blog
     * @return RedirectResponse
     */
    public function delete(int $blog)
    {
        $blog = $this->em->getRepository(Blog::class)->find($blog);
        $this->em->remove($blog);
        $this->em->flush();
        $this->addFlash('success',"Marka Silindi");
        return $this->redirectToRoute('admin_blog');
    }
}
