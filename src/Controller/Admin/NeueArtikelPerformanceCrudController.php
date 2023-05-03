<?php

namespace App\Controller\Admin;

use App\Entity\NeueArtikelPerformance;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NeueArtikelPerformanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NeueArtikelPerformance::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('Datum'),
            TextField::new('Mitarbeiter'),
            TextField::new('VerkaufsArtikel'),
            TextareaField::new('Auffaeligkeit', 'Auffäligkeit'),
            TextField::new('Informiert', 'Informiert (Name)'),
            BooleanField::new('Gecheckt')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Neue Artikel Performance')
            ->setEntityLabelInPlural('Neue Artikel Performance')
            ->setDefaultSort(['Datum' => 'DESC'])
            ->showEntityActionsInlined();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Datum')
            ->add('Mitarbeiter')
            ->add('VerkaufsArtikel')
            ->add('Informiert')
            ->add('Gecheckt')
            //->add(EntityFilter::new('BeNr'))
            ;
    }
}
