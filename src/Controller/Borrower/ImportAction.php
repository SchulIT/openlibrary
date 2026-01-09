<?php

namespace App\Controller\Borrower;

use App\Import\Borrower\CsvImporter;
use App\Import\Borrower\CsvImportRequest;
use App\Import\Borrower\CsvImportRequestType;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class ImportAction extends AbstractController {

    #[Route('/borrower/import', name: 'import_borrowers')]
    public function __invoke(
        CsvImporter $importer,
        Request $request,
        TranslatorInterface $translator
    ): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::IMPORT);

        $importRequest = new CsvImportRequest();
        $form = $this->createForm(CsvImportRequestType::class, $importRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $result = $importer->importCsv($importRequest);

                $this->addFlash(
                    'success',
                    $translator->trans(
                        'borrowers.import.success',
                        [
                            '%added%' => count($result->getAdded()),
                            '%updated%' => count($result->getUpdated()),
                            '%removed%' => count($result->getRemoved()),
                            '%notRemoved%' => count($result->getNotRemoved()),
                        ]
                    )
                );

                return $this->redirectToRoute('admin_borrowers');
            } catch (Throwable $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('borrowers/import.html.twig', [
            'header' => 'borrowers.import.label',
            'action' => 'borrowers.import.submit',
            'form' => $form->createView()
        ]);
    }
}
