<?php

namespace App\Import\Borrower;

use App\Entity\BorrowerType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class CsvImportRequest {
    #[Assert\NotNull]
    #[Assert\File]
    public File|null $csv = null;

    #[Assert\NotNull]
    public ?BorrowerType $type = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 1)]
    public string|null $delimiter = ';';

    public bool $removeOld = false;
}
