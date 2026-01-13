<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\ParameterTypes\IntType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;

#[Settings]
class BarcodeIdSettings {
    use SettingsTrait;

    #[SettingsParameter(type: IntType::class, label: 'settings.barcode.current_year')]
    public int $currentYear = 2025;

    #[SettingsParameter(type: IntType::class, label: 'settings.barcode.current_sequence.label', description: 'settings.barcode.current_sequence.help')]
    public int $currentSequence = 1;
}
