<?php

namespace App\Controller\Admin;

use App\Entity\Grund;
use App\Entity\Rechnung;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Security\Core\Security;

class GrundCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Grund::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Inhalt')->formatValue(static function ($value, Grund $grund): ?string {
                return sprintf('%s&nbsp;(%s)', $value, ($grund->getAuftrags()->count() === 1)?'1 Auftrag':$grund->getAuftrags()->count().' Aufträge');
            }),
            TextField::new('ZielEmail'),
            TextField::new('Titel', 'Betreff der E-Mail')
        ];
    }

    public function configureCrud(\EasyCorp\Bundle\EasyAdminBundle\Config\Crud $crud):\EasyCorp\Bundle\EasyAdminBundle\Config\Crud
    {
        return $crud
            // the names of the Doctrine entity properties where the search is made on
            // (by default it looks for in all properties)
            ->setSearchFields(['Inhalt', 'ZielEmail', 'Titel'])

            // defines the initial sorting applied to the list of entities
            // (user can later change this sorting by clicking on the table columns)
            // ->setDefaultSort(['Id' => 'ASC'])

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

    public function configureActions(Actions $actions): Actions
    {
        return $actions->setPermission(Crud::PAGE_INDEX,'ROLE_MITARBEITER')->setPermission(Crud::PAGE_DETAIL,'ROLE_MITARBEITER')
            // ...
            //->remove(Crud::PAGE_INDEX, Action::NEW)
            //->remove(Crud::PAGE_INDEX, Action::EDIT)
            //->remove(Crud::PAGE_INDEX, Action::DELETE)
            //->add(Crud::PAGE_INDEX, Action::DETAIL)
            /*->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)*/
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Inhalt')
            ->add('ZielEmail')
            ->add('Titel')
            ;
    }
}
