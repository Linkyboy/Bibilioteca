<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\Markup;
use App\Entity\Artist;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;

class WikipediaFilter extends AbstractExtension{
    private $client;
    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }
    public function getFilters()
    {
        return [
            new TwigFilter('ArtistWiki', [$this, 'getArtistWiki']),
        ];
    }
    public function getArtistWiki(Artist $a){
        $name = $a->getFirstName()." ".$a->getLastName();
        $url = 'https://fr.wikipedia.org/wiki/'.$a->getFirstName().'_'.$a->getLastName();
        $response = $this->client->request(
            'GET',
            $url
        );
        if($response->getStatusCode()==200){
            return new Markup('<a href="'.$url.'">'. $name.'</a>', 'UTF-8');
        }
        else{
            return new Markup('<span>'. $name.'</span>', 'UTF-8');
        }
    }
}