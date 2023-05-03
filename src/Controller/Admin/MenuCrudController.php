<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\MenuePunkt;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Component\Security\Core\Security;

class MenuCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Titel'),
            AssociationField::new('MenuePunkte')
                ->setFormTypeOptions([
                    'by_reference' => false
                ]),
            NumberField::new('position')
        ];
    }

    public function configureCrud(\EasyCorp\Bundle\EasyAdminBundle\Config\Crud $crud):\EasyCorp\Bundle\EasyAdminBundle\Config\Crud
    {
        return $crud
            // the names of the Doctrine entity properties where the search is made on
            // (by default it looks for in all properties)
            ->setSearchFields(['MenuePunkte', 'Titel'])

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
        return $actions
            // ...
            //->remove(Crud::PAGE_INDEX, Action::NEW)
            //->remove(Crud::PAGE_INDEX, Action::EDIT)
            //->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            //->remove(Crud::PAGE_DETAIL, Action::DELETE)
            //->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Titel')
            ->add('MenuePunkte')
            ;
    }
}
