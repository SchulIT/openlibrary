<?php

namespace App\Security\Voter;

use App\Entity\Category;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CategoryVoter extends Voter {
    public const string NEW = 'new-category';
    public const string EDIT = 'edit';
    public const string DELETE = 'delete';

    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager) {

    }

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::NEW
            || (in_array($attribute, [self::EDIT, self::DELETE]) && $subject instanceof Category);
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_BOOKS_ADMIN']);
    }
}
