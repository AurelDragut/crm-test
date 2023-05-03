<?php

namespace App\Controller\Admin;

use App\Entity\AnfrageAngebote;
use App\Entity\Benutzer;
use App\Repository\BenutzerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class AnfrageAngeboteCrudController extends AbstractCrudController
{
    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
    }

    public static function getEntityFqcn(): string
    {
        return AnfrageAngebote::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $benutzerRepository = $this->entityManager->getRepository(Benutzer::class);

        yield IdField::new('id')->onlyOnIndex();
        yield DateField::new('AnfrageDatum');
        yield TextField::new('KundenName');
        yield AssociationField::new('BearbeitetVon')->setFormTypeOption('query_builder', function (BenutzerRepository $benutzerRepository) {
            return $benutzerRepository->createQueryBuilder('entity')
                ->where('entity.roles like :role')
                ->setParameter('role', '%ROLE_ANFRAGEN%')
                ->orderBy('entity.name', 'ASC');
        });
        yield TextareaField::new('Kommentar')->hideOnIndex();
        yield TextField::new('Ergebnis', 'Ergebnis/Angebotsnummer');
        yield BooleanField::new('Abgeschlossen');
        yield ChoiceField::new('Status')->setChoices([
            'In Bearbeitung' => 'In Bearbeitung',
            'Auftrag' => 'Auftrag',
            'Abgelehnt' => 'Abgelehnt'
        ]);
        yield ChoiceField::new('KanalDerAnfrage')->setChoices([
                'E-Mail' => 'E-Mail',
                'Telefon' => 'Telefon',
                'Plattform' => 'Plattform'
            ])->renderExpanded();
        yield TextField::new('Name','E-Mail Adresse');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC'])
            ->showEntityActionsInlined()// ...
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
            ->add('BearbeitetVon')
            ->add('Abgeschlossen')
            ->add(ChoiceFilter::new('Status')->setChoices(['In Bearbeitung' => 'In Bearbeitung', 'Auftrag' => 'Auftrag', 'Abgelehnt' => 'Abgelehnt']))
            ->add(ChoiceFilter::new('KanalDerAnfrage')->setChoices(['E-Mail' => 'E-Mail', 'Telefon' => 'Telefon', 'Plattform' => 'Plattform']))
            //->add(EntityFilter::new('BeNr'))
            ;
    }
}
