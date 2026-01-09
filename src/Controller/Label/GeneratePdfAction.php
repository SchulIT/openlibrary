<?php

namespace App\Controller\Label;

use App\Http\ValueResolver\MapCsvQueryParameter;
use App\Label\DownloadLabelsRequest;
use App\Label\DownloadLabelsRequestType;
use App\Label\PdfCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GeneratePdfAction extends AbstractController {

    #[Route('/labels/pdf', name: 'download_pdf_labels')]
    public function __invoke(
        Request $request,
        PdfCreator $pdfCreator,
        #[MapCsvQueryParameter(delimiter: ',', filter: FILTER_VALIDATE_INT)] array $ids = [ ]
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_BOOKS_ADMIN');
        $downloadRequest = new DownloadLabelsRequest();

        $form = $this->createForm(DownloadLabelsRequestType::class, $downloadRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $pdfCreator->createPdfResponse($downloadRequest);
        }

        return $this->render('labels/download.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
