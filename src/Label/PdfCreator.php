<?php

namespace App\Label;

use App\Entity\LabelTemplate;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use TCPDF;

readonly class PdfCreator {

    /**
     * @var array<string, ObjectivePrintStrategyInterface>
     */
    private array $printStrategies;

    public function __construct(
        #[AutowireIterator(ObjectivePrintStrategyInterface::AUTOCONFIGURE_TAG)] iterable $printStrategies
    ) {
        $strategies = [ ];
        foreach($printStrategies as $strategy) {
            $strategies[$strategy->getTargetObjective()->value] = $strategy;
        }

        $this->printStrategies = $strategies;
    }

    public function createPdfResponse(DownloadLabelsRequest $request): Response {
        $response = new Response($this->createPdf($request), 200, [
            'Content-Type' => 'application/pdf'
        ]);

        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'barcodes.pdf'));

        return $response;
    }

    /**
     * @param DownloadLabelsRequest $request
     * @return string Resulting PDF document as (binary?) string
     * @throws Exception
     */
    public function createPdf(DownloadLabelsRequest $request): string {
        $strategy = $this->printStrategies[$request->printObjective->value] ?? null;

        if($strategy === null) {
            throw new Exception('PrintStrategy not found');
        }

        $label = $request->template;

        $pdf = $this->createTCPDF($label);
        $pdf->AddPage();

        $column = 1;
        $row = 1;

        $cellHeight = $label->getCellHeightMM() - 2 * $label->getCellPaddingMM();
        $cellWidth = $label->getCellWidthMM() - 2 * $label->getCellPaddingMM();

        $books = $request->books;
        array_unshift(
            $books,
            ...array_fill(0, $request->offset, null)
        );

        foreach ($books as $book) {
            $x = $pdf->GetX();
            $y = $pdf->GetY();

            $strategy->print($pdf, $book, $label, $x, $y, $cellWidth, $cellHeight);

            if($column === $label->getColumns()) {
                $pdf->Ln($label->getCellHeightMM());
                $column = 1;
                $row++;
            } else {
                $column++;
            }

            if($row === $label->getRows() + 1) {
                $pdf->AddPage();
                $row = 1;
            }
        }

        return $pdf->Output('labels.pdf', 'S');
    }

    private function createTCPDF(LabelTemplate $label): TCPDF {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setAuthor('');
        $pdf->setTitle('Barcodes');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setTopMargin($label->getTopMarginMM());
        $pdf->setLeftMargin($label->getLeftMarginMM());
        $pdf->setRightMargin($label->getRightMarginMM());

        $pdf->setAutoPageBreak(false);

        $pdf->setFont('helvetica', '', 8);

        return $pdf;
    }
}
