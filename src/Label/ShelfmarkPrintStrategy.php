<?php

namespace App\Label;

use App\Entity\Book;
use App\Entity\LabelTemplate;
use Override;
use TCPDF;

class ShelfmarkPrintStrategy implements ObjectivePrintStrategyInterface {
    #[Override]
    public function getTargetObjective(): PrintObjective {
        return PrintObjective::Shelfmark;
    }

    #[Override]
    public function print(TCPDF $pdf, ?Book $book, LabelTemplate $label, float $x, float $y, float $cellWidth, float $cellHeight): void {
        if($book !== null) {
            $pdf->setX($x + $label->getCellPaddingMM());
            $pdf->Cell(
                $cellWidth,
                $cellHeight,
                $book->getShelfmark(),
                align: 'C'
            );
        }

        $pdf->setXY($x + $label->getCellWidthMM(), $y);
    }
}
