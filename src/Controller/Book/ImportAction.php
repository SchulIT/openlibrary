<?php

namespace App\Controller\Book;

use App\Import\Book\CsvImporter;
use App\Import\Book\CsvImportRequest;
use App\Import\Book\CsvImportRequestType;
use App\Security\Voter\BookVoter;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImportAction extends AbstractController {

    #[Route('/books/import', name: 'import_books')]
    public function __invoke(
        CsvImporter $importer,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(BookVoter::IMPORT);
        $importRequest = new CsvImportRequest();
        $form = $this->createForm(CsvImportRequestType::class, $importRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $importer->importCsv($importRequest);

                $this->addFlash('success', 'books.import.success');
                return $this->redirectToRoute('admin_books');
            } catch (Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('books/import.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
