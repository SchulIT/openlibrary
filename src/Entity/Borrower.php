<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\UniqueConstraint(fields: ['barcodeId', 'type'])]
#[UniqueEntity(fields: ['email'])]
#[UniqueEntity(fields: ['barcodeId'])]
class Borrower implements Stringable {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING, enumType: BorrowerType::class)]
    private BorrowerType $type;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    private ?string $barcodeId = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $firstname;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $lastname;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    private string $email;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Length(max: 255)]
    private ?string $grade;

    /**
     * @var Collection<Checkout>
     */
    #[ORM\OneToMany(targetEntity: Checkout::class, mappedBy: 'borrower')]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $checkouts;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
        $this->checkouts = new ArrayCollection();
    }

    public function getBarcodeId(): ?string {
        return $this->barcodeId;
    }

    public function setBarcodeId(?string $barcodeId): Borrower {
        $this->barcodeId = $barcodeId;
        return $this;
    }

    public function getType(): BorrowerType {
        return $this->type;
    }

    public function setType(BorrowerType $type): void {
        $this->type = $type;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void {
        $this->firstname = $firstname;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void {
        $this->lastname = $lastname;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getGrade(): ?string {
        return $this->grade;
    }

    public function setGrade(?string $grade): void {
        $this->grade = $grade;
    }

    /**
     * @return Collection<Checkout>
     */
    public function getCheckouts(): Collection {
        return $this->checkouts;
    }

    #[Override]
    public function __toString(): string {
        if($this->type === BorrowerType::Student) {
            return sprintf('S %s, %s (%s)', $this->getLastname(), $this->getFirstname(), $this->getGrade());
        } else if($this->type === BorrowerType::Teacher) {
            return sprintf('L %s, %s (%s)', $this->getLastname(), $this->getFirstname(), $this->getBarcodeId());
        }

        return sprintf('%s, %s', $this->getLastname(), $this->getLastname());
    }
}
