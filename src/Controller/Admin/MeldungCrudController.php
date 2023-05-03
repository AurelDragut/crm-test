<?php

namespace App\Controller\Admin;

use App\Entity\Meldung;
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

class MeldungCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Meldung::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnDetail(),
            TextField::new('Rechnungsnummer'),
            TextField::new('StuckArtikelnummer', 'Stück Artikelnummer'),
            DateField::new('Datum'),
            ChoiceField::new('Ergebnis', 'Entscheidung')->setFormTypeOptions(['attr.onchange' => 'switcher()'])
                ->setChoices([
                'NeuSendung' => 'NeuSendung',
                'Gutschrift' => 'Gutschrift',
                'Kulanzerstattung' => 'Kulanzerstattung'
            ]),
            ChoiceField::new('Kulanzerstattung', 'Kulanzerstattung (%)')->setFormTypeOptions(['row_attr.id' => 'Beitrag', 'row_attr.class' => 'invisible'])
                ->setChoices(
                [
                    '5%' => '5%',
                    '10%' => '10%',
                    '15%' => '15%',
                    '20%' => '20%',
                    '25%' => '25%',
                    '30%' => '30%',
                    '35%' => '35%',
                    '40%' => '40%',
                    '45%' => '45%',
                    '50%' => '50%'
                ]
            ),
            ChoiceField::new('KulanzerstattungEuro', 'Kulanzerstattung (€)')->setFormTypeOptions(['row_attr.id' => 'ProzentEuro', 'row_attr.class' => 'invisible'])
                ->setChoices(
                    [
                        '5€' => '5€',
                        '10€' => '10€',
                        '15€' => '15€',
                        '20€' => '20€',
                        '25€' => '25€',
                        '30€' => '30€',
                        '35€' => '35€',
                        '40€' => '40€',
                        '45€' => '45€',
                        '50€' => '50€'
                    ]
                )
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the names of the Doctrine entity properties where the search is made on
            // (by default it looks for in all properties)
            ->setSearchFields(['Rechnungsnummer', 'StuckArtikelnummer', 'Datum'])

            ->setPageTitle('index', 'Transportschaden DPD beim Kunden zugestellt ab. 22.02.23')

            ->setFormThemes(['admin/meldung_form_theme.html.twig'])

            // defines the initial sorting applied to the list of entities
            // (user can later change this sorting by clicking on the table columns)
            //->setDefaultSort(['id' => 'DESC'])

            // the max number of entities to display per page
            ->setPaginatorPageSize(15)
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
            ->setDefaultSort(['Datum' => 'DESC'])
            ->showEntityActionsInlined()
            ;
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
            ->add('Datum')
            ->add(ChoiceFilter::new('Ergebnis', 'Entscheidung')->setChoices([
                'NeuSendung' => 'NeuSendung',
                'Gutschrift' => 'Gutschrift',
                'Kulanzerstattung' => 'Kulanzerstattung'
            ]))
            ->add(ChoiceFilter::new('Kulanzerstattung', 'Kulanzerstattung (%)')->setChoices([
                '5%' => '5%',
                '10%' => '10%',
                '15%' => '15%',
                '20%' => '20%',
                '25%' => '25%',
                '30%' => '30%',
                '35%' => '35%',
                '40%' => '40%',
                '45%' => '45%',
                '50%' => '50%'
            ]))
            ->add(ChoiceFilter::new('KulanzerstattungEuro', 'Kulanzerstattung (€)')->setChoices([
                '5€' => '5€',
                '10€' => '10€',
                '15€' => '15€',
                '20€' => '20€',
                '25€' => '25€',
                '30€' => '30€',
                '35€' => '35€',
                '40€' => '40€',
                '45€' => '45€',
                '50€' => '50€'
            ]))
            //->add(EntityFilter::new('BeNr'))
            ;
    }
}
