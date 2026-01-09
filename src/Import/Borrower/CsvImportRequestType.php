<?php

namespace App\Import\Borrower;

use App\Entity\BorrowerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CsvImportRequestType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('csv', FileType::class, [
                'label' => 'borrowers.import.csv.label',
                'help' => 'borrowers.import.csv.help'
            ])
            ->add('type', EnumType::class, [
                'label' => 'borrowers.import.type.label',
                'class' => BorrowerType::class,
                'placeholder' => 'borrowers.import.type.placeholder',
            ])
            ->add('delimiter', TextType::class, [
                'label' => 'borrowers.import.delimiter'
            ])
            ->add('removeOld', CheckboxType::class, [
                'label' => 'borrowers.import.remove.label',
                'help' => 'borrowers.import.remove.help',
                'required' => false
            ]);
    }
}
