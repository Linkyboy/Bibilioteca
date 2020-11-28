<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Book;
use App\Entity\CD;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
    public function findAllEntityCategories(string $entity){
        $e = $this->createQueryBuilder('c')->expr();
        return $this->createQueryBuilder('c')
            ->innerJoin("c.documents","d")
            ->where($e->isInstanceOf('d',$entity))
            ->orderBy("c.categoryName","ASC")
            ->getQuery()
            ->getResult();
    }

    public function getUserCategoryPreferences(int $id){

        return $this->createQueryBuilder("c")
        ->select("count(b.id),c.id")
        ->join("c.documents","d")
        ->join("d.copies","co")
        ->join("co.borrowings","b")
        ->where("b.user=:id")
        ->setParameter("id",$id)
        ->groupBy("c.id")
        ->orderBy("count(b.id)","DESC")
        ->setFirstResult( 0 )
        ->setMaxResults( 1)
        ->getQuery()
        ->getResult()
        ;
    }
    public function findAllCateogoriesForDocument(string $entity){
        $e= $this->createQueryBuilder("c")->expr();
        return $this->createQueryBuilder("c")
        ->join("c.documents","d")
        ->join("d.participates","p")
        ->join("p.artist","a")
        ->where($e->instanceof("d",$entity))
        ->getQuery()
        ->getResult()
        ;
    }
    public function findAllCateogoriesWithArtist(){
        return $this->createQueryBuilder("c")
        ->join("c.documents","d")
        ->join("d.participates","p")
        ->join("p.artist","a")
        ->groupBy("c.id")
        ->orderBy("c.categoryName","ASC")
        ->getQuery()
        ->getResult()
        ;
    }
    // /**
    //  * @return Category[] Returns an array of Category objects
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
    public function findOneBySomeField($value): ?Category
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
