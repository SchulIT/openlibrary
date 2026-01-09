<?php

namespace App\Label;

use App\Entity\Book;
use App\Entity\LabelTemplate;
use Override;
use TCPDF;

class BarcodePrintStrategy implements ObjectivePrintStrategyInterface {

    #[Override]
    public function getTargetObjective(): PrintObjective {
        return PrintObjective::Barcode;
    }

    #[Override]
    public function print(TCPDF $pdf, Book|null $book, LabelTemplate $label, float $x, float $y, float $cellWidth, float $cellHeight): void {
        if ($book !== null) {
            $pdf->write1DBarcode(
                $book->getBarcodeId(),
                'C39',
                $x + $label->getCellPaddingMM(),
                $y + $label->getCellPaddingMM(),
                $cellWidth,
                $cellHeight * 0.55,
                0.4,
                $this->getBarcodeStyle(),
                'M'
            );
        }

        $pdf->setX($x + $label->getCellPaddingMM());

        if($book !== null) {
            $text = $book->getShelfmark();
            $pdf->Cell(
                $cellWidth,
                $cellHeight * 0.45,
                $text,
                align: 'C'
            );
        }

        $pdf->setXY($x + $label->getCellWidthMM(), $y);
    }

    private function getBarcodeStyle(): array {
        return [
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => false,
            'cellfitalign' => '',
            'border' => 0,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => [ 0, 0, 0 ], // black
            'bgcolor' => false,
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => false
        ];
    }
}
