<?php

namespace App\Voter;

use App\Common\Security\AbstractVoter;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @extends AbstractVoter<User>
 */
class UserVoter extends AbstractVoter
{
    public const VERIFY_ACCOUNT_ACTION   = 'verifyAccount';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, self::getAttributes()) && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $this->callVoter($attribute, $subject, $user);
    }

    protected function canVerifyAccount(User $loggedIn, User $willBeUpdated): bool
    {
        return $loggedIn->getId() === $willBeUpdated->getId() && !$willBeUpdated->getActivated();
    }

    protected static function getAttributes(): array
    {
        return [self::VERIFY_ACCOUNT_ACTION];
    }
}