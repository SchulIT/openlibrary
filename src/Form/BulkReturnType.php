<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BulkReturnType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
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
