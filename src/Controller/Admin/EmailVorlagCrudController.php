<?php

namespace App\Controller\Admin;

use App\Entity\EmailVorlag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Security\Core\Security;

class EmailVorlagCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return EmailVorlag::class;
    }

    public function configureCrud(\EasyCorp\Bundle\EasyAdminBundle\Config\Crud $crud):\EasyCorp\Bundle\EasyAdminBundle\Config\Crud
    {
        return $crud
            ->setEntityLabelInPlural('E-Mail-Vorlagen')
            ->showEntityActionsInlined()
            // ...
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL)->setPermission(Action::INDEX, 'ROLE_MITARBEITER');
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
