<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ArtistRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Artist::class);
        $this->em = $em;
    }

    public function findAllArtistsByPriority()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('a')
            ->from('App:Artist', 'a')
            ->orderBy('a.priority', 'DESC');

        return $qb
            ->getQuery()
            ->getResult();
    }
}
