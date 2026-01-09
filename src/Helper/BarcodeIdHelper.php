<?php

namespace App\Helper;

use App\Repository\BookRepositoryInterface;
use App\Settings\BarcodeIdSettings;
use Jbtronics\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Component\Clock\ClockInterface;

readonly class BarcodeIdHelper {

    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private BarcodeIdSettings $settings,
        private SettingsManagerInterface $settingsManager,
        private ClockInterface $clock
    ) {

    }

    public function getNextAvailableBarcodeId(): string {
        $now = $this->clock->now();
        $year = intval($now->format('Y'));

        if($this->settings->currentYear !== $year) {
            $this->settings->currentSequence = 1;
            $this->settings->currentYear = $year;
            $this->settingsManager->save($this->settings);
        }

        do {
            $barcodeId = sprintf('%d/%d', $this->settings->currentYear, $this->settings->currentSequence);
            $this->settings->currentSequence++;
        } while($this->bookRepository->findOneByBarcodeId($barcodeId) !== null);

        return $barcodeId;
    }
}
