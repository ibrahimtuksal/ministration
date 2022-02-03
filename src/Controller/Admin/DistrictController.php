<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/district")
 * Class DistrictController
 * @package App\Controller\Admin
 */
class DistrictController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="admin_district")
     * @Template()
     * @return array
     */
    public function index(): array
    {
        return [
            'activeCity' => $this->em->getRepository(City::class)->findBy(['is_active' => true]),
            'contacts' => $this->em->getRepository(Contact::class)->getPhones()
        ];
    }
}
