<?php

namespace App\Controller\Admin;

use App\Entity\Neusendung;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class NeusendungCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Neusendung::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            ChoiceField::new('SendungsTyp')->setChoices(['Transportschaden' => 'Transportschaden', 'Nachforschung' => 'Nachforschung', 'Gewährleistung/Garantie' => 'Gewährleistung/Garantie']),
            TextField::new('RechnungsNr', 'Rechnungsnummer'),
            DateField::new('Datum'),
            TextField::new('Stueck_ArtNr', 'Stück Artikelnummer'),
            TextField::new('Kommentar')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['Datum' => 'DESC'])
            ->showEntityActionsInlined();
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
            ->add('Stueck_ArtNr')
            ->add('RechnungsNr')
            ->add('Datum')
            ->add(ChoiceFilter::new('SendungsTyp')->setChoices(['Transportschaden' => 'Transportschaden', 'Nachforschung' => 'Nachforschung', 'Gewährleistung/Garantie' => 'Gewährleistung/Garantie']))
            //->add(EntityFilter::new('BeNr'))
            ;
    }
}
