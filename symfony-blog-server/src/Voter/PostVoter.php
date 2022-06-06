<?php

namespace App\Voter;

use App\Common\Security\AbstractVoter;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @extends AbstractVoter<Post>
 */
class PostVoter extends AbstractVoter
{
    public const EDIT_ACTION   = 'edit';
    public const DELETE_ACTION = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, self::getAttributes()) && $subject instanceof Post;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $this->callVoter($attribute, $subject, $user);
    }

    protected function canEdit(Post $post, User $user): bool
    {
        return $this->isGranted(User::ADMIN_ROLE, $user) || $post->getAuthor() === $user;
    }

    protected function canDelete(Post $post, User $user): bool
    {
        return $this->isGranted(User::ADMIN_ROLE, $user) || $post->getAuthor() === $user;
    }

    protected static function getAttributes(): array
    {
        return [self::EDIT_ACTION, self::DELETE_ACTION];
    }
}