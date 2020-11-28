<?php
namespace App\Security;

use DateTime;
use App\Entity\User;
use App\Entity\Penalty;
use App\Repository\UserRepository;
use App\Repository\PenaltyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PenaltyVoter extends Voter
{

    const TYPE = 'penalty';

    private $pr;
    private $requestStack;
    private $em;
    public function __construct(Security $security,PenaltyRepository $pr,RequestStack $requestStack,EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->pr = $pr;
        $this->em = $em;
        $this->requestStack=$requestStack;
    }
    protected function supports(string $attribute, $subject)
    {
        if (!($attribute==self::TYPE)) {
            return false;
        }
        if (!$subject) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('danger', 'You are not logged,register first.');
            return false;
        }
        return $this->canBorrow($user);
        throw new \LogicException('This code should not be reached!');
    }

    private function canBorrow( User $user)
    {
        $p1 = $this->pr->findTotalHistoricForPenaltyType($this->user->getId(),"3 month","Late Return");
        $p2 = $this->pr->findTotalHistoricForPenaltyType($this->user->getId(),"6 month","Late Return");
        $p3 = $this->pr->findTotalHistoricForPenaltyType($this->user->getId(),"6 month","Bill");
        $p4 = $this->pr->findTotalHistoricForPenaltyType($this->user->getId(),"1 year","Bill"); 
        $p5 = $this->pr->findTotalHistoricForPenaltyType($this->user->getId(),"1 year","Late Return");

        if($p5>=10 && $p4>3){
            $penalty = new Penalty();
            $penalty->setLabel("Ban");
            $penalty->setUser($this->user);
            $penalty->setAmout(15);
            $penalty->setDate(new DateTime());
            $this->user->setStatus("Banned");
            $this->em->persist($penalty);
            $this->em->persist($this->user);
            $this->em->flush();
            $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('danger', 'You have been banned for 15 days .');
            return false;
        }
        else if($p3>=2){
            $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('danger', 'You may encounter a 5 euros bill .');
        }
        else if ($p1>=3){
            $penalty = new Penalty();
            $penalty->setLabel("Bill");
            $penalty->setUser($this->user);
            $penalty->setAmout(1);
            $penalty->setDate(new DateTime());
            $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('danger', 'You have to pay 1 euro for your recent late return .');
        }
        else if($p2>=2){
            $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('danger', 'Warning, you could be charged for your late return.');
        }
        return true;
    }
}