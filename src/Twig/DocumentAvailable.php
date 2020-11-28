<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\Markup;
use App\Entity\Document;
use App\Repository\CopyRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;


class DocumentAvailable extends AbstractExtension{
    private $repository;
    public function __construct(CopyRepository $cr){
        $this->repository = $cr;
    }
    public function getFilters()
    {
        return [
            new TwigFilter('AvailableCopy', [$this, 'getAvailableCopy']),
        ];
    }
    public function getAvailableCopy(Document $d){

        $copies = $this->repository->getTotalAvailableCopies($d->getId());
 
        if($copies[0]["count"]>0){
            return '<span class="badge badge-primary">Available :'.$copies[0]["count"].'</span>';
        }
        else{
            return '<span class="badge badge-secondary">Not Available</span>';
        }
    }
}