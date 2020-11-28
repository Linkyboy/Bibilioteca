<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function getPinnedBooks(){
        return $this->createQueryBuilder('b')
            ->where("b.isPinned=1")
            ->getQuery()
            ->getResult()
        ;
    }
    public function getEightLastBooks(){
        return $this->createQueryBuilder('b')
            ->orderBy("b.inCollectionDate","ASC")
            ->setFirstResult( 0 )
            ->setMaxResults( 8 )
            ->getQuery()
            ->getResult()
        ;
    }

    public function getArtistsBook(int $id){
        return $this->createQueryBuilder('b')
            ->join("b.participates",'p')
            ->join("p.artist",'a')
            ->where("a.id=:id")
            ->setParameter("id",$id)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getCategoryBook(int $id){
        return $this->createQueryBuilder('b')
            ->join("b.category",'c')
            ->where("c.id=:id")
            ->setParameter("id",$id)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getPaginatedBook(int $pagination,int $length){

        return $this->createQueryBuilder('b')
            ->setFirstResult($pagination*$length)
            ->setMaxResults($length)
            ->orderBy("b.title")
            ->getQuery()
            ->getResult();
    }
    public function getBookWithString(string $string){
        $e =$this->createQueryBuilder('b')->expr();
        return $this->createQueryBuilder('b')
            ->add("where",$e->like("b.title","?1"))
            ->setParameter("1","%".$string."%")
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
