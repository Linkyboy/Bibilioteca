<?php

namespace App\Repository;

use App\Entity\DVD;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DVD|null find($id, $lockMode = null, $lockVersion = null)
 * @method DVD|null findOneBy(array $criteria, array $orderBy = null)
 * @method DVD[]    findAll()
 * @method DVD[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DVDRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DVD::class);
    }
    public function getEightLastDVDs(){
        return $this->createQueryBuilder('b')
            ->orderBy("b.inCollectionDate","ASC")
            ->setFirstResult( 0 )
            ->setMaxResults( 8 )
            ->getQuery()
            ->getResult()
        ;
    }
    public function getPinnedDVDs(){
        return $this->createQueryBuilder('b')
            ->where('b.isPinned=1')
            ->getQuery()
            ->getResult()
        ;
    }
    public function getArtistsDVD(int $id){
        return $this->createQueryBuilder('b')
            ->join("b.participates",'p')
            ->join("p.artist",'a')
            ->where("a.id=:id")
            ->setParameter("id",$id)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getCategoryDVD(int $id){
        return $this->createQueryBuilder('b')
            ->join("b.category",'c')
            ->where("c.id=:id")
            ->setParameter("id",$id)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getDVDStartingByLetter(string $letter){
        $e =$this->createQueryBuilder('b')->expr();
        return $this->createQueryBuilder('b')
            ->add("where",$e->like("b.title","?1"))
            ->setParameter("1",$letter."%")
            ->getQuery()
            ->getResult();
    }
    public function getPaginatedDVD(int $pagination,int $length){

        return $this->createQueryBuilder('b')
            ->setFirstResult($pagination*$length)
            ->setMaxResults($length)
            ->orderBy("b.title")
            ->getQuery()
            ->getResult();
    }
    public function getDVDWithString(string $string){
        $e =$this->createQueryBuilder('b')->expr();
        return $this->createQueryBuilder('b')
            ->add("where",$e->like("b.title","?1"))
            ->setParameter("1","%".$string."%")
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return DVD[] Returns an array of DVD objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DVD
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
