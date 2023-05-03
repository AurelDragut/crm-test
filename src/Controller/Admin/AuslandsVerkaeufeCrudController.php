<?php

namespace App\Controller\Admin;

use App\Entity\AuslandsVerkaeufe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AuslandsVerkaeufeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AuslandsVerkaeufe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('Datum'),
            TextField::new('Mitarbeiter'),
            TextField::new('Land'),
            TextField::new('VerkaufsArtikelKategorie', 'Verkaufs Artikel/Kategorie'),
            TextField::new('JahrMonat', 'Potenzial Jahr/Monat'),
            TextField::new('Informiert','Informiert (Name)'),
            BooleanField::new('Gecheckt'),
            TextareaField::new('Aktion')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Auslands Verkauf')
            ->setEntityLabelInPlural('Auslands Verkäufe')
            ->setDefaultSort(['Datum' => 'DESC'])
            ->showEntityActionsInlined();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Datum')
            ->add('Mitarbeiter')
            ->add('Land')
            ->add('Informiert')
            ->add('Gecheckt')
            //->add(EntityFilter::new('BeNr'))
            ;
    }
}
