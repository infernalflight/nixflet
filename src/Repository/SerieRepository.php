<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findSeriesCustom(int $offset, int $limit, string $status, \DateTime $date, ?float $vote = null): array
    {
        $q = $this->createQueryBuilder('s')
            ->orderBy('s.popularity', 'DESC')
            ->andWhere('s.status = :status OR s.firstAirDate <= :date')
            ->setParameter('status', $status)
            ->setParameter('date', $date);

        if ($vote) {
            $q->orWhere('s.vote >= :vote')
                ->setParameter('vote', $vote);
        }

        $q2 = clone $q;
        $q2->select('COUNT(s.id)');

        return [
            $q2->getQuery()->getSingleScalarResult(),
            $q->setFirstResult($offset) // offset = rang de la pagination
            ->setMaxResults($limit) // nb de resultats du lot
            ->getQuery()
            ->getResult()
        ];
    }



//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
