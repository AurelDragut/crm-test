<?php

namespace App\Controller\Admin;

use App\Entity\BWare;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class BWareCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BWare::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('B-Ware')
            ->setEntityLabelInPlural('B-Ware')
            ->setDefaultSort(['id' => 'DESC'])
            ->showEntityActionsInlined()// ...
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('EingangsDatum'),
            TextField::new('ArtikelNummer'),
            NumberField::new('Stueck', 'Stück'),
            TextField::new('Status'),
            TextField::new('Name','Name')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)


            /*->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)*/
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('ArtikelNummer')
            ->add('Status')
            ->add('Name')
            //->add(EntityFilter::new('BeNr'))
            ;
    }

}
