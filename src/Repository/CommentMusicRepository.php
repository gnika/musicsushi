<?php

namespace App\Repository;

use App\Entity\CommentMusic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommentMusic|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentMusic|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentMusic[]    findAll()
 * @method CommentMusic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentMusicRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommentMusic::class);
    }

    // /**
    //  * @return CommentMusic[] Returns an array of CommentMusic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentMusic
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
