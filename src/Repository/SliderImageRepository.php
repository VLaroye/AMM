<?php

namespace App\Repository;

use App\Entity\SliderImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SliderImageRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, SliderImage::class);
        $this->em = $em;
    }

    public function getImagesByPosition($page = 1, $max = 10)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('img')
            ->from('App:SliderImage', 'img')
            ->orderBy('img.position', 'ASC')
            ->setFirstResult(($page - 1) * $max)
            ->setMaxResults($max);

        return new Paginator($qb);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('e')
            ->where('e.something = :value')->setParameter('value', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
