<?php

namespace App\Controller\Admin;

use App\Entity\WarenEingang;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FormFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class WarenEingangCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WarenEingang::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateField::new('EingangDatum', 'Datum'),
            TextField::new('Bestellnummer', 'Bestellnr'),
            ChoiceField::new('WarenTyp')->setChoices(['Batterien' => 'Batterien', 'Sonstiges' => 'Sonstiges']),
            TextField::new('Rechnungsnummer', 'Rechnungsnr'),
            ImageField::new('rechnungsDatei','Rechnung')->setUploadDir('/public/uploads/rechnungen')->setTemplatePath('/bundles/EasyAdminBundle/crud/field/rechnung.html.twig')->setUploadedFileNamePattern('[year]-[month]-[day]-[name].[extension]'),
            TextField::new('LieferantName', 'Lieferant'),
            BooleanField::new('VollstaendingGeliefert', 'Vollst. geliefert'),
            TextareaField::new('FehlendeArtikel', 'Fehlende Artikel')->hideOnIndex(),
            BooleanField::new('PreiseAngepasst', 'Preise Angepasst'),
            BooleanField::new('ImBestandGebucht', 'Im Bestand gebucht'),
            TextField::new('VonWenGebucht', 'Gebucht (Name)'),
            TextareaField::new('SonstigesKommentarWare', 'Sonstiges Kommentar (Ware)')->hideOnIndex(),
            BooleanField::new('VollBezahlt', 'Voll bezahlt'),
            TextareaField::new('SonstigesKommentarFinanzen', 'Sonstiges Kommentar (Finanzen)')->hideOnIndex(),
            TextField::new('Freigabe', 'Freigabe (Name)')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setFormThemes(['waren-eingang/form_theme.html.twig'])
            ->setEntityLabelInSingular('Waren Eingang')
            ->setEntityLabelInPlural('Waren Eingang')
            ->setDefaultSort(['id' => 'DESC'])
            ->showEntityActionsInlined();
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
            ->add('LieferantName')
            ->add('VollstaendingGeliefert')
            ->add('ImBestandGebucht')
            ->add('VonWenGebucht')
            ->add('VollBezahlt')
            ->add('Freigabe')
            ->add(ChoiceFilter::new('WarenTyp')->setChoices(['Batterien' => 'Batterien', 'Sonstiges' => 'Sonstiges']))
            ->add('PreiseAngepasst')
            //->add(EntityFilter::new('BeNr'))
            ;
    }
}
