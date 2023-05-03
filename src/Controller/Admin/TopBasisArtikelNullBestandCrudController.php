<?php

namespace App\Controller\Admin;

use App\Entity\TopBasisArtikelNullBestand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class TopBasisArtikelNullBestandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TopBasisArtikelNullBestand::class;
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
            BooleanField::new('Gecheckt'),
            TextareaField::new('Aktion')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Top BasisArtikel mit 0 Bestand')
            ->setEntityLabelInPlural('Top BasisArtikel mit 0 Bestand')
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
