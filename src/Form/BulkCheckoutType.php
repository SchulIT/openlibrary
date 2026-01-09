<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Borrower;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class BulkCheckoutType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('borrower', EntityType::class, [
                'class' => Borrower::class,
                'label' => 'checkouts.borrower',
                'choice_label' => fn(Borrower $borrower): string => sprintf('[%s] %s', $borrower->getBarcodeId(), $borrower),
                'attr' => [
                    'data-choice' => 'true'
                ]
            ])
            ->add('expectedEnd', DateType::class, [
                'label' => 'checkouts.expected_end',
                'widget' => 'single_text'
            ])
            ->add('books', EntityType::class, [
                'label' => 'checkouts.books',
                'class' => Book::class,
                'choice_label' => fn(Book $book): string => sprintf('[%s] %s', $book->getBarcodeId(), $book->getTitle()),
                'multiple' => true,
                'attr' => [
                    'data-choice' => 'true'
                ]
            ]);
    }
}
