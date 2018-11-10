<?php
namespace App\Security;

use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TicketVoter extends Voter
{
    const EDIT = 'edit';
    const ASSIGN = 'assign';
    const CLOSE = 'close';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT,self::ASSIGN,self::CLOSE])) {
            return false;
        }

        // only vote on Ticket objects inside this voter
        if (!$subject instanceof Ticket) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Ticket object, thanks to supports
        /** @var Ticket $ticket */
        $ticket = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($ticket, $user);
            case self::ASSIGN:
                return $this->canAssign($ticket, $user);
            case self::CLOSE:
                return $this->canClose($ticket, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Ticket $ticket, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return ($ticket->getCurrentPlace() !== "closed") && 
            ($user === $ticket->getCreatedBy() || $user === $ticket->getAssignedTo() ||
            $this->security->isGranted('ROLE_ADMIN') && $ticket->getCurrentPlace() == "new");
    }

    private function canAssign(Ticket $ticket, User $user)
    {
        return $this->security->isGranted('ROLE_ADMIN') && $ticket->getCurrentPlace() == "new";
    }

    private function canClose(Ticket $ticket, User $user)
    {
        return $this->canEdit($ticket, $user);
    }
}
