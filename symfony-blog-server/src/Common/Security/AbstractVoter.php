<?php

namespace App\Common\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use function Symfony\Component\String\u;

/**
 * @template T
 * {@inheritdoc}
 */
abstract class AbstractVoter extends Voter
{
    abstract protected static function getAttributes(): array;

    public function __construct(
        protected Security $security
    ) {}

    /**
     * @param T $subject
     */
    abstract protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool;

    /**
     * @param T $object
     */
    protected function callVoter(string $attribute, mixed $object, User $user): bool
    {
        $method = u(ucfirst($attribute))
            ->prepend('can')
            ->camel()
            ->toString();

        return $this->$method($object, $user);
    }

    protected function isGranted($attribute, $subject = null): bool
    {
        return $this->security->isGranted($attribute, $subject);
    }
}
