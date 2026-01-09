<?php

namespace App\Import\Book;

use App\Entity\Book;
use App\Entity\Category;
use App\Repository\BookRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Utils\ArrayUtils;
use App\Utils\CollectionUtils;
use DateTime;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CsvImporter {

    public function __construct(private BookRepositoryInterface $bookRepository,
                                private CategoryRepositoryInterface $categoryRepository,
                                private ValidatorInterface $validator) {

    }

    /**
     * @throws InvalidArgument
     * @throws CsvColumnException
     * @throws UnavailableStream
     * @throws Exception|ValidationFailedException
     */
    public function importCsv(CsvImportRequest $request): void {
        $csv = Reader::createFromPath($request->csv->getRealPath());
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($request->delimiter);

        $categories = ArrayUtils::createArrayWithKeys(
            $this->categoryRepository->findAll(),
            fn(Category $category) => $category->getAbbreviation()
        );

        $this->bookRepository->beginTransaction();

        foreach($csv->getRecords() as $record) {
            $id = $this->getColumnValue($record, $request->idHeader, 'Feld "Nummer" nicht vorhanden oder leer.');

            $book = $this->bookRepository->findOneByBarcodeId($id);

            if($book === null) {
                $book = new Book();
                $book->setBarcodeId($id);
            }

            $book->setTitle($this->getColumnValue($record, $request->titleHeader, true, 'Feld "Titel" nicht vorhanden oder leer.'));

            $authors = $this->getColumnValue($record, $request->authorsHeader, false);

            if(!empty($authors)) {
                $book->setAuthors([$authors]);
            }

            if(!empty($request->subtitleHeader)) {
                $book->setSubtitle($this->getColumnValue($record, $request->subtitleHeader, false));
            }

            if(!empty($request->seriesHeader)) {
                $book->setSeries($this->getColumnValue($record, $request->seriesHeader, false));
            }

            if(!empty($request->publisherHeader)) {
                $book->setPublisher($this->getColumnValue($record, $request->publisherHeader, false));
            }

            if(!empty($request->shelfmarkHeader)) {
                $book->setShelfmark($this->getColumnValue($record, $request->shelfmarkHeader, false));
            }

            try {
                $book->setIsbn($this->getColumnValue($record, $request->isbnHeader, true, 'Feld "ISBN" nicht vorhanden oder leer.'));
            } catch(CsvColumnException) {
                continue;
            }

            if(!empty($request->yearHeader)) {
                $book->setYear(
                    $this->getIntOrNull(
                        $this->getColumnValue($record, $request->yearHeader, false)
                    )
                );
            }

            $category = $this->getColumnValue($record, $request->categoryHeader, true, 'Feld "Kategorie" nicht vorhanden oder leer.');
            $book->setCategory($categories[$category] ?? null);

            if(!empty($request->receiptDateHeader)) {
                $book->setReceiptDate(
                    $this->getDateOrNull(
                        $this->getColumnValue($record, $request->receiptDateHeader, false),
                        $request->dateFormat,
                        false
                    )
                );
            }

            if(!empty($request->inventoryDateHeader)) {
                $book->setLastInventoryDate(
                    $this->getDateOrNull(
                        $this->getColumnValue($record, $request->inventoryDateHeader, false),
                        $request->dateFormat,
                        false
                    )
                );
            }

            if(!empty($request->priceHeader)) {
                $book->setPrice(
                    $this->getFloatOrNull(
                        $this->getColumnValue($record, $request->priceHeader, false),
                    )
                );
            }

            $violations = $this->validator->validate($book);

            if(count($violations) > 0) {
                continue;
                //throw new ValidationFailedException($id, $violations);
            }

            $this->bookRepository->persist($book);
        }

        $this->bookRepository->commit();
    }

    /**
     * @throws CsvColumnException
     */
    private function getDateOrNull(string|null $value, string $format, bool $throw = true, string $throwMessage = 'Kein gültiges Datum.'): ?DateTime {
        if(empty($value)) {
            return null;
        }

        $dateTime = DateTime::createFromFormat($format, $value);

        if($dateTime === false) {
            if($throw === true) {
                throw new CsvColumnException($format, $throwMessage);
            } else {
                return null;
            }
        }

        return $dateTime;
    }

    private function getFloatOrNull(string|null $value): ?float {
        if(empty($value)) {
            return null;
        }

        $value = str_replace(',', '.', $value);

        if(!is_numeric($value)) {
            return null;
        }

        return floatval($value);
    }

    private function getIntOrNull(string|null $value): ?int {
        if(empty($value)) {
            return null;
        }

        if(!is_numeric($value)) {
            return null;
        }

        return intval($value);
    }

    /**
     * @throws CsvColumnException
     */
    private function getColumnValue(array $record, string $key, bool $throw = true, string $throwMessage = 'Feld nicht gesetzt oder leer'): ?string {
        $value = $record[$key] ?? null;

        if($throw === true && empty($value)) {
            throw new CsvColumnException($throwMessage);
        }

        if(empty($value)) {
            return null;
        }

        return $value;
    }
}
