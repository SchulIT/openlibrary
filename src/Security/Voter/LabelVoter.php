<?php

namespace App\Security\Voter;

use Override;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LabelVoter extends Voter {

    public const string LIST = 'list-labels';
    public const string NEW = 'new-label';
    public const string EDIT = 'edit';
    public const string SHOW = 'show';
    public const string DELETE = 'delete';

    public function __construct(
        private readonly AccessDecisionManagerInterface $accessDecisionManager
    ) {

    }

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::LIST
            || $attribute === self::NEW
            || (in_array($attribute, [self::EDIT, self::DELETE, self::SHOW]) && $subject instanceof Label);
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }
}
