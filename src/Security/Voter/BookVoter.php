<?php

namespace App\Security\Voter;

use App\Entity\Book;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookVoter extends Voter {

    public const string LIST = 'list-books';
    public const string NEW = 'new-book';
    public const string EDIT = 'edit';
    public const string SHOW = 'show';
    public const string DELETE = 'delete';
    public const string IMPORT = 'import-books';

    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager) {

    }

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::NEW
            || $attribute === self::LIST
            || $attribute === self::IMPORT
            || (in_array($attribute, [self::EDIT, self::DELETE, self::SHOW]) && $subject instanceof Book);
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        if($attribute === self::LIST || $attribute === self::SHOW) {
            return $this->accessDecisionManager->decide($token, [ 'ROLE_LENDER' ]);
        }

        return $this->accessDecisionManager->decide($token, ['ROLE_BOOKS_ADMIN']);
    }
}
