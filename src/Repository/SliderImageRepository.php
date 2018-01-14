<?php

namespace App\Repository;

use App\Entity\SliderImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SliderImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SliderImage::class);
    }

    public function getImagesByPosition()
    {
        return $this->createQueryBuilder('img')
            ->orderBy('img.position', 'ASC')
            ->getQuery()
            ->getResult();
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
