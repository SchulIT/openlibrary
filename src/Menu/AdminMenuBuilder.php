<?php

namespace App\Menu;

use App\Menu\SettingsMenuBuilder;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminMenuBuilder extends AbstractMenuBuilder {

    public function adminMenu(array $options): ItemInterface {
        $root = $this->factory->createItem('root')
            ->setChildrenAttributes([
                'class' => 'navbar-nav float-lg-right'
            ]);

        $menu = $root->addChild('admin', [
            'label' => ''
        ])
            ->setExtra('icon', 'fa fa-cogs')
            ->setAttribute('title', $this->translator->trans('administration.menu'))
            ->setExtra('menu', 'admin')
            ->setExtra('menu-container', '#submenu')
            ->setExtra('pull-right', true);

        if($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('settings.label', [
                'route' => 'admin_settings'
            ])
                ->setExtra('icon', 'fas fa-cogs');

            $menu->addChild('messenger.label', [
                'route' => 'zenstruck_messenger_monitor_dashboard'
            ])
                ->setExtra('icon', 'fas fa-envelope-open-text');

            $menu->addChild('logs.label', [
                'route' => 'admin_logs'
            ])
                ->setExtra('icon', 'fas fa-clipboard-list');
        }

        return $root;
    }
}
