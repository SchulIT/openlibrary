<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\BorrowerType as BorrowerTypeEntity;

class BorrowerType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('type', EnumType::class, [
                'label' => 'borrowers.type',
                'class' => BorrowerTypeEntity::class,
                'placeholder' => 'filter.choose.borrower_type',
            ])
            ->add('barcodeId', TextType::class, [
                'label' => 'borrowers.barcode_id.label',
                'help' => 'borrowers.barcode_id.help'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'borrowers.firstname'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'borrowers.lastname'
            ])
            ->add('email', EmailType::class, [
                'label' => 'borrowers.email'
            ])
            ->add('grade', TextType::class, [
                'label' => 'borrowers.grade.label',
                'help' => 'borrowers.grade.help',
                'required' => false
            ]);
    }
}
