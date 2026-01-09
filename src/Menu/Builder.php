<?php

namespace App\Menu;

use App\Security\Voter\BorrowerVoter;
use App\Security\Voter\CheckoutVoter;
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

        if($this->authorizationChecker->isGranted(CheckoutVoter::CheckoutAny)) {
            $menu->addChild('checkouts.menu', [
                'route' => 'checkouts'
            ])
                ->setExtra('icon', 'fa fa-shopping-cart');

            $menu->addChild('returns.menu', [
                'route' => 'return'
            ])
                ->setExtra('icon', 'fa fa-reply');
        }

        $menu->addChild('borrowers.menu', [
            'route' => 'admin_borrowers'
        ])
            ->setExtra('icon', 'fa fa-users');

        if($this->authorizationChecker->isGranted('ROLE_CATEGORIES_ADMIN')) {
            $menu->addChild('categories.menu', [
                'route' => 'admin_categories'
            ])
                ->setExtra('icon', 'fa fa-list');
        }

        if($this->authorizationChecker->isGranted('ROLE_BOOKS_ADMIN')) {
            $menu->addChild('books.menu', [
                'route' => 'admin_books'
            ])
                ->setExtra('icon', 'fa fa-book-open');

            $menu->addChild('labels.menu', [
                'route' => 'labels'
            ])
                ->setExtra('icon', 'fa fa-barcode');
        }

        return $menu;
    }
}
