<?php

namespace App\Label;

use App\Entity\Book;
use App\Entity\LabelTemplate;
use Symfony\Component\Validator\Constraints as Assert;

class DownloadLabelsRequest {
    /**
     * @var Book[]
     */
    #[Assert\Count(min: 1)]
    public array $books = [ ];

    public PrintObjective $printObjective = PrintObjective::Barcode;

    #[Assert\NotNull]
    public ?LabelTemplate $template = null;

    #[Assert\GreaterThanOrEqual(0)]
    public int $offset = 0;
}
