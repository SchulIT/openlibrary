<?php

namespace App\Import\Book;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CsvImportRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('csv', FileType::class, [
                'label' => 'books.import.csv.label',
                'help' => 'books.import.csv.help'
            ])
            ->add('delimiter', TextType::class, [
                'label' => 'books.import.delimiter'
            ])
            ->add('dateFormat', TextType::class, [
                'label' => 'books.import.date_format.label',
                'help' => 'books.import.date_format.help'
            ])
            ->add('idHeader', TextType::class, [
                'label' => 'books.import.headers.id.label',
                'help' => 'books.import.headers.id.help'
            ])
            ->add('authorsHeader', TextType::class, [
                'label' => 'books.import.headers.authors.label',
                'help' => 'books.import.headers.authors.help'
            ])
            ->add('titleHeader', TextType::class, [
                'label' => 'books.import.headers.title.label',
                'help' => 'books.import.headers.title.help'
            ])
            ->add('subtitleHeader', TextType::class, [
                'label' => 'books.import.headers.subtitle.label',
                'help' => 'books.import.headers.subtitle.help',
                'required' => false
            ])
            ->add('seriesHeader', TextType::class, [
                'label' => 'books.import.headers.series.label',
                'help' => 'books.import.headers.series.help',
                'required' => false
            ])
            ->add('isbnHeader', TextType::class, [
                'label' => 'books.import.headers.isbn.label',
                'help' => 'books.import.headers.isbn.help'
            ])
            ->add('categoryHeader', TextType::class, [
                'label' => 'books.import.headers.category.label',
                'help' => 'books.import.headers.category.help'
            ])
            ->add('receiptDateHeader', TextType::class, [
                'label' => 'books.import.headers.receipt_date.label',
                'help' => 'books.import.headers.receipt_date.help'
            ])
            ->add('updatedAtHeader', TextType::class, [
                'label' => 'books.import.headers.updated_at.label',
                'help' => 'books.import.headers.updated_at.help',
                'required' => false
            ])
            ->add('inventoryDateHeader', TextType::class, [
                'label' => 'books.import.headers.inventory_date.label',
                'help' => 'books.import.headers.inventory_date.help',
                'required' => false
            ])
            ->add('priceHeader', TextType::class, [
                'label' => 'books.import.headers.price.label',
                'help' => 'books.import.headers.price.help',
                'required' => false
            ]);
    }
}
