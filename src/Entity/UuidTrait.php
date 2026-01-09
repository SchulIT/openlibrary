<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait UuidTrait {
    #[ORM\Column(type: Types::STRING, length: 36, unique: true)]
    private ?string $uuid;

    public function getUuid(): ?string {
        return $this->uuid;
    }
}