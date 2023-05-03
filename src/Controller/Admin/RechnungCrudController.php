<?php

namespace App\Controller\Admin;

use App\Entity\Rechnung;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Core\Security;

class RechnungCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Rechnung::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('createdAt', 'Erstellt am'),
            TextField::new('rechnungsnummer'),
            TextField::new('KdNr', 'KundenNummer'),
            TextField::new('webshop')->hideOnIndex(),
            TextField::new('plattform'),
            TextField::new('Anrede')->hideOnIndex(),
            TextField::new('Vorname')->hideOnIndex(),
            TextField::new('Nachname')->hideOnIndex(),
            TextField::new('Strasse', 'Straße')->hideOnIndex(),
            TextField::new('PLZ')->hideOnIndex(),
            TextField::new('Ort')->hideOnIndex(),
            TextField::new('Land')->hideOnIndex(),
            TextField::new('Tel')->hideOnIndex(),
            TextField::new('Email')->hideOnIndex(),
            CollectionField::new('artikels','Artikel')->hideOnIndex()->renderExpanded()->formatValue(function ($value) {
                $items = explode(', ', $value);
                $result = '';
                if (count($items) > 0) {
                    $result .= '<ol>';
                    foreach ($items as $item) {
                        $result .= '<li> '.$item.'</li>';
                    }
                    $result .= '</ol>';
                }
                return $result;
            }),
            NumberField::new('artikels.count', 'Anzahl der Artikel')->hideOnDetail(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->setPermission(Action::NEW,'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE,'ROLE_ADMIN')
            ->setPermission(Crud::PAGE_INDEX, 'ROLE_MITARBEITER')
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            /*->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)*/
            ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the names of the Doctrine entity properties where the search is made on
            // (by default it looks for in all properties)
            ->setSearchFields(['Rechnungsnummer', 'KdNr.Vorname', 'KdNr.Nachname', 'Webshop', 'Plattform'])

            // defines the initial sorting applied to the list of entities
            // (user can later change this sorting by clicking on the table columns)
            ->setDefaultSort(['createdAt' => 'DESC'])

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
            ->setPaginatorFetchJoinCollection(true)
//            ->showEntityActionsAsDropdown()
            ->showEntityActionsInlined()
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Rechnungsnummer')
            //->add(EntityFilter::new('KdNr'))
            ->add('Webshop')
            ->add('Plattform')
            ->add('createdAt')
            ;
    }
}
