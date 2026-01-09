<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Borrower;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class CheckoutType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('book', EntityType::class, [
                'label' => 'checkouts.book',
                'class' => Book::class,
                'disabled' => true
            ])
            ->add('borrower', EntityType::class, [
                'label' => 'checkouts.borrower',
                'class' => Borrower::class,
                'disabled' => true
            ])
            ->add('start', DateType::class, [
                'label' => 'checkouts.start',
                'disabled' => true,
                'widget' => 'single_text'
            ])
            ->add('expectedEnd', DateType::class, [
                'label' => 'checkouts.expected_end',
                'widget' => 'single_text'
            ])
            ->add('end', DateType::class, [
                'label' => 'checkouts.end',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'checkouts.comment',
                'required' => false
            ]);
    }
}
