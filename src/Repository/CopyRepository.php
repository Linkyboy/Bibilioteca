<?php

namespace App\Repository;

use App\Entity\Copy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
/**
 * @method Copy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Copy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Copy[]    findAll()
 * @method Copy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CopyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Copy::class);
    }
    public function getTotalAvailableCopies(int $id){
        $e=$this->createQueryBuilder('co')->expr();
        return $this->createQueryBuilder('co')
        ->select("COUNT(co.id) as count")
        ->join("co.document","do")
        ->where("do.id=:id")
        ->andWhere($e->notIn('co.id',$this->getNotAvailableCopyForDocument($id)))
        ->setParameters(["id"=>$id,"now"=>new DateTime()])
        
        //->setParameter("id2",$id)
        ->getQuery()
        ->getResult()
        ;
    }
    public function getNotAvailableCopyForDocument(int $id){
        $e = $this->createQueryBuilder('c')->expr();
        return $this->createQueryBuilder('c')
            ->select("c.id")
            ->join("c.borrowings","b")
            ->join("c.document","d")
            ->where("d.id=:id")
            ->andWhere("b.expectedReturnDate>:now")
            ->orWhere("b.actualReturnDate IS NULL")
            ->groupBy("c.id")
            ->getDQL()
            ;
    }
    public function getAvailableCopies(int $id){
        $e=$this->createQueryBuilder('co')->expr();
        return $this->createQueryBuilder('co')
        ->join("co.document","do")
        ->where("do.id=:id")
        ->andWhere($e->notIn('co.id',$this->getNotAvailableCopyForDocument($id)))
        ->setParameters(["id"=>$id,"now"=>new DateTime()])
        ->getQuery()
        ->getResult()
        ;
    }

    public function getCurrentBorrowForUser(int $id){
        return $this->createQueryBuilder('c')
        ->select("COUNT(c.id) as count")
        ->join("c.borrowings","b")
        ->join("b.user","u")
        ->where("b.actualReturnDate IS NULL")
        ->andWhere("u.id=:id")
        ->setParameters(["id"=>$id])
        ->getQuery()
        ->getResult()
        ;
    }
    // /**
    //  * @return Copy[] Returns an array of Copy objects
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
    public function findOneBySomeField($value): ?Copy
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
