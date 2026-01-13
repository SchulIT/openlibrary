<?php

namespace App\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\Routing\Route;

class SettingsMenuBuilder extends AbstractMenuBuilder {
    public function settingsMenu(array $options = [ ]): ItemInterface {
        $root = $this->factory->createItem('root');

        $root->addChild('settings.app.label', [
            'route' => 'app_settings'
        ])
            ->setExtra('icon', 'fa-solid fa-cog');

        $root->addChild('settings.barcode.label', [
            'route' => 'barcode_settings'
        ])
            ->setExtra('icon', 'fa-solid fa-barcode');

        $root->addChild('settings.checkout.label', [
            'route' => 'checkout_settings'
        ])
            ->setExtra('icon', 'fa-solid fa-shopping-cart');

        return $root;
    }
}
