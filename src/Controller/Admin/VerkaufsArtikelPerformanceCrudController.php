<?php

namespace App\Controller\Admin;

use App\Entity\VerkaufsArtikelPerformance;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class VerkaufsArtikelPerformanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VerkaufsArtikelPerformance::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('Datum'),
            TextField::new('Mitarbeiter'),
            TextField::new('VerkaufsArtikel'),
            ChoiceField::new('Plattform')->setChoices([
                'Amazon' => 'Amazon',
                'Ebay' => 'Ebay',
                'Autobatterien24' => 'Autobatterien24',
                'BatterieSpezialist' => 'BatterieSpezialist',
                'BatterieZentrum' => 'BatterieZentrum',
                'Langzeitbatterien' => 'Langzeitbatterien',
                'SIGABatterien' => 'SIGABatterien',
                'Solarbatterie' => 'Solarbatterie',
                'Versorgungsbatterie' => 'Versorgungsbatterie',
                'Winnerbatterien' => 'Winnerbatterien',
                'Wohnmobilbatterie' => 'Wohnmobilbatterie'
            ]),
            TextField::new('Informiert', 'Informiert (Name)'),
            TextareaField::new('Auffaeligkeit', 'Auffäligkeit'),
            BooleanField::new('Gecheckt')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Verkaufs Artikel Performance')
            ->setEntityLabelInPlural('Verkaufs Artikel Performance')
            ->setDefaultSort(['Datum' => 'DESC'])
            ->showEntityActionsInlined();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Datum')
            ->add('Mitarbeiter')
            ->add('VerkaufsArtikel')
            ->add(ChoiceFilter::new('Plattform')->setChoices([
                'Amazon' => 'Amazon',
                'Ebay' => 'Ebay',
                'Autobatterien24' => 'Autobatterien24',
                'BatterieSpezialist' => 'BatterieSpezialist',
                'BatterieZentrum' => 'BatterieZentrum',
                'Langzeitbatterien' => 'Langzeitbatterien',
                'SIGABatterien' => 'SIGABatterien',
                'Solarbatterie' => 'Solarbatterie',
                'Versorgungsbatterie' => 'Versorgungsbatterie',
                'Winnerbatterien' => 'Winnerbatterien',
                'Wohnmobilbatterie' => 'Wohnmobilbatterie'
            ]))
            ->add('Informiert')
            ->add(BooleanFilter::new('Gecheckt'));
    }

}
