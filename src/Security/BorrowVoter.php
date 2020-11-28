<?php
namespace App\Security;

use App\Entity\User;
use App\Entity\Document;
use App\Repository\CopyRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;
class BorrowVoter extends Voter
{

    const BORROW = 'borrow';
    private $quota;
    private $cr;
    private $requestStack;
    
    public function __construct(int $borrowQuota, Security $security,CopyRepository $cr,RequestStack $requestStack)
    {
        $this->quota = $borrowQuota;
        $this->security = $security;
        $this->cr = $cr;
        $this->requestStack=$requestStack;
    }
    protected function supports(string $attribute, $subject)
    {
        if (!($attribute==self::BORROW)) {
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
        $currentBorrow = $this->cr->getCurrentBorrowForUser($user->getId());
        
        if($currentBorrow[0]["count"]>=$this->quota){
            $this->requestStack->getCurrentRequest()->getSession()->getFlashBag()->add('danger', 'Too many documents borrowed! You have to return them first.');
            return false;
        }
        else{
            return true;
        }
    }
}