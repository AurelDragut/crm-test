<?php

namespace App\Controller\Admin;

use App\Entity\RausgeflogeneArtikel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class RausgeflogeneArtikelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RausgeflogeneArtikel::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('Datum'),
            TextField::new('Mitarbeiter'),
            ChoiceField::new('Plattform')->setChoices(['Amazon' => 'Amazon', 'Ebay' => 'Ebay']),
            TextField::new('Kundennummer'),
            TextField::new('Bestellnummer'),
            TextField::new('BetragsGebuehr', 'Betragsgebühr'),
            TextField::new('Informiert', 'Informiert (Name)'),
            BooleanField::new('Gecheckt')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index','Ebay/Amazon Kunden Optimierung')
            ->setEntityLabelInSingular('Optimierung')
            ->setEntityLabelInPlural('Optimierungen')
            ->setDefaultSort(['Datum' => 'DESC'])
            ->showEntityActionsInlined();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Datum')
            ->add('Mitarbeiter')
            ->add(ChoiceFilter::new('Plattform')->setChoices(['Amazon' => 'Amazon', 'Ebay' => 'Ebay']))
            ->add('Informiert')
            ->add('Gecheckt')
            //->add(EntityFilter::new('BeNr'))
            ;
    }
}
