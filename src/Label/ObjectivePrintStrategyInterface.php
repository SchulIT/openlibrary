<?php

namespace App\Label;

use App\Entity\Book;
use App\Entity\LabelTemplate;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use TCPDF;

#[AutoconfigureTag(ObjectivePrintStrategyInterface::AUTOCONFIGURE_TAG)]
interface ObjectivePrintStrategyInterface {
    public const string AUTOCONFIGURE_TAG = 'app.labels.objective_print_strategy';


    public function getTargetObjective(): PrintObjective;

    public function print(TCPDF $pdf, Book|null $book, LabelTemplate $label, float $x, float $y, float $cellWidth, float $cellHeight): void;
}
