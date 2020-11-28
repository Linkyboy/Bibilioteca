<?php

namespace App\Repository;

use DateTime;
use App\Entity\Penalty;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Penalty|null find($id, $lockMode = null, $lockVersion = null)
 * @method Penalty|null findOneBy(array $criteria, array $orderBy = null)
 * @method Penalty[]    findAll()
 * @method Penalty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PenaltyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Penalty::class);
    }
    public function findHistoricForPenaltyType(int $id,string $duration,string $type){
        return $this->createQueryBuilder('p')
            ->join('p.user','u')
            ->where('u.id=:id')
            ->andWhere("p.date<=:date")
            ->andWhere("p.label=:label")
            ->setParameters(["date"=>(new DateTime())->modify("-".$duration),"label"=>$type,"id"=>$id])
            ->getQuery()
            ->getResult()
            ;
    }
    public function findHistoricUserPenalties(int $id,string $duration){
        return $this->createQueryBuilder('p')
            ->join('p.user','u')
            ->where('u.id=:id')
            ->andWhere("p.date<=:date")
            ->setParameters(["date"=>(new DateTime())->modify("-".$duration),"id"=>$id])
            ->getQuery()
            ->getResult()
            ;
    }
    public function findTotalHistoricForPenaltyType(int $id,string $duration,string $type){
        return $this->createQueryBuilder('p')
            ->select("COUNT(p.id)")
            ->join('p.user','u')
            ->where('u.id=:id')
            ->andWhere("p.date<=:date")
            ->andWhere("p.label=:label")
            ->setParameters(["date"=>(new DateTime())->modify("-".$duration),"label"=>$type])
            ->distinct()
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    // /**
    //  * @return Penalty[] Returns an array of Penalty objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Penalty
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
