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

    public function findAllByBeginningDateTime($page = 1, $max = 10)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('e')
            ->from('App:Event', 'e')
            ->orderBy('e.beginningDateTime', 'ASC')
            ->setFirstResult(($page - 1) * $max)
            ->setMaxResults($max);

        return new Paginator($qb);
    }

    public function findAllFuturByBeginningDateTime()
    {
        $todayDate = new \DateTime('now');
        $todayDate = $todayDate->format('Y-m-d H:i:s');
        $qb = $this->em->createQueryBuilder();

        return $qb->select('e')
            ->from('App:Event', 'e')
            ->where('e.beginningDateTime > :today')->setParameter('today', $todayDate)
            ->orderBy('e.beginningDateTime', 'ASC')
            ->getQuery()
            ->getResult();
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
