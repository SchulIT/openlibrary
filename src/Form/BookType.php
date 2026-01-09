<?php

declare(strict_types=1);

namespace App\Form;

use App\Book\Shelfmark\Generator;
use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookType extends AbstractType {

    public function __construct(
        private readonly Generator $generator,
    ) {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('barcodeId', TextType::class, [
                'label' => 'books.barcode_id.label',
                'help' => 'books.barcode_id.help'
            ])
            ->add('title', TextType::class, [
                'label' => 'books.title',
            ])
            ->add('subtitle', TextType::class, [
                'label' => 'books.subtitle.label',
                'help' => 'books.subtitle.help',
                'required' => false
            ])
            ->add('series', TextType::class, [
                'label' => 'books.series.label',
                'help' => 'books.series.help',
                'required' => false
            ])
            ->add('isbn', TextType::class, [
                'label' => 'books.isbn.label',
                'help' => 'books.isbn.help'
            ])
            ->add('publisher', TextType::class, [
                'label' => 'books.publisher.label',
                'help' => 'books.publisher.help',
                'required' => false
            ])
            ->add('authors', CollectionType::class, [
                'label' => 'books.authors.label',
                'entry_type' => TextCollectionEntryType::class,
                'entry_options' => [
                    'constraints' => [
                        new NotBlank()
                    ]
                ],
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('year', IntegerType::class, [
                'label' => 'books.year.label',
                'help' => 'books.year.help',
                'required' => false
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'books.comment.label',
                'help' => 'books.comment.help',
                'required' => false
            ])
            ->add('cover', VichImageType::class, [
                'label' => 'books.cover.label',
                'required' => false,
                'delete_label' => 'actions.delete'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'books.category',
                'placeholder' => 'filter.choose.category'
            ])
            ->add('topic', TextType::class, [
                'label' => 'books.topic.label',
                'help' => 'books.topic.help',
                'required' => false
            ])
            ->add('shelfmark', TextType::class, [
                'label' => 'books.shelfmark.label',
                'help' => 'books.shelfmark.help'
            ])
            ->add('isBorrowable', CheckboxType::class, [
                'label' => 'books.borrowable.label',
                'help' => 'books.borrowable.help',
                'required' => false
            ])
            ->add('isListed', CheckboxType::class, [
                'label' => 'books.listed.label',
                'help' => 'books.listed.help',
                'required' => false
            ])
            ->add('receiptDate', DateType::class, [
                'label' => 'books.receipt_date.label',
                'help' => 'books.receipt_date.help',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('price', MoneyType::class, [
                'label' => 'books.price.label',
                'help' => 'books.price.help',
                'currency' => 'EUR',
                'required' => false
            ]);

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $book = $event->getData();

                if(!$book instanceof Book) {
                    return;
                }

                if($book->getShelfmark() !== Generator::MAGIC_STRING) {
                    return;
                }

                if($book->getCategory() === null) {
                    return;
                }

                $book->setShelfmark($this->generator->generate($book));
            });
    }
}
