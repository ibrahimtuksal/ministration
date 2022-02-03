<?php

namespace App\Controller\Admin;

use App\Entity\Colors;
use App\Entity\Contact;
use App\Entity\ContactType;
use App\Entity\ContactTypeValue;
use App\Form\ContactFormType;
use App\Form\ContactTypeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/contact")
 * Class ContactController
 * @package App\Controller\Admin
 */
class ContactController extends AbstractController
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
     * @Route("/", name="admin_contact")
     * @Template()
     */
    public function index()
    {
        return [
            'phones' => $this->em->getRepository(Contact::class)->findAll(),
            'contactType' => $this->em->getRepository(ContactType::class)->findAll()
        ];
    }

    /**
     * @Route("/type/add", name="admin_contact_type_add")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function contactTypeAdd(Request $request)
    {
        /** @var Colors $colors */
        $colors = $this->em->getRepository(Colors::class)->findAll();
        $contactTypeValues = $this->em->getRepository(ContactTypeValue::class)->findAll();
        $contactType = new ContactType();
        $form = $this->createForm(ContactTypeFormType::class, $contactType);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           /** @var Colors $color */
           $color = $this->em->getRepository(Colors::class)->find($request->request->get('color'));
           $value = $this->em->getRepository(ContactTypeValue::class)->find($request->request->get('typeValue'));
           $contactType->setColor($color);
           $contactType->setValue($value);
           $this->em->persist($contactType);
           $this->em->flush();
           $this->addFlash('success', 'Başarıyla Eklendi');
           return $this->redirectToRoute('admin_contact');
       }

       return [
           'form' => $form->createView(),
           'colors' => $colors,
           'contactTypeValues' => $contactTypeValues
       ];
    }

    /**
     * @Route("/type/update/{contactType}", name="admin_contact_type_update")
     * @Template()
     * @param int $contactType
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function contactTypeUpdate(int $contactType, Request $request)
    {
        /** @var Colors $colors */
        $colors = $this->em->getRepository(Colors::class)->findAll();
        $contactTypeValues = $this->em->getRepository(ContactTypeValue::class)->findAll();
        $contactType = $this->em->getRepository(ContactType::class)->find($contactType);
        $form = $this->createForm(ContactTypeFormType::class, $contactType);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           /** @var Colors $color */
           $color = $this->em->getRepository(Colors::class)->find($request->request->get('color'));
           $value = $this->em->getRepository(ContactTypeValue::class)->find($request->request->get('typeValue'));
           $contactType->setValue($value);
           $contactType->setColor($color);
           $this->em->flush();
           $this->addFlash('success', 'Başarıyla Güncellendi');
           return $this->redirectToRoute('admin_contact');
       }

       return [
           'form' => $form->createView(),
           'contactType' => $contactType,
           'colors' => $colors,
           'contactTypeValues' => $contactTypeValues
       ];
    }

    /**
     * @Route("/type/delete/{contactType}", name="admin_contact_type_delete")
     * @param int $contactType
     * @return RedirectResponse
     */
    public function contactTypeDelete(int $contactType)
    {
        $contactType = $this->em->getRepository(ContactType::class)->find($contactType);
        $this->em->remove($contactType);
        $this->em->flush();
        $this->addFlash('success', 'Başarıyla Silindi');
        return $this->redirectToRoute('admin_contact');
    }

    /**
     * @Route("/add", name="admin_contact_add")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function add(Request $request)
    {
        /** @var ContactType $types */
        $types = $this->em->getRepository(ContactType::class)->findAll();
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ){
            /** @var ContactType $type */
            $type = $this->em->getRepository(ContactType::class)->find($request->request->get('type'));
            $contact->setType($type);
            $contact->setIsIndex(false);
            $contact->setIsFixed(false);
            $contact->setIsSidebar(false);
            $this->em->persist($contact);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi');
            return $this->redirectToRoute('admin_contact');
        }

        return [
            'types' => $types,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/update/{contact}", name="admin_contact_update")
     * @Template()
     * @param Request $request
     * @param int $contact
     * @return array|RedirectResponse
     */
    public function update(int $contact, Request $request)
    {
        /** @var ContactType $types */
        $types = $this->em->getRepository(ContactType::class)->findAll();
        $contact = $this->em->getRepository(Contact::class)->find($contact);
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ){
            /** @var ContactType $type */
            $type = $this->em->getRepository(ContactType::class)->find($request->request->get('type'));
            $contact->setType($type);
            $this->em->flush();
            $this->addFlash('success', 'Başarıyla Eklendi');
            return $this->redirectToRoute('admin_contact');
        }

        return [
            'types' => $types,
            'contact' => $contact,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/index/{contact}", name="admin_contact_index")
     * @param string $contact
     * @return RedirectResponse
     */
    public function contactIndex($contact)
    {
        $contact = $this->em->getRepository(Contact::class)->find($contact);
        $contact->setIsIndex($contact->getIsIndex() ? FALSE : TRUE);
        $this->em->flush();
        $this->addFlash('success', 'Başarıyla Güncellendi');
        return $this->redirectToRoute('admin_contact');
    }

    /**
     * @Route("/index/sidebar/{contact}", name="admin_contact_sidebar_index")
     * @param string $contact
     * @return RedirectResponse
     */
    public function contactSidebarIndex($contact)
    {
        $contact = $this->em->getRepository(Contact::class)->find($contact);
        $contact->setIsSidebar($contact->getIsSidebar() ? FALSE : TRUE);
        $this->em->flush();
        $this->addFlash('success', 'Başarıyla Güncellendi');
        return $this->redirectToRoute('admin_contact');
    }

    /**
     * @Route("/fixed/{contact}", name="admin_contact_fixed")
     * @param string $contact
     * @return RedirectResponse
     */
    public function contactFixed($contact)
    {
        $fixedContacts = $this->em->getRepository(Contact::class)->findOneBy(['is_fixed' => true]);

        if ($fixedContacts instanceof Contact){
            $fixedContacts->setIsFixed(false);
        }

        $contact = $this->em->getRepository(Contact::class)->find($contact);
        $contact->setIsFixed(true);
        $this->em->flush();
        $this->addFlash('success', 'Başarıyla Güncellendi');
        return $this->redirectToRoute('admin_contact');
    }

    /**
     * @Route("/delete/{contact}", name="admin_contact_delete")
     * @param int $contact
     * @return RedirectResponse
     */
    public function delete(int $contact)
    {
        $contact = $this->em->getRepository(Contact::class)->find($contact);
        $this->em->remove($contact);
        $this->em->flush();
        $this->addFlash('success', 'Başarıyla Silindi');
        return $this->redirectToRoute('admin_contact');
    }
}
