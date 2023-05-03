<?php

namespace App\Controller\Admin;

use App\Entity\BatterieRegal;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class BatterieRegalCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return BatterieRegal::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Artikelnummer'),
            ChoiceField::new('Hersteller')->setChoices([
                'Banner Batterien' => 'Banner Batterien',
                'BSA Batterien' => 'BSA Batterien',
                'Exide Batterien' => 'Exide Batterien',
                'Langzeit Batterien' => 'Langzeit Batterien',
                'SIGA Batterien' => 'SIGA Batterien',
                'Tokohama Batterien' => 'Tokohama Batterien']),
            ChoiceField::new('Batterietechnologie')->setChoices([
                'AGM' => 'AGM',
                'Blei-Säure' => 'Blei-Säure',
                'Calcium' => 'Calcium',
                'Calcium/Silber' => 'Calcium/Silber',
                'Deep Cycle Technologie (Deep Cycle Sealed)' => 'Deep Cycle Technologie (Deep Cycle Sealed)',
                'Dry Charged Technology' => 'Dry Charged Technology',
                'EFB' => 'EFB',
                'GEL' => 'GEL',
                'GEL Deep Cycle Technologie' => 'GEL Deep Cycle Technologie',
                'Innovative LANGZEIT GEL Technologie' => 'Innovative LANGZEIT GEL Technologie',
                'Lithium Ionen' => 'Lithium Ionen',
                'Lithium-LiFePo4' => 'Lithium-LiFePo4',
                'Micro-Fibre Boost Technologie' => 'Micro-Fibre Boost Technologie',
                'Nano-Lithium-Polymer' => 'Nano-Lithium-Polymer',
                'Nassbatterie ' => 'Nassbatterie ',
                'verschlossene Nassbatterie' => 'verschlossene Nassbatterie',
                'SMF' => 'SMF']),
            TextField::new('Kapazitaet', 'Kapazität (Ah)'),
            TextField::new('Kaltstartstrom','Kaltstartstrom (A/EN)'),
            TextField::new('Masse', 'Maße'),
            ChoiceField::new('Standard')->setChoices([
                'Asia' => 'Asia',
                'Europa' => 'Europa',
                'USA' => 'USA'
            ]),
            ChoiceField::new('Regal')->setChoices([
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                6 => 6,
                7 => 7,
                8 => 8,
                9 => 9,
                10 => 10,
                11 => 11,
                12 => 12,
                13 => 13,
            ]),
            NumberField::new('Preis', 'Preis (€)')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the names of the Doctrine entity properties where the search is made on
            // (by default it looks for in all properties)
            ->setSearchFields(['Artikelnummer', 'Hersteller', 'Kapazitaet','Kaltstartstrom','Masse','Standard'])

            // defines the initial sorting applied to the list of entities
            // (user can later change this sorting by clicking on the table columns)
            ->setDefaultSort(['Regal' => 'ASC'])

            // the max number of entities to display per page
            ->setPaginatorPageSize(50)
            // the number of pages to display on each side of the current page
            // e.g. if num pages = 35, current page = 7, and you set ->setPaginatorRangeSize(4)
            // the paginator displays: [Previous]  1 ... 3  4  5  6  [7]  8  9  10  11 ... 35  [Next]
            // set this number to 0 to display a simple "< Previous | Next >" pager
            ->setPaginatorRangeSize(4)

            // these are advanced options related to Doctrine Pagination
            // (see https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/tutorials/pagination.html)
            ->setPaginatorUseOutputWalkers(true)
            ->setPaginatorFetchJoinCollection(false)
//            ->showEntityActionsAsDropdown()
            ->showEntityActionsInlined()
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('Artikelnummer'))
            ->add(ChoiceFilter::new('Hersteller')->setChoices([
                'Banner Batterien' => 'Banner Batterien',
                'BSA Batterien' => 'BSA Batterien',
                'Exide Batterien' => 'Exide Batterien',
                'Langzeit Batterien' => 'Langzeit Batterien',
                'SIGA Batterien' => 'SIGA Batterien',
                'Tokohama Batterien' => 'Tokohama Batterien']))
            ->add(ChoiceFilter::new('Batterietechnologie')->setChoices([
                'AGM' => 'AGM',
                'Blei-Säure' => 'Blei-Säure',
                'Calcium' => 'Calcium',
                'Calcium/Silber' => 'Calcium/Silber',
                'Deep Cycle Technologie (Deep Cycle Sealed)' => 'Deep Cycle Technologie (Deep Cycle Sealed)',
                'Dry Charged Technology' => 'Dry Charged Technology',
                'EFB' => 'EFB',
                'GEL' => 'GEL',
                'GEL Deep Cycle Technologie' => 'GEL Deep Cycle Technologie',
                'Innovative LANGZEIT GEL Technologie' => 'Innovative LANGZEIT GEL Technologie',
                'Lithium Ionen' => 'Lithium Ionen',
                'Lithium-LiFePo4' => 'Lithium-LiFePo4',
                'Micro-Fibre Boost Technologie' => 'Micro-Fibre Boost Technologie',
                'Nano-Lithium-Polymer' => 'Nano-Lithium-Polymer',
                'Nassbatterie ' => 'Nassbatterie ',
                'verschlossene Nassbatterie' => 'verschlossene Nassbatterie',
                'SMF' => 'SMF'])->canSelectMultiple())
            ->add(NumericFilter::new('Kapazitaet', 'Kapazität (Ah)'))
            ->add(NumericFilter::new('Kaltstartstrom', 'Kaltstartstrom (A/EN)'))
            ->add(TextFilter::new('Masse'))
            ->add(ChoiceFilter::new('Standard')->setChoices([
                'Asia' => 'Asia',
                'Europa' => 'Europa',
                'USA' => 'USA'
            ])->canSelectMultiple())
            ->add(NumericFilter::new('Preis','Preis (Euro)'))
            ->add(ChoiceFilter::new('Regal')->setChoices([
                0 => 0,
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                6 => 6,
                7 => 7,
                8 => 8,
                9 => 9,
                10 => 10,
                11 => 11,
                12 => 12,
                13 => 13,
            ]));
    }
}
