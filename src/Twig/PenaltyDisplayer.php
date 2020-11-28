<?php
namespace App\Twig;

use Twig\TwigFilter;
use App\Entity\Penalty;
use Twig\Extension\AbstractExtension;


class PenaltyDisplayer extends AbstractExtension{

    public function getFilters()
    {
        return [
            new TwigFilter('PenaltyDisplay', [$this, 'getPenaltyDisplay']),
        ];
    }
    public function getPenaltyDisplay(Penalty $p){
 
        if($p->getLabel()=="LateReturn"){
            return '<tr class="table-warning">
                        <td>'.$p->getLabel().'</td>
                        <td>'.$p->getDate().'</td>

                    </tr>';
        }
        else if($p->getLabel()=="Bill"){
            return '<tr class="table-danger">
                        <td>'.$p->getLabel().'</td>

                    </tr>';
        }
        else{
            return '<tr>
                        <td>'.$p->getLabel().'</td>
                        <td>'.$p->getDate().'</td>
                    </tr>';
        }    

    }
}