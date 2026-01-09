<?php

namespace App\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum BorrowerType: string implements TranslatableInterface {
    case Teacher = 'teacher';
    case Student = 'student';
    case Other = 'other';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string {
        $key = sprintf('borrower_type.%s', $this->value);
        return $translator->trans($key, domain: 'enums');
    }
}