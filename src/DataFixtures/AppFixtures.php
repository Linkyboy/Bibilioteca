<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Artist;
use App\Entity\Book;
use App\Entity\CD;
use App\Entity\DVD;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Penalty;
use App\Entity\Role;
use App\Entity\Copy;
use App\Entity\Participates;
use App\Entity\Borrowing;
use App\Repository\ArtistRepository;
use App\Repository\ParticipatesRepository;
use DateTime;
use Faker;
use Doctrine\Common\Collections\ArrayCollection;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $populator = new \Faker\ORM\Doctrine\Populator($faker, $manager);
        
        /*$populator->addEntity(Artist::class, 5);
        $populator->addEntity(Book::class, 5);
        $populator->addEntity(CD::class, 5);
        $populator->addEntity(DVD::class,5);
        $populator->addEntity(Category::class, 5);
        $populator->addEntity(User::class, 5);
        $pk = $populator->execute();*/
        
        $artists = [];
        $users = [];
        $books = [];
        $cds = [];
        $dvd = [];
        $copies = [];
        $roles=[];
        $categories = [];

        $possibleStatus = ["Brain new", "Damaged"," Poor"];

        // GENERATION DES ROLES 
        $role= new Role();
        $role->setDescription($faker->text);
        $role->setRoleName("ROLE_ADMIN");
        $roles[] = $role;
        $manager->persist($role);

        $role= new Role();
        $role->setDescription($faker->text);
        $role->setRoleName("ROLE_SUPERADMIN");
        $roles[] = $role;
        $manager->persist($role);

        $role= new Role();
        $role->setDescription($faker->text);
        $role->setRoleName("ROLE_ANONYMOUS");
        $roles[] = $role;
        $manager->persist($role);


        for ($i = 0; $i < 20; $i++) {
            
            $artiste = new Artist();
            $artists[] = $artiste;
            $artiste->setLastName($faker->lastName);
            $artiste->setFirstName($faker->firstName);
            $artiste->setPresentation($faker->text);
            $artiste->setIllustration("image/artist.jpg");
            $artiste->setBirthDate($faker->dateTimeBetween("-30 year", "-5 year"));
            $manager->persist($artiste);
            
            $book = new Book();
            $books[]=$book;
            $book->setReferenceNumber($faker->randomNumber($nbDigits = 8, $strict = false));
            $book->setTitle($faker->company);
            $book->setPublicationDate($faker->dateTimeBetween("-30 year", "-5 year"));
            $book->setInCollectionDate($faker->dateTimeBetween("-5 year", "now"));
            $book->setAvailability(true);
            $book->setPublisher($faker->name);
            $book->setIsPinned(rand(0,1));
            $book->setDescription($faker->text);
            $book->setPages($faker->randomNumber($nbDigits = 2, $strict = false));
            $book->setOriginalLanguage($faker->country);
            $book->setIsbn($faker->isbn13);
            $book->setIllustration("image/book.jpg");
            $manager->persist($book);

            $cd = new CD();
            $cds[]=$cd;
            $cd->setReferenceNumber($faker->randomNumber($nbDigits = 8, $strict = false));
            $cd->setTitle($faker->company);
            $cd->setPublicationDate($faker->dateTimeBetween("-30 year", "-5 year"));
            $cd->setInCollectionDate($faker->dateTimeBetween("-5 year", "now"));
            $cd->setAvailability(true);
            $cd->setPublisher($faker->name);
            $cd->setIsPinned(rand(0,1));
            $cd->setDescription($faker->text);
            $cd->setIllustration("image/cd.jpg");
            $cd->setTotalDuration($faker->dateTimeBetween("now-1hour"));
            $manager->persist($cd);

            $dvd = new DVD();
            $dvds[]=$dvd;
            $dvd->setReferenceNumber($faker->randomNumber($nbDigits = 8, $strict = false));
            $dvd->setTitle($faker->company);
            $dvd->setPublicationDate($faker->dateTimeBetween("-30 year", "-5 year"));
            $dvd->setInCollectionDate($faker->dateTimeBetween("-5 year", "now"));
            $dvd->setAvailability(true);
            $dvd->setPublisher($faker->name);
            $dvd->setIsPinned(rand(0,1));
            $dvd->setDescription($faker->text);
            $dvd->setIllustration("image/dvd.jpg");
            $dvd->setDuration($faker->dateTimeBetween("now-1hour"));
            $dvd->setHasBonus(rand(0,1));
            $manager->persist($dvd);

            $user = new User();
            $users[] = $user;
            $user->setUserName($faker->userName);
            $user->setAddress($faker->address);
            $user->setEmail($faker->email);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setPassword($faker->password);
            $user->setPhoneNumber($faker->phoneNumber);
            $user->setRegisterDate($faker->dateTimeBetween("-30 year", "-5 year"));
            $user->setEndDate($faker->dateTimeBetween("-4 year", "-1 year"));
            $manager->persist($user);

            $category = new Category();
            $category->setCategoryName($faker->text(30));
            $category->setDescription($faker->text);
            $manager->persist($category);
            $categories[]=$category;
        }

         // GENERATION DES PARTICIPATIONS
        foreach($books as $b){
            $part = new Participates();
            $a = $artists[rand(0,count($artists)-1)];
            $manager->persist($a);
            $part->setDocument($b);
            $part->setArtist($a);
            $part->setRole("Auteur");
            $manager->persist($part);
            
        }
        foreach($cds as $b){
            $part = new Participates();
            $a = $artists[rand(0,count($artists)-1)];
            $manager->persist($a);
            $part->setDocument($b);
            $part->setArtist($a);
            $part->setRole("Singer");
            $manager->persist($part);
            
        }
        foreach($dvds as $b){
            $part = new Participates();
            $a = $artists[rand(0,count($artists)-1)];
            $manager->persist($a);
            $part->setDocument($b);
            $part->setArtist($a);
            $part->setRole("Realisator");
            $manager->persist($part);
            
        }

        // GENERATION DES COPIES
        foreach($books as $b){
            $count = rand(0,6);
            for($i=0;$i<$count;$i++){
                $copy = new Copy();
                $copies[]=$copy;
                $copy->setDocument($b);
                $copy->setCopyReference($faker->ean13);
                $copy->setStatus($possibleStatus[rand(0,count($possibleStatus)-1)]);
                $manager->persist($copy);
            }  
        }
        foreach($cds as $b){
            $count = rand(0,6);
            for($i=0;$i<$count;$i++){
                $copy = new Copy();
                $copies[]=$copy;
                $copy->setDocument($b);
                $copy->setCopyReference($faker->ean13);
                $copy->setStatus($possibleStatus[rand(0,count($possibleStatus)-1)]);
                $manager->persist($copy);
            }  
        }
        foreach($dvds as $b){
            $count = rand(0,6);
            for($i=0;$i<$count;$i++){
                $copy = new Copy();
                $copies[]=$copy;
                $copy->setDocument($b);
                $copy->setCopyReference($faker->ean13);
                $copy->setStatus($possibleStatus[rand(0,count($possibleStatus)-1)]);
                $manager->persist($copy);
            }  
        }

        // GENERATION DES EMPRUNTS
        /*foreach($users as $u){
            $b1 = new Borrowing();
            $b2 = new Borrowing();
            $b3 = new Borrowing();
            $b4 = new Borrowing();
            $b5 = new Borrowing();
            $b6 = new Borrowing();
            $b7 = new Borrowing();
            $b8 = new Borrowing();
            $b9 = new Borrowing();
            $b10 = new Borrowing();
            $b11 = new Borrowing();
            $b12 = new Borrowing();
            $b1->setCopy($copies[rand(0,count($copies)-1)]);
            $b2->setCopy($copies[rand(0,count($copies)-1)]);
            $b3->setCopy($copies[rand(0,count($copies)-1)]);
            $b4->setCopy($copies[rand(0,count($copies)-1)]);
            $b5->setCopy($copies[rand(0,count($copies)-1)]);
            $b6->setCopy($copies[rand(0,count($copies)-1)]);
            $b7->setCopy($copies[rand(0,count($copies)-1)]);
            $b8->setCopy($copies[rand(0,count($copies)-1)]);
            $b9->setCopy($copies[rand(0,count($copies)-1)]);
            $b10->setCopy($copies[rand(0,count($copies)-1)]);
            $b11->setCopy($copies[rand(0,count($copies)-1)]);
            $b12->setCopy($copies[rand(0,count($copies)-1)]);
            $b1->setUser($u);
            $b2->setUser($u);
            $b3->setUser($u);
            $b4->setUser($u);
            $b5->setUser($u);
            $b6->setUser($u);
            $b7->setUser($u);
            $b8->setUser($u);
            $b9->setUser($u);
            $b10->setUser($u);
            $b11->setUser($u);
            $b12->setUser($u);
            $b1->setBorrowingDate(new DateTime($faker->date));
            $b2->setBorrowingDate(new DateTime($faker->date));
            $b3->setBorrowingDate(new DateTime($faker->date));
            $b4->setBorrowingDate(new DateTime($faker->date));
            $b5->setBorrowingDate(new DateTime($faker->date));
            $b6->setBorrowingDate(new DateTime($faker->date));
            $b7->setBorrowingDate(new DateTime($faker->date));
            $b8->setBorrowingDate(new DateTime($faker->date));
            $b9->setBorrowingDate(new DateTime($faker->date));
            $b10->setBorrowingDate(new DateTime($faker->date));
            $b11->setBorrowingDate(new DateTime($faker->date));
            $b12->setBorrowingDate(new DateTime($faker->date));
            $b1->setExpectedReturnDate(new DateTime($faker->date));
            $b2->setExpectedReturnDate(new DateTime($faker->date));
            $b3->setExpectedReturnDate(new DateTime($faker->date));
            $b4->setExpectedReturnDate(new DateTime($faker->date));
            $b5->setExpectedReturnDate(new DateTime($faker->date));
            $b6->setExpectedReturnDate(new DateTime($faker->date));
            $b7->setExpectedReturnDate(new DateTime($faker->date));
            $b8->setExpectedReturnDate(new DateTime($faker->date));
            $b9->setExpectedReturnDate(new DateTime($faker->date));
            $b10->setExpectedReturnDate(new DateTime($faker->date));
            $b11->setExpectedReturnDate(new DateTime($faker->date));
            $b12->setExpectedReturnDate(new DateTime($faker->date));
            $b1->setActualReturnDate(new DateTime($faker->date));
            $b2->setActualReturnDate(new DateTime($faker->date));
            $b3->setActualReturnDate(new DateTime($faker->date));
            $b4->setActualReturnDate(new DateTime($faker->date));
            $b5->setActualReturnDate(new DateTime($faker->date));
            $b6->setActualReturnDate(new DateTime($faker->date));
            $b7->setActualReturnDate(new DateTime($faker->date));
            $b8->setActualReturnDate(new DateTime($faker->date));
            $b9->setActualReturnDate(new DateTime($faker->date));
            $b10->setActualReturnDate(new DateTime($faker->date));
            $b11->setActualReturnDate(new DateTime($faker->date));
            $b12->setActualReturnDate(new DateTime($faker->date));
            $manager->persist($b1);
            $manager->persist($b2);
            $manager->persist($b3);
            $manager->persist($b4);
            $manager->persist($b5);
            $manager->persist($b6);
            $manager->persist($b7);
            $manager->persist($b8);
            $manager->persist($b9);
            $manager->persist($b10);
            $manager->persist($b11);
            $manager->persist($b12);
        }*/
        //AJOUT DE ROLE POUR LES UTILISATEURS
        foreach($users as $u){
            $u->addRole($roles[rand(0,count($roles)-1)]);
        }
       /* foreach($books  as $b ){
            $count = rand(1,3);
            for($i=0;$i<$count;$i++){
                $b->addCategory($categories[rand(0,count($categories)-1)]);
            }
        }
        foreach($cds  as $b ){
            $count = rand(1,3);
            for($i=0;$i<$count;$i++){
                $b->addCategory($categories[rand(0,count($categories)-1)]);
            }
        }
        foreach($dvds  as $b ){
            $count = rand(1,3);
            for($i=0;$i<$count;$i++){
                $b->addCategory($categories[rand(0,count($categories)-1)]);
            }
        }*/
        $manager->flush();
    }
}
