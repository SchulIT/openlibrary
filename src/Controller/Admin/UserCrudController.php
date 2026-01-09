<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInSingular('users.singular')
            ->setEntityLabelInPlural('users.plural')
            ->setSearchFields(['firstname', 'lastname', 'email']);
    }

    public function configureActions(Actions $actions): Actions {
        return $actions
            ->disable(Action::NEW);
    }

    public function configureFields(string $pageName): iterable {
        return [
            TextField::new('username', 'users.username')
                ->setDisabled(true),
            TextField::new('firstname', 'users.firstname')
                ->setDisabled(true),
            TextField::new('lastname', 'users.lastname')
                ->setDisabled(true),
            EmailField::new('email', 'users.email')
                ->setDisabled(true),
            AssociationField::new('associatedBorrowers', 'users.associated_borrowers')
                ->setFormTypeOption('multiple', true)
        ];
    }
}
