<?php

namespace App\Controller\Admin;

use App\Entity\PotenzialBasisArtikel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class PotenzialBasisArtikelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PotenzialBasisArtikel::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('Datum'),
            TextField::new('Mitarbeiter'),
            TextField::new('BasisArtikel'),
            TextareaField::new('Auffaeligkeit','Auffäligkeit'),
            TextField::new('Informiert', 'Informiert (Name)'),
            TextField::new('JahrMonat', 'Potenzial Jahr/Monat'),
            BooleanField::new('Gecheckt')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Potenzial BasisArtikel')
            ->setEntityLabelInPlural('Potenzial BasisArtikel')
            ->setDefaultSort(['Datum' => 'DESC'])
            ->showEntityActionsInlined();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Datum')
            ->add('Mitarbeiter')
            ->add('BasisArtikel')
            ->add('Informiert')
            ->add('Gecheckt')
            //->add(EntityFilter::new('BeNr'))
            ;
    }
}
