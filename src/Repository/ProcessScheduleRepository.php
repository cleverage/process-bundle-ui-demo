<?php

namespace App\Repository;

use App\Entity\ProcessSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProcessSchedule>
 *
 * @method ProcessSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProcessSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProcessSchedule[]    findAll()
 * @method ProcessSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcessScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProcessSchedule::class);
    }

//    /**
//     * @return ProcessSchedule[] Returns an array of ProcessSchedule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProcessSchedule
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
