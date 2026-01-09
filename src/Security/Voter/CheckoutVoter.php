<?php

namespace App\Security\Voter;

use App\Entity\Checkout;
use LogicException;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CheckoutVoter extends Voter {

    public const string CheckoutAny = 'checkout-any';
    public const string Edit = 'edit';
    public const string Remove = 'remove';

    public function __construct(
        private readonly AccessDecisionManagerInterface $accessDecisionManager
    ) {

    }

    #[Override]
    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::CheckoutAny
            || ($subject instanceof Checkout && in_array($attribute, [ self::Edit, self::Remove]));
    }

    #[Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        switch($attribute) {
            case self::CheckoutAny:
                $this->accessDecisionManager->decide($token, ['ROLE_LENDER']);

            case self::Edit:
                return $this->accessDecisionManager->decide($token, ['ROLE_LENDER']);

            case self::Remove:
                return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
        }

        throw new LogicException('This code should not be reached!');
    }
}
