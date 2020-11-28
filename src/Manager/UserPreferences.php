<?php 
namespace App\Manager;

use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\DocumentRepository;

class UserPreferences{

    private $cr;
    private $ur;
    private $dr;

    public function __construct(CategoryRepository $cr,UserRepository $ur,DocumentRepository $dr){
        $this->cr = $cr;
        $this->ur = $ur;
        $this->dr = $dr;
    }

    public function getUserPreferences(int $userID){
        return $this->cr->getUserCategoryPreferences($userID);
    }

    public function getUserSharingSamePreferences(int $userID,array $preference){
        $sharing = [];
        $users = $this->ur->findAll();
        foreach($users as $u){
            $upref= $this->getUserPreferences($u->getId());
            if(isset($preference[0]["id"]) && isset($upref[0]["id"]))
                if($upref[0]["id"]== $preference[0]["id"]){
                    $sharing[]=$u;
                }
        }
        return $sharing;
    }
    public function getMostBorrowedDocumentsForUserPreference(int $userID){
        $preference = $this->getUserPreferences($userID);
        $users = $this->getUserSharingSamePreferences($userID,$preference);
        $documentRanking = [];
        $result = [];
        foreach($users as $u){
            $res=$this->dr->getUserBorrowInCategory($u->getId(),$preference[0]["id"]);
            foreach($res as $r){
                if(!isset($documentRanking[$r["id"]])){
                    $documentRanking[$r["id"]] = 1;
                }
                else{
                    $documentRanking[$r["id"]] = $documentRanking[$r["id"]]+1;
                }
            }
        }
       for($i=0;$i<5;$i++){
            $max =array_keys($documentRanking, max($documentRanking));
            $result[]=$max[0];
            unset($documentRanking[$max[0]]);
        }
        return $result;
    }
}