<?php

namespace App\Controller\Admin;

use App\Entity\Benutzer;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class BenutzerCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordEncoder;
    private Request $request;
    private Security $security;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, Security $security)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->request = Request::createFromGlobals();
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Benutzer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Mitarbeiter')
            ->setEntityLabelInPlural('Mitarbeiter')
            ->showEntityActionsInlined()// ...
            ;
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('name', 'Benutzername'),
            EmailField::new('email', 'E-Mail'),
            ImageField::new('avatar', 'Avatar')
                ->setBasePath('uploads/avatars')
                ->setUploadDir('public/uploads/avatars')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),
            TextField::new('password', 'Passwort')
                ->setRequired(false)->hideOnIndex()
                ->onlyWhenCreating()
                ->setFormType(PasswordType::class)
                ->formatValue(function ($value, $entity) {
                    return $entity;
                }),
            TextField::new('updatePassword', 'Passwort')
                ->setRequired(false)->hideOnIndex()
                ->onlyWhenUpdating()
                ->setFormTypeOption('mapped', false)
                ->setFormType(PasswordType::class)
                ->formatValue(function ($value, $entity) {
                    return $entity;
                }),
            ChoiceField::new('roles', 'Rolle')->setChoices([
                'Super Admin' => 'ROLE_SUPER_ADMIN',
                'Admin' => 'ROLE_ADMIN',
                'Kunden' => 'ROLE_USER',
                'Anfragen Angebote' => 'ROLE_ANFRAGEN',
                'Aufkleber' => 'ROLE_AUFKLEBER',
                'Waren Eingang' => 'ROLE_WAREN_EINGANG',
                'B-Ware / Retoure' => 'ROLE_BWARE_RETOURE',
                'Neusendungen' => 'ROLE_NEUSENDUNGEN',
                'Mitarbeiter' => 'ROLE_MITARBEITER',
                'Andere Berichte' => 'ROLE_ANDERE_BERICHTE'
            ])->renderExpanded()->allowMultipleChoices()
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // set new password with encoder interface
        if (method_exists($entityInstance, 'setPassword')) {

            $clearPassword = $this->request->request->all()['Benutzer']['updatePassword'];

            ///MyLog::info("clearPass:" . $clearPassword);

            // save password only if is set a new clearpass
            if (!empty($clearPassword) || !is_null($clearPassword)) {
                ////MyLog::info("clearPass not empty! encoding password...");
                $encodedPassword = $this->passwordEncoder->hashPassword($entityInstance, $clearPassword);
                $entityInstance->setPassword($encodedPassword);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL)->setPermission(Action::INDEX, 'ROLE_MITARBEITER');
    }

    public function detail(AdminContext $context): KeyValueStore|Response
    {
        $this->denyAccessUnlessGranted('POST_VIEW', $context->getEntity()->getInstance());
        return parent::detail($context); // TODO: Change the autogenerated stub
    }

    public function configureFilters(Filters $filters): Filters
    {
        $roles = [
            'Super Admin' => 'ROLE_SUPER_ADMIN',
            'Admin' => 'ROLE_ADMIN',
            'Mitarbeiter' => 'ROLE_MITARBEITER',
            'Anfragen Angebote' => 'ROLE_ANFRAGEN',
            'Waren Eingang' => 'ROLE_WAREN_EINGANG',
            'Aufkleber' => 'ROLE_AUFKLEBER',
            'B-Ware / Retoure' => 'ROLE_BWARE_RETOURE',
            'Neusendungen' => 'ROLE_NEUSENDUNGEN',
            'Andere Berichte' => 'ROLE_ANDERE_BERICHTE',
            'Kunden' => 'ROLE_USER'
        ];
        return $filters
            ->add(ArrayFilter::new('roles')->setChoices($roles))//->add(EntityFilter::new('BeNr'))
            ;
    }
}