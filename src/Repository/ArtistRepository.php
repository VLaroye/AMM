<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ArtistRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Artist::class);
        $this->em = $em;
    }

    public function findAllArtistsByPriority($page = 1, $max = 10)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('a')
            ->from('App:Artist', 'a')
            ->orderBy('a.priority', 'DESC')
            ->setFirstResult(($page - 1) * $max)
            ->setMaxResults($max);

        return new Paginator($qb);
    }
}
