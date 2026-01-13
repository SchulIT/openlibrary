<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\ParameterTypes\IntType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Validator\Constraints as Assert;

#[Settings]
class CheckoutSettings {
    use SettingsTrait;

    #[SettingsParameter(label: 'settings.checkout.duration.label', description: 'settings.checkout.duration.help', type: IntType::class)]
    #[Assert\GreaterThan(0)]
    public int $defaultCheckoutDurationInDays = 30;
}
