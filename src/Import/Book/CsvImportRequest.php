<?php

namespace App\Import\Book;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class CsvImportRequest {
    #[Assert\NotNull]
    #[Assert\File]
    public File|null $csv = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 1)]
    public string|null $delimiter = ';';

    #[Assert\NotBlank]
    public string $dateFormat = 'd.m.Y';

    #[Assert\NotBlank]
    public string|null $idHeader = 'Nummer';

    #[Assert\NotBlank]
    public string|null $authorsHeader = 'Verfasser';

    #[Assert\NotBlank]
    public string|null $titleHeader = 'Titel';

    #[Assert\NotBlank(allowNull: true)]
    public string|null $subtitleHeader = null;

    #[Assert\NotBlank(allowNull: true)]
    public string|null $publisherHeader = null;

    #[Assert\NotBlank(allowNull: true)]
    public string|null $seriesHeader = 'Serie';

    #[Assert\NotBlank]
    public string|null $isbnHeader = 'ISBN/ISSN/ISMN';

    #[Assert\NotBlank(allowNull: true)]
    public string|null $yearHeader = 'Jahr';

    #[Assert\NotBlank]
    public string|null $categoryHeader = 'Kat.';

    #[Assert\NotBlank(allowNull: true)]
    public string|null $receiptDateHeader = 'Eingang';

    #[Assert\NotBlank(allowNull: true)]
    public string|null $updatedAtHeader = 'Änderung';

    #[Assert\NotBlank(allowNull: true)]
    public string|null $inventoryDateHeader = 'Inventur';

    #[Assert\NotBlank(allowNull: true)]
    public string|null $priceHeader = 'Preis';
}
