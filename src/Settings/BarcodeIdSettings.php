<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\ParameterTypes\IntType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;

#[Settings]
class BarcodeIdSettings {
    use SettingsTrait;

    #[SettingsParameter(type: IntType::class)]
    public int $currentYear = 2025;

    #[SettingsParameter(type: IntType::class)]
    public int $currentSequence = 1;
}
