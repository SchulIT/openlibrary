<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['abbreviation'])]
class Category {

    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING, length: 16, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 16)]
    private ?string $abbreviation = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    /** @var class-string|null */
    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private ?string $shelfmarkGenerator = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $shelfmarkGeneratorParameter = null;

    use TimestampableOnCreateTrait;
    use TimestampableOnUpdateTrait;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
    }

    public function getAbbreviation(): ?string {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): Category {
        $this->abbreviation = $abbreviation;
        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): Category {
        $this->name = $name;
        return $this;
    }

    public function getShelfmarkGenerator(): ?string {
        return $this->shelfmarkGenerator;
    }

    public function setShelfmarkGenerator(?string $shelfmarkGenerator): Category {
        $this->shelfmarkGenerator = $shelfmarkGenerator;
        return $this;
    }

    public function getShelfmarkGeneratorParameter(): ?string {
        return $this->shelfmarkGeneratorParameter;
    }

    public function setShelfmarkGeneratorParameter(?string $shelfmarkGeneratorParameter): Category {
        $this->shelfmarkGeneratorParameter = $shelfmarkGeneratorParameter;
        return $this;
    }

    public function __toString(): string {
        return sprintf('%s (%s)', $this->getName(), $this->getAbbreviation());
    }
}
