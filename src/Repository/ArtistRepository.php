<?php

namespace App\Repository;

use App\Entity\Artist;
use App\Entity\Book;
use App\Entity\Participates;
use App\Entity\CD;
use App\Entity\Category;
use App\Entity\DVD;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Artist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artist[]    findAll()
 * @method Artist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artist::class);
    }

    public function getBookAuthor(int $bookid){
        return $this->createQueryBuilder('a')
            ->join('a.participates','p')
            ->innerJoin(Book::class,'b','WITH','p.document=b.id')
            ->where("b.id=:id")
            ->andWhere("p.role='Auteur'")
            ->setParameter("id",$bookid)
            ->getQuery()
            ->getResult()
            
        ;
    }
    public function getArtistStartingByLetter(string $letter){
        $e =$this->createQueryBuilder('a')->expr();
        return $this->createQueryBuilder('a')
            ->add("where",$e->like("a.lastName","?1"))
            ->setParameter("1",$letter."%")
            
            ->getQuery()
            ->getResult();
    }
    public function getArtistStartingByLetterForEntity(string $letter,string $entity){
        $e =$this->createQueryBuilder('a')->expr();
        return $this->createQueryBuilder('a')
            ->join('a.participates','p')
            ->innerJoin(Book::class,'b','WITH','p.document=b.id')
            ->add("where",$e->like("a.lastName","?1"))
            ->groupBy("a.id")
            ->setParameter("1",$letter."%")
            ->getQuery()
            ->getResult();
    }
    public function getDocumentArtists(int $docID){
        return $this->createQueryBuilder('a')
            ->join('a.participates','p')
            ->join('p.document','d')
            ->where("d.id=:id")
            ->setParameter("id",$docID)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getArtistInCategory(int $id){
        return $this->createQueryBuilder('a')
            ->join('a.participates','p')
            ->join('p.document','d')
            ->join('d.category','c')
            ->where("c.id=:id")
            ->setParameter("id",$id)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * findAllEntityArtistAlphabetical
     *
     * @param  mixed $entity
     * @return void
     */
    public function findAllEntityArtistAlphabetical(string $entity){
        return $this->createQueryBuilder('a')
            ->join('a.participates','p')
            ->innerJoin($entity,'b','WITH','p.document=b.id')
            ->orderBy('a.lastName', 'ASC')
            ->addOrderBy('a.firstName', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * findAllEntityArtistInCategory
     *
     * @param  mixed $id
     * @param  mixed $entity
     * @return void
     */
    public function findAllEntityArtistInCategoryID(int $id,string $entity){
        return $this->createQueryBuilder('a')
            ->innerJoin("a.participates","p")
            ->innerJoin($entity,"b","WITH","p.document = b.id")
            ->innerJoin("b.category", "c")
            ->where("c.id=:id")
            ->setParameter("id",$id)
            ->orderBy("c.categoryName","ASC")
            ->getQuery()
            ->getResult();
    }
    
    // /**
    //  * @return Artist[] Returns an array of Artist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Artist
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
