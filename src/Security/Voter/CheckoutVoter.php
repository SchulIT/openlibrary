<?php

namespace App\Security\Voter;

use App\Entity\Checkout;
use LogicException;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CheckoutVoter extends Voter {

    public const string LIST = 'list-checkouts';
    public const string CHECKOUT_ANY = 'checkout-any';
    public const string EDIT = 'edit';
    public const string SHOW = 'show';
    public const string DELETE = 'delete';

    public function __construct(
        private readonly AccessDecisionManagerInterface $accessDecisionManager
    ) {

    }

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::CHECKOUT_ANY
            || $attribute === self::LIST
            || ($subject instanceof Checkout && in_array($attribute, [ self::EDIT, self::DELETE, self::SHOW]));
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        switch($attribute) {
            case self::CHECKOUT_ANY:
            case self::LIST:
                $this->accessDecisionManager->decide($token, ['ROLE_LENDER']);

            case self::EDIT:
            case self::SHOW:
                return $this->accessDecisionManager->decide($token, ['ROLE_LENDER']);

            case self::DELETE:
                return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
        }

        throw new LogicException('This code should not be reached!');
    }
}
