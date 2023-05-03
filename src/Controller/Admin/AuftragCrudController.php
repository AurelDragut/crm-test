<?php

namespace App\Controller\Admin;

use App\Entity\Auftrag;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\InsufficientEntityPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Response;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;

class AuftragCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Auftrag::class;
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            FormField::addTab('Übersicht')->collapsible(),
            NumberField::new('id')->hideOnForm()->hideOnDetail(),
            TextField::new('Rechnung.vorname', 'Vorname')->setColumns(6)->hideOnIndex(),
            DateTimeField::new('Datum', 'Datum')->setColumns(6)->renderAsChoice()->setDisabled()->hideOnIndex(),
            TextField::new('Rechnung.nachname', 'Nachname')->setColumns(6)->hideOnIndex(),
            TextField::new('Rechnung.rechnungsnummer', 'RN/Auftr.')->setColumns(6)->hideOnIndex(),
            TextField::new('Rechnung.strasse', 'Straße')->setColumns(6)->hideOnIndex(),
            TextField::new('Rechnung.kdnr', 'KD-Nr.')->setColumns(6)->hideOnIndex(),
            TextField::new('Rechnung.plz', 'PLZ')->setColumns(6)->hideOnIndex(),
            AssociationField::new('Mitarbeiter')->setColumns(6)->setQueryBuilder(
                function (QueryBuilder $qb) {$qb->andWhere('(entity.roles LIKE :roles)')->setParameter('roles', "%MITARBEITER%");}
            )->formatValue(static function ($value, Auftrag $auftrag): ?string {
                return sprintf('%s&nbsp;(%s)', $value, ($auftrag->getMitarbeiter()->getAuftrags()->count() === 1)?'1 Auftrag':$auftrag->getMitarbeiter()->getAuftrags()->count().' Aufträge');
            }),
            TextField::new('Rechnung.ort', 'Stadt')->setColumns(6)->hideOnIndex(),
            DateTimeField::new('TerminFuerReparatur', 'Termin Für Reparatur')->setColumns(6)->renderAsChoice()->hideOnIndex(),
            TextField::new('Rechnung.land', 'Land')->setColumns(6)->hideOnIndex(),
            TextField::new('Rechnung.Email', 'e-Mail')->setColumns(6)->hideOnIndex(),
            TextField::new('Rechnung.Tel', 'Telefon')->setColumns(6)->hideOnIndex(),
            BooleanField::new('isArchiviert','Archiviert')->hideOnForm()->setPermission('ROLE_MITARBEITER'),

            FormField::addTab('Status & Historie')->collapsible(),
            TextField::new('AktuellerStatus', 'Aktueller Status')->setDisabled()->hideOnIndex(),
            AssociationField::new('Status')->setColumns(6)->hideOnDetail()->formatValue(static function ($value, Auftrag $auftrag): ?string {
                return sprintf('%s&nbsp;(%s)', $value, ($auftrag->getStatus()->getAuftrags()->count() === 1)?'1 Auftrag':$auftrag->getStatus()->getAuftrags()->count().' Aufträge');
            }),
            CollectionField::new('AuftragStatuses', 'Chronik der Anfragestatus', array('mapped' => false))->hideOnIndex()->setDisabled()->allowAdd(false)->setColumns(12)->setPermission('ROLE_MITARBEITER')
                ->formatValue(function ($value) {
                    $items = explode(', ', $value);
                    $result = '';
                    if (count($items) > 0) {
                        $result = '<ul>';
                        foreach ($items as $item) {
                            $result .= '<Li>'.$item.'</Li>';
                        }
                        $result .= '</ul>';
                    }

                    return $result;
                }),
            BooleanField::new('sendEmail','Keine E-Mail senden')->setColumns(6)->hideOnIndex()->setPermission('ROLE_MITARBEITER'),
            TextareaField::new('beschreibung')->hideOnIndex()->renderAsHtml()->setDisabled()->setColumns(6),

            //AssociationField::new('Rechnung')->hideOnIndex(),
            TextField::new('Name')->setDisabled()->setColumns(6)->formatValue(static function ($value, Auftrag $auftrag): ?string {
                return sprintf('%s&nbsp;(%s)', $value, ($auftrag->getArtikel()->getAuftrag()->count() === 1)?'1 Auftrag':$auftrag->getArtikel()->getAuftrag()->count().' Aufträge');
            }),
            AssociationField::new('Grund')->setDisabled()->setColumns(6)->formatValue(static function ($value, Auftrag $auftrag): ?string {
                return sprintf('%s&nbsp;(%s)', $value, ($auftrag->getGrund()->getAuftrags()->count() === 1)?'1 Auftrag':$auftrag->getGrund()->getAuftrags()->count().' Aufträge');
            }),
            FormField::addTab('Produkte'),
            TextField::new('Name')->hideOnIndex()->hideOnDetail(),
            NumberField::new('Menge')->hideOnIndex(),
            AssociationField::new('Grund')->hideOnIndex()->hideOnDetail(),
            TextareaField::new('Beschreibung')->hideOnIndex()->hideOnDetail(),
            FormField::addTab('Dateien & Fotos'),
            ImageField::new('Bild_1', 'Bild #1')->hideOnIndex()->setBasePath('images/tickets')->setUploadDir('public/images/tickets')->setColumns(2)->setDisabled(),
            ImageField::new('Bild_2', 'Bild #2')->hideOnIndex()->setBasePath('images/tickets')->setUploadDir('public/images/tickets')->setColumns(2)->setDisabled(),
            ImageField::new('Bild_3', 'Bild #3')->hideOnIndex()->setBasePath('images/tickets')->setUploadDir('public/images/tickets')->setColumns(2)->setDisabled(),
            ImageField::new('Bild_4', 'Bild #4')->hideOnIndex()->setBasePath('images/tickets')->setUploadDir('public/images/tickets')->setColumns(2)->setDisabled(),
            ImageField::new('Bild_5', 'Bild #5')->hideOnIndex()->setBasePath('images/tickets')->setUploadDir('public/images/tickets')->setColumns(2)->setDisabled(),
            FormField::addTab('Historie'),
            FormField::addTab('Reparaturbericht'),
            AssociationField::new('kommentars','Kommentare')->onlyOnIndex(),
            CollectionField::new('kommentareListe', 'Kommentare')->onlyOnDetail()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->renderExpanded()
                ->setFormTypeOption('mapped', false)
                ->formatValue(function ($value) {
                    $value = explode(', ', $value);
                    $string = '';
                    foreach ($value as $val) {
                        $val = explode(' - ', $val);
                        $string .= '<div class="card mb-1">
                                      <div class="card-header">'.$val[0].((isset($val[2]))?' -  '.$val[2]:'').'</div>
                                      <div class="card-body">
                                        <p class="card-text">'.(isset($val[1])?' -  '.$val[1]:'').'</p>
                                      </div>
                                    </div>';
                    }
                    return $string; }),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the names of the Doctrine entity properties where the search is made on
            // (by default it looks for in all properties)
            ->setSearchFields(['Rechnung', 'Name', 'Grund.Inhalt'])

            ->setPageTitle('index', 'Auftr&auml;ge')

            ->setFormThemes(['admin/form_theme.html.twig'])

            ->setFormOptions(['validation_groups' => ['auftrag']], ['validation_groups' => ['auftrag']])

            ->overrideTemplates([
                'crud/detail' => 'anfrage/detail.html.twig',
                'crud/field/image' => 'admin/auftrag_image.html.twig',
                'crud/index' => 'admin/auftrag_index.html.twig'
            ])

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
            ->showEntityActionsInlined()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {

        $sendDHLRetourenSchein = Action::new('sendDHLRetourenSchein', 'DHL Retoure Link senden', 'fa-brands fa-dhl')
            ->linkToCrudAction('sendDHLRetourenSchein');
        $drucken = Action::new('drucken', 'drucken', 'fa fa-print')
            ->linkToCrudAction('drucken');
        $versandoptionen = Action::new('versandoptionen', 'Versandoptionen', 'fa fa-truck')
            ->linkToCrudAction('versandoptionen');
        $reparaturbericht = Action::new('reparaturbericht', 'Reparaturbericht drucken', 'fa fa-gear')
            ->linkToCrudAction('reparaturbericht');
        $begleitscheindrucken = Action::new('begleitscheindrucken', 'Begleitschein drucken', 'fa fa-barcode')
            ->linkToCrudAction('begleitscheindrucken');
        $neueKommentar = Action::new('neueKommentar', 'Neue Kommentar', 'fa fa-comment-alt')
            ->linkToCrudAction('neueKommentar');
        return $actions
            // ...
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            
            //->remove(Crud::PAGE_INDEX, Action::EDIT)
            //->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->add(Crud::PAGE_DETAIL, $neueKommentar)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_DETAIL, $begleitscheindrucken)
            ->add(Crud::PAGE_DETAIL, $reparaturbericht)
            ->add(Crud::PAGE_DETAIL, $versandoptionen)
            ->add(Crud::PAGE_DETAIL, $drucken)
            ->add(Crud::PAGE_DETAIL, $sendDHLRetourenSchein)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->setPermission(Crud::PAGE_INDEX, 'ROLE_MITARBEITER')
            ->setPermission($sendDHLRetourenSchein, 'ROLE_MITARBEITER')
            ->setPermission($drucken, 'ROLE_MITARBEITER')
            ->setPermission($versandoptionen, 'ROLE_MITARBEITER')
            ->setPermission($reparaturbericht, 'ROLE_MITARBEITER')
            ->setPermission($begleitscheindrucken, 'ROLE_MITARBEITER')
            ->setPermission($neueKommentar, 'ROLE_USER')


            /*->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)*/
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('Grund')
            ->add('Mitarbeiter')
            ->add('Status')
            //->add(EntityFilter::new('BeNr'))
            ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->isGranted('ROLE_ADMIN')) return $queryBuilder
            ->andWhere('entity.isArchiviert = false');

        if ($this->isGranted('ROLE_MITARBEITER'))
            return $queryBuilder->andWhere('entity.Mitarbeiter = \''.$this->getUser().'\'');
        $sql = $queryBuilder->leftJoin('App\\Entity\\Rechnung', 'Rechnung')
            ->Where('entity.Rechnung = Rechnung.Rechnungsnummer')
            ->andWhere('Rechnung.KdNr = \''.$this->getUser()->getKdNr().'\'')
            ->andWhere('entity.isArchiviert = false');
        return $sql;
    }

    #[NoReturn]
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $Bilder = [];
        if($entityInstance->getBild1() !== null) $Bilder[] = $entityInstance->getBild1();
        if($entityInstance->getBild2() !== null) $Bilder[] = $entityInstance->getBild2();
        if($entityInstance->getBild3() !== null) $Bilder[] = $entityInstance->getBild3();
        if($entityInstance->getBild4() !== null) $Bilder[] = $entityInstance->getBild4();
        if($entityInstance->getBild5() !== null) $Bilder[] = $entityInstance->getBild5();

        foreach ($Bilder as $Datei) {
            if (is_file($Datei)) unlink($Datei);
        }
        $entityManager->remove($entityInstance);
        $entityManager->flush();
    }

    public function sendDHLRetourenSchein(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        // add your logic here...
    }

    public function neueKommentar(AdminContext $context): RedirectResponse
    {
        $id = $context->getEntity()->getInstance()->getId();
        return $this->redirect('https://test.batterie-reklamation.de/admin?crudAction=new&crudControllerFqcn=App%5CController%5CAdmin%5CKommentarCrudController&menuIndex=5&referrer=?crudAction%3Dindex%26crudControllerFqcn%3DApp%255CController%255CAdmin%255CKommentarCrudController%26menuIndex%3D5%26submenuIndex%3D-1&submenuIndex=-1&AuftragId='.$id);
    }

    public function drucken(AdminContext $context): RedirectResponse
    {
        return $this->redirect($context->getReferrer().'&printURL=1');
    }

    public function detail(AdminContext $context): KeyValueStore|Response
    {
        return parent::detail($context);
    }
}
