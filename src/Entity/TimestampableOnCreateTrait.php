<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimestampableOnCreateTrait {
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(type: Types::STRING)]
    #[Gedmo\Blameable(on: 'create')]
    private string $createdBy;

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedBy(): string {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): self {
        $this->createdBy = $createdBy;
        return $this;
    }
}
