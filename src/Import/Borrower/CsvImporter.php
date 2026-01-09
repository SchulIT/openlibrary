<?php

namespace App\Import\Borrower;

use App\Entity\Borrower;
use App\Repository\BorrowerRepositoryInterface;
use App\Repository\CheckoutRepositoryInterface;
use App\Utils\ArrayUtils;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

readonly class CsvImporter {
    public const string IdHeader = 'ID';
    public const string FirstnameHeader = 'Vorname';
    public const string LastnameHeader = 'Nachname';
    public const string EmailHeader = 'E-Mail';
    public const string GradeHeader = 'Klasse';

    public function __construct(private BorrowerRepositoryInterface $borrowerRepository, private CheckoutRepositoryInterface $checkoutRepository) {
    }

    /**
     * @throws InvalidArgument
     * @throws CsvColumnException
     * @throws UnavailableStream
     * @throws Exception
     */
    public function importCsv(CsvImportRequest $request): Result {
        $result = new Result();

        $borrowers = ArrayUtils::createArrayWithKeys(
            $this->borrowerRepository->findExternalByType($request->type),
            fn(Borrower $borrower) => $borrower->getBarcodeId()
        );

        $csv = Reader::createFromPath($request->csv->getRealPath());
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($request->delimiter);

        $toAdd = [ ];
        $toUpdate = [ ];
        $toRemove = [ ];

        $targetIds = [ ];

        foreach($csv->getRecords() as $offset => $record) {
            $id = $this->getColumnValue($record, self::IdHeader, true, 'Feld "ID" nicht vorhanden oder leer');
            $borrower = new Borrower();

            if(array_key_exists($id, $borrowers)) {
                $borrower = $borrowers[$id];
                $toUpdate[] = $borrower;
                $result->addUpdated($borrower);
            } else {
                $borrower->setBarcodeId($id);
                $toAdd[] = $borrower;
                $result->addAdded($borrower);
            }

            $targetIds[] = $id;

            $firstname = $this->getColumnValue($record, self::FirstnameHeader, true, 'Feld "Vorname" nicht vorhanden oder leer');
            $lastname = $this->getColumnValue($record, self::LastnameHeader, true, 'Feld "Nachname" nicht vorhanden oder leer');
            $email = $this->getColumnValue($record, self::EmailHeader, true, 'Feld "E-Mail" nicht vorhanden oder leer');
            $grade = $this->getColumnValue($record, self::GradeHeader, false);

            $borrower->setType($request->type);
            $borrower->setFirstname($firstname);
            $borrower->setLastname($lastname);
            $borrower->setEmail($email);
            $borrower->setGrade($grade);
        }

        foreach($borrowers as $id => $borrower) {
            if(!in_array($id, $targetIds)) {
                $toRemove[] = $borrower;
            }
        }

        $this->borrowerRepository->beginTransaction();

        foreach($toAdd as $borrower) {
            $this->borrowerRepository->persist($borrower);
        }

        foreach($toUpdate as $borrower) {
            $this->borrowerRepository->persist($borrower);
        }

        if($request->removeOld === true) {
            /** @var Borrower $borrower */
            foreach ($toRemove as $borrower) {
                if ($this->checkoutRepository->hasActiveCheckouts($borrower)) {
                    $result->addNotRemoved($borrower);
                } else {
                    $this->borrowerRepository->remove($borrower);
                    $result->addRemoved($borrower);
                }
            }
        }

        $this->borrowerRepository->commit();

        return $result;
    }

    /**
     * @throws CsvColumnException
     */
    private function getColumnValue(array $record, string $key, bool $throw = true, string $throwMessage = 'Feld nicht gesetzt oder leer'): ?string {
        $value = $record[$key] ?? null;

        if($throw === true && empty($value)) {
            throw new CsvColumnException($throwMessage);
        }

        return $value;
    }
}
