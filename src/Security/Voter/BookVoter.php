<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookVoter extends Voter
{
    public const EDIT = 'book.edit';
    public const VIEW = 'book.view';

    public function __construct(protected readonly AuthorizationCheckerInterface $checker)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Book;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            if ($this->checker->isGranted('ROLE_ADMIN')) {
                return true;
            }
            return false;
        }

        /** @var Book $subject */
        return match ($attribute) {
            self::EDIT => $user === $subject->getCreatedBy(),
            self::VIEW => true,
            default => false,
        };
    }
}
