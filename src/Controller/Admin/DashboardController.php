<?php

namespace App\Controller\Admin;

use App\Entity\AnfrageAngebote;
use App\Entity\Aufkleber;
use App\Entity\AuslandsVerkaeufe;
use App\Entity\BasisArtikel;
use App\Entity\BatterieRegal;
use App\Entity\Benutzer;
use App\Entity\Artikel;
use App\Entity\Auftrag;
use App\Entity\BWare;
use App\Entity\EinkaufzuVerkaufPreis;
use App\Entity\EmailVorlag;
use App\Entity\Frage;
use App\Entity\Grund;
use App\Entity\Haendler;
use App\Entity\Kommentar;
use App\Entity\MargenCheck;
use App\Entity\Meldung;
use App\Entity\Menu;
use App\Entity\MenuePunkt;
use App\Entity\NeueArtikelPerformance;
use App\Entity\Neusendung;
use App\Entity\PotenzialBasisArtikel;
use App\Entity\RausgeflogeneArtikel;
use App\Entity\Rechnung;
use App\Entity\Seite;
use App\Entity\Slide;
use App\Entity\Status;
use App\Entity\TopBasisArtikelNullBestand;
use App\Entity\VerkaufsArtikelPerformance;
use App\Entity\WarenEingang;
use App\Repository\ArtikelRepository;
use App\Repository\AuftragRepository;
use App\Repository\BenutzerRepository;
use App\Repository\GrundRepository;
use App\Repository\RechnungRepository;
use App\Repository\StatusRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Symfony\Component\Translation\t;

class DashboardController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SIGA CRM')
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Homepage', 'fas fa-home', 'app_homepage'); // ->setLinkTarget('_blank')
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::section('Admin')->setPermission('ROLE_MITARBEITER');
        yield MenuItem::linkToCrud('Aufkleber', 'fa fa-tags', Aufkleber::class)->setPermission('ROLE_AUFKLEBER');
        yield MenuItem::linkToCrud('Anfragen Angebote', 'fa fa-tags', AnfrageAngebote::class)->setPermission('ROLE_ANFRAGEN');
        yield MenuItem::linkToUrl('Dateimanager (Neuer Tab)', 'fa fa-file-o', '/manager/?conf=default&tree=0&view=list&route=/rechnungen')->setPermission('ROLE_WAREN_EINGANG')->setLinkTarget('_blank');
        yield MenuItem::linkToCrud('Waren Eingang', 'fa fa-tags', WarenEingang::class)->setPermission('ROLE_WAREN_EINGANG');
        yield MenuItem::linkToCrud('B-Ware', 'fa fa-tags', BWare::class)->setPermission('ROLE_BWARE_RETOURE');
        yield MenuItem::linkToCrud('Neusendungen', 'fa fa-tags', Neusendung::class)->setPermission('ROLE_NEUSENDUNGEN');
        yield MenuItem::linkToCrud('Meldungen', 'fa fa-tags', Meldung::class)->setPermission('ROLE_NEUSENDUNGEN');
        yield MenuItem::linkToCrud('Batterien In Regale', 'fa fa-tags', BatterieRegal::class);
        yield MenuItem::linkToCrud('E-Mail-Vorlagen', 'fa fa-tags', EmailVorlag::class)->setPermission('ROLE_MITARBEITER');

        yield MenuItem::subMenu('Andere Berichte')->setPermission('ROLE_ANDERE_BERICHTE')->setSubItems([
            MenuItem::linkToCrud('Ebay/Amazon Kunden Optimierung','fa fa-tags', RausgeflogeneArtikel::class)->setPermission('ROLE_ANDERE_BERICHTE'),
            MenuItem::linkToCrud('EinkaufsPreis zu VerkaufsPreis','fa fa-tags', EinkaufzuVerkaufPreis::class)->setPermission('ROLE_ANDERE_BERICHTE'),
            MenuItem::linkToCrud('MargenCheck','fa fa-tags', MargenCheck::class)->setPermission('ROLE_ANDERE_BERICHTE'),
            MenuItem::linkToCrud('Top BasisArtikel mit 0 Bestand','fa fa-tags', TopBasisArtikelNullBestand::class)->setPermission('ROLE_ANDERE_BERICHTE'),
            MenuItem::linkToCrud('Potenzial BasisArtikel','fa fa-tags', PotenzialBasisArtikel::class)->setPermission('ROLE_ANDERE_BERICHTE'),
            MenuItem::linkToCrud('Neue Artikel Performance','fa fa-tags', NeueArtikelPerformance::class)->setPermission('ROLE_ANDERE_BERICHTE'),
            MenuItem::linkToCrud('Auslands Verkäufe','fa fa-tags', AuslandsVerkaeufe::class)->setPermission('ROLE_ANDERE_BERICHTE'),
            MenuItem::linkToCrud('VerkaufsArtikel Performance','fa fa-tags', VerkaufsArtikelPerformance::class)->setPermission('ROLE_ANDERE_BERICHTE'),
        ]);

        yield MenuItem::linkToUrl('Mitarbeiter', 'fa fa-tags', '?referrer=%2Fadmin%3FcrudAction%3Dindex%26crudControllerFqcn%3DApp%255CController%255CAdmin%255CBenutzerCrudController%26menuIndex%3D12%26submenuIndex%3D3&crudAction=index&crudControllerFqcn=App\Controller\Admin\BenutzerCrudController&menuIndex=12&submenuIndex=3&filters[roles][comparison]=not+like&filters[roles][value]=ROLE_USER')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToLogout(t('user.sign_out', domain: 'EasyAdminBundle'),'fas fa-sign-out-alt');
    }

    public function configureUserMenu(Benutzer|UserInterface $user): UserMenu
    {
        $userMenuItems = [];

        if (class_exists(LogoutUrlGenerator::class)) {
            $userMenuItems[] = MenuItem::section();
            $userMenuItems[] = MenuItem::linkToUrl('Mein Profil', 'fas fa-user', '/admin?crudAction=detail&crudControllerFqcn=App\Controller\Admin\BenutzerCrudController&entityId=' . $user->getId() . '&menuIndex=16&referrer=?crudAction%3Dindex%26crudControllerFqcn%3DApp%255CController%255CAdmin%255CBenutzerCrudController%26menuIndex%3D16%26submenuIndex%3D-1&submenuIndex=-1');
            $userMenuItems[] = MenuItem::linkToLogout(t('user.sign_out', domain: 'EasyAdminBundle'), 'fa-sign-out');
        }
        if ($this->isGranted(Permission::EA_EXIT_IMPERSONATION)) {
            $userMenuItems[] = MenuItem::linkToExitImpersonation(t('user.exit_impersonation', domain: 'EasyAdminBundle'), 'fa-user-lock');
        }

        $userName = '';
        if (method_exists($user, '__toString')) {
            $userName = (string)$user;
        } elseif (method_exists($user, 'getUserIdentifier')) {
            $userName = $user->getUserIdentifier();
        } elseif (method_exists($user, 'getUsername')) {
            $userName = $user->getUsername();
        }

        $avatar = $user->getAvatar() ?? 'no-avatar-350x350-300x300.jpg';

        return UserMenu::new()
            ->displayUserName()
            ->displayUserAvatar()
            ->setName($userName)
            ->setAvatarUrl('/uploads/avatars/' . $avatar)
            ->setMenuItems($userMenuItems);
    }

    /*public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }*/
}
