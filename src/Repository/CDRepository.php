<?php

namespace App\Repository;

use App\Entity\CD;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CD|null find($id, $lockMode = null, $lockVersion = null)
 * @method CD|null findOneBy(array $criteria, array $orderBy = null)
 * @method CD[]    findAll()
 * @method CD[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CDRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CD::class);
    }
    public function getEightLastCDs(){
        return $this->createQueryBuilder('b')
            ->orderBy("b.inCollectionDate","ASC")
            ->setFirstResult( 0 )
            ->setMaxResults( 8 )
            ->getQuery()
            ->getResult()
        ;
    }
    public function getPinnedCDs(){
        return $this->createQueryBuilder('b')
            ->where('b.isPinned=1')
            ->getQuery()
            ->getResult()
        ;
    }
    public function getArtistsCD(int $id){
        return $this->createQueryBuilder('b')
            ->join("b.participates",'p')
            ->join("p.artist",'a')
            ->where("a.id=:id")
            ->setParameter("id",$id)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getCategoryCD(int $id){
        return $this->createQueryBuilder('b')
            ->join("b.category",'c')
            ->where("c.id=:id")
            ->setParameter("id",$id)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getCDStartingByLetter(string $letter){
        $e =$this->createQueryBuilder('b')->expr();
        return $this->createQueryBuilder('b')
            ->add("where",$e->like("b.title","?1"))
            ->setParameter("1",$letter."%")
            ->getQuery()
            ->getResult();
    }
    public function getPaginatedCD(int $pagination,int $length){

        return $this->createQueryBuilder('b')
            ->setFirstResult($pagination*$length)
            ->setMaxResults($length)
            ->orderBy("b.title")
            ->getQuery()
            ->getResult();
    }
    public function getCDWithString(string $string){
        $e =$this->createQueryBuilder('b')->expr();
        return $this->createQueryBuilder('b')
            ->add("where",$e->like("b.title","?1"))
            ->setParameter("1","%".$string."%")
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return CD[] Returns an array of CD objects
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
    public function findOneBySomeField($value): ?CD
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
