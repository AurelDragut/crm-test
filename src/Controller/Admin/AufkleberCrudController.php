<?php

namespace App\Controller\Admin;

use App\Entity\Aufkleber;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NullFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\Security\Core\Security;

class AufkleberCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Aufkleber::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // AssociationField::new('rechnung'),
            TextField::new('Artikelnummer'),
            TextField::new('Abmessungen'),
            TextField::new('BasisArtikel'),
            ChoiceField::new('Hersteller', 'Brand')->setChoices(['BSA' => 'BSA','Exakt' => 'Exakt', 'Langzeit' => 'Langzeit','NRG' => 'NRG', 'SIGA' => 'SIGA', 'Solis' => 'Solis', 'Tokohama' => 'Tokohama', 'Winter' => 'Winter']),
            UrlField::new('LinkFA'),
            UrlField::new('LinkWMD'),
            DateField::new('Bestellungsdatum')->setCustomOption('value',date('d.m.Y'))
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the names of the Doctrine entity properties where the search is made on
            // (by default it looks for in all properties)
            ->setSearchFields(['Artikelnummer', 'Abmessungen', 'Bestellungsdatum','BasisArtikel'])

            // defines the initial sorting applied to the list of entities
            // (user can later change this sorting by clicking on the table columns)
            ->setDefaultSort(['Bestellungsdatum' => 'DESC'])

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
            ->showEntityActionsInlined()
            ;
    }

    public function createEntity(string $entityFqcn) {
        $xyz = new Aufkleber();
        $xyz->setBestellungsdatum(new \DateTime('1970-01-01'));
        return $xyz;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $roles = ['Super Admin' => 'ROLE_SUPER_ADMIN', 'Admin' => 'ROLE_ADMIN', 'Mitarbeiter' => 'ROLE_MITARBEITER'];
        return $filters
            ->add(TextFilter::new('Abmessungen'))
            ->add(ChoiceFilter::new('Hersteller')->setChoices(['BSA' => 'BSA','Exakt' => 'Exakt', 'Langzeit' => 'Langzeit','NRG' => 'NRG', 'SIGA' => 'SIGA', 'Solis' => 'Solis', 'Tokohama' => 'Tokohama', 'Winter' => 'Winter']))
            ->add(DateTimeFilter::new('Bestellungsdatum'))
            ->add(TextFilter::new('BasisArtikel'))
            ;
    }
}
