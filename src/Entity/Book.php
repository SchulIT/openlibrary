<?php

namespace App\Entity;

use App\Book\Shelfmark\Generator;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
class Book implements Stringable {

    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    private ?string $barcodeId = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $title;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Length(max: 255)]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Length(max: 255)]
    private ?string $series = null;

    #[ORM\Column(type: Types::JSON, nullable: false)]
    private array $authors = [ ];

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Length(max: 255)]
    private ?string $publisher = null;

    #[ORM\Column(type: Types::STRING, length: 17, )]
    #[Assert\NotBlank]
    #[Assert\Isbn(type: Assert\Isbn::ISBN_13)]
    private string $isbn;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\Positive]
    private int|null $year = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private string|null $comment;

    #[Vich\UploadableField(mapping: 'covers', fileNameProperty: 'coverFileName')]
    #[Assert\Image(mimeTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'])]
    private ?File $cover = null;

    #[ORM\Column(nullable: true)]
    private ?string $coverFileName = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    #[Assert\NotNull]
    private ?Category $category = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $topic = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank]
    private ?string $shelfmark = Generator::MAGIC_STRING;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isBorrowable = true;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isListed = true;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull]
    private ?DateTime $receiptDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTime $lastInventoryDate = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private float|null $price = null;

    /**
     * @var Collection<Checkout>
     */
    #[ORM\OneToMany(targetEntity: Checkout::class, mappedBy: 'book')]
    #[ORM\OrderBy(['id' => 'DESC'])]
    private Collection $checkouts;

    use TimestampableOnCreateTrait;
    use TimestampableOnUpdateTrait;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
        $this->checkouts = new ArrayCollection();
    }

    public function getBarcodeId(): ?string {
        return $this->barcodeId;
    }

    public function setBarcodeId(?string $barcodeId): Book {
        $this->barcodeId = $barcodeId;
        return $this;
    }

    public function hasCover(): bool {
        return !empty($this->coverFileName);
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): Book {
        $this->title = $title;
        return $this;
    }

    public function getSubtitle(): ?string {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): Book {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function getSeries(): ?string {
        return $this->series;
    }

    public function setSeries(?string $series): Book {
        $this->series = $series;
        return $this;
    }

    public function getAuthors(): array {
        return $this->authors;
    }

    public function setAuthors(array $authors): Book {
        $this->authors = $authors;
        return $this;
    }

    public function getPublisher(): ?string {
        return $this->publisher;
    }

    public function setPublisher(?string $publisher): Book {
        $this->publisher = $publisher;
        return $this;
    }

    public function getIsbn(): string {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): Book {
        $this->isbn = $isbn;
        return $this;
    }

    public function getYear(): ?int {
        return $this->year;
    }

    public function setYear(?int $year): Book {
        $this->year = $year;
        return $this;
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function appendComment(string $comment, string $delimiter = '\n'): Book {
        if(empty($this->comment)) {
            $this->comment = trim($comment);
        } else {
            $this->comment .= $delimiter . $comment;
        }

        return $this;
    }

    public function setComment(?string $comment): Book {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getCover(): ?File {
        return $this->cover;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|null $cover
     * @return Book
     */
    public function setCover(?File $cover): Book {
        $this->cover = $cover;

        if(null !== $cover) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    public function getCoverFileName(): ?string {
        return $this->coverFileName;
    }

    public function clearCoverFileName(): Book {
        $this->coverFileName = null;
        return $this;
    }

    public function setCoverFileName(?string $coverFileName): Book {
        if($coverFileName === null) { // prevent update of null
            return $this;
        }

        $this->coverFileName = $coverFileName;
        return $this;
    }

    public function getCategory(): ?Category {
        return $this->category;
    }

    public function setCategory(?Category $category): Book {
        $this->category = $category;
        return $this;
    }

    public function getTopic(): ?string {
        return $this->topic;
    }

    public function setTopic(?string $topic): Book {
        $this->topic = $topic;
        return $this;
    }

    public function getShelfmark(): ?string {
        return $this->shelfmark;
    }

    public function setShelfmark(?string $shelfmark): Book {
        $this->shelfmark = $shelfmark;
        return $this;
    }

    public function isBorrowable(): bool {
        return $this->isBorrowable;
    }

    public function setIsBorrowable(bool $isBorrowable): Book {
        $this->isBorrowable = $isBorrowable;
        return $this;
    }

    public function isListed(): bool {
        return $this->isListed;
    }

    public function setIsListed(bool $isListed): Book {
        $this->isListed = $isListed;
        return $this;
    }

    public function getReceiptDate(): ?DateTime {
        return $this->receiptDate;
    }

    public function setReceiptDate(?DateTime $receiptDate): Book {
        $this->receiptDate = $receiptDate;
        return $this;
    }

    public function getLastInventoryDate(): ?DateTime {
        return $this->lastInventoryDate;
    }

    public function setLastInventoryDate(?DateTime $lastInventoryDate): Book {
        $this->lastInventoryDate = $lastInventoryDate;
        return $this;
    }

    public function getPrice(): ?float {
        return $this->price;
    }

    public function setPrice(?float $price): Book {
        $this->price = $price;
        return $this;
    }

    public function getCheckouts(): Collection {
        return $this->checkouts;
    }

    public function setCheckouts(Collection $checkouts): Book {
        $this->checkouts = $checkouts;
        return $this;
    }

    public function __toString(): string {
        return sprintf('%s - %s (%s)', $this->barcodeId, $this->title, $this->isbn);
    }
}
