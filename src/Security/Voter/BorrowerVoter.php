<?php

namespace App\Security\Voter;

use App\Entity\Borrower;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BorrowerVoter extends Voter {

    public const string NEW = 'new-borrower';
    public const string SHOW = 'show';
    public const string EDIT = 'edit';
    public const string DELETE = 'delete';
    public const string IMPORT = 'import-borrowers';

    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager) {

    }

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::NEW
            || $attribute === self::IMPORT
            || (in_array($attribute, [self::EDIT, self::DELETE, self::SHOW]) && $subject instanceof Borrower);
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        if($attribute === self::SHOW) {
            return $this->accessDecisionManager->decide($token, ['ROLE_LENDER']);
        }

        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

}
