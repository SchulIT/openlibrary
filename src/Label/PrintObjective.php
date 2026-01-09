<?php

namespace App\Label;

use Override;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum PrintObjective: string implements TranslatableInterface {
    case Barcode = 'barcode';
    case Shelfmark = 'shelfmark';

    #[Override]
    public function trans(TranslatorInterface $translator, ?string $locale = null): string {
        return $translator->trans(
            sprintf('print_objective.%s', $this->value),
            domain: 'enums',
            locale: $locale
        );
    }
}
