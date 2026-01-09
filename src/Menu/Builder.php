<?php

namespace App\Menu;

use App\Security\Voter\BookVoter;
use App\Security\Voter\BorrowerVoter;
use App\Security\Voter\CategoryVoter;
use App\Security\Voter\CheckoutVoter;
use App\Security\Voter\LabelVoter;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class Builder {
    public function __construct(
        private FactoryInterface $factory,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {

    }

    public function mainMenu(array $options): ItemInterface {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav me-auto');

        $menu->addChild('dashboard.label', [
            'route' => 'dashboard'
        ])
            ->setExtra('icon', 'fa fa-home');

        $menu->addChild('browse.menu', [
            'route' => 'browse'
        ])
            ->setExtra('icon', 'fa fa-book');

        if($this->authorizationChecker->isGranted(CheckoutVoter::LIST)) {
            $menu->addChild('checkouts.menu', [
                'route' => 'checkouts'
            ])
                ->setExtra('icon', 'fa fa-shopping-cart');

            $menu->addChild('returns.menu', [
                'route' => 'return'
            ])
                ->setExtra('icon', 'fa fa-reply');
        }

        if($this->authorizationChecker->isGranted(BorrowerVoter::LIST)) {
            $menu->addChild('borrowers.menu', [
                'route' => 'admin_borrowers'
            ])
                ->setExtra('icon', 'fa fa-users');
        }

        if($this->authorizationChecker->isGranted(CategoryVoter::LIST)) {
            $menu->addChild('categories.menu', [
                'route' => 'admin_categories'
            ])
                ->setExtra('icon', 'fa fa-list');
        }

        if($this->authorizationChecker->isGranted(BookVoter::LIST)) {
            $menu->addChild('books.menu', [
                'route' => 'admin_books'
            ])
                ->setExtra('icon', 'fa fa-book-open');
        }

        if($this->authorizationChecker->isGranted(LabelVoter::LIST)) {
            $menu->addChild('labels.menu', [
                'route' => 'labels'
            ])
                ->setExtra('icon', 'fa fa-barcode');
        }

        return $menu;
    }
}
