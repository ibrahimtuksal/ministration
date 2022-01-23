<?php

namespace App\Generator;

use App\Entity\General;
use Doctrine\ORM\EntityManagerInterface;

class GlobalGenerator
{

    /**
     * @var string $name
     */
    public string $name;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->name = $this->setName();
    }

    private function setName()
    {
        $general = $this->em->getRepository(General::class)->find(General::GLOBAL);

        return $general->getName();
    }

}