<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class EventRepository
 * @package App\Repository
 */
class EventRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Event::class);
        $this->em = $em;
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
