<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EventRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Event::class);
        $this->em = $em;
    }

    public function findAll($page = 1, $max = 10)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('e')
            ->from('App:Event', 'e')
            ->setFirstResult(($page - 1) * $max)
            ->setMaxResults($max);

        return new Paginator($qb);
    }

    public function findEventsByCategory(int $category)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('e')
            ->from(Event::class, 'e')
            ->where('e.category = :category')
            ->setParameter('category', $category);

        return $qb
            ->getQuery()
            ->getResult();
    }
}
