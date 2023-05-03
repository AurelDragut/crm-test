<?php

namespace App\Controller;

use App\Controller\Admin\AuftragCrudController;
use App\Entity\Benutzer;
use App\Entity\Artikel;
use App\Entity\Auftrag;
use App\Entity\Grund;
use App\Entity\Rechnung;
use App\Entity\Status;
use App\Form\AnfrageType;
use App\Entity\Menu;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AnfragenController extends AbstractController
{
    private ManagerRegistry $registry;
    /**
     * @var array|object[]
     */
    private array $menus;
    private HttpClientInterface $client;
    private Security $security;
    private RequestStack $requestStack;

    public function __construct(ManagerRegistry $registry, HttpClientInterface $client,Security $security, RequestStack $requestStack)
    {
        $this->registry = $registry;
        $this->client = $client;
        $entityManager = $this->registry->getManager();
        $this->menus = $entityManager->getRepository(Menu::class)->findAll();
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    #[Route('/anfragen', name: 'anfragen')]
    public function index(): Response
    {
        return $this->render('anfrage/index.html.twig', [
            'menu' => $this->menus
        ]);
    }

    #[Route('/anfrage/check', name: 'anfrage_pruefen')]
    public function check($errors = []): Response
    {
        $form1 = $this->createFormBuilder()
            ->add('re_nr', TextType::class, ['label' => 'Rechnungsnummer', 'row_attr' => ['class' => 'col-md-5'], 'attr' => ['value' => 'RE253031']])
            ->add('Postleitzahl', TextType::class, ['label' => 'PLZ', 'row_attr' => ['class' => 'col-md-5'], 'attr' => ['value' => '01936']])
            ->add('Pruefen', ButtonType::class, ['label' => 'Prüfen', 'row_attr' => ['class' => 'col-md-2']])
            ->getForm();

        return $this->render('anfrage/check.html.twig', ['form' => $form1->createView(), 'menu' => $this->menus, 'errors' => $errors]);
    }

    /**
     * @throws TransportExceptionInterface|\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    #[Route('/anfrage/neu', name: 'neu_anfrage')]
    public function new(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator, SluggerInterface $slugger, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer, AdminUrlGenerator $adminUrlGenerator): Response
    {
        if ($request->query->get('Rechnungsnummer')) {
            $Rechnungsnummer = $request->query->get('Rechnungsnummer');
        } else {
            $anfrage = $request->request->getIterator();
            $Rechnungsnummer = $anfrage['anfrage']['Rechnungsnummer'];
        }

        $entityManager = $doctrine->getManager();
        $Rechnung = $entityManager->getRepository(Rechnung::class)->findOneBy(['Rechnungsnummer' => $Rechnungsnummer]);

        $Artikel = $entityManager->getRepository(Artikel::class)->findBy(['Rechnung' => $Rechnungsnummer]);

        $session = $this->requestStack->getSession();

        if ($request->request->has('anfrage')) {

            $session->set('anrede', $request->request->getIterator()->offsetGet('anfrage')['Anrede']);
            $session->set('vorname', $request->request->getIterator()->offsetGet('anfrage')['Vorname']);
            $session->set('nachname', $request->request->getIterator()->offsetGet('anfrage')['Nachname']);
            $session->set('strasse', $request->request->getIterator()->offsetGet('anfrage')['Strasse']);
            $session->set('plz', $request->request->getIterator()->offsetGet('anfrage')['PLZ']);
            $session->set('stadt', $request->request->getIterator()->offsetGet('anfrage')['Stadt']);
            $session->set('land', $request->request->getIterator()->offsetGet('anfrage')['Land']);
            $session->set('telefon', $request->request->getIterator()->offsetGet('anfrage')['Telefon']);
        } else {
            $session->set('anrede', $Rechnung->getAnrede());
            $session->set('vorname', $Rechnung->getVorname());
            $session->set('nachname', $Rechnung->getNachname());
            $session->set('strasse', $Rechnung->getStrasse());
            $session->set('plz', $Rechnung->getPLZ());
            $session->set('stadt', $Rechnung->getOrt());
            $session->set('land', $Rechnung->getLand());
            $session->set('telefon', $Rechnung->getTel());
        }

        $request->query->set('Anrede', $session->get('anrede'));
        $request->query->set('Vorname', $session->get('vorname'));
        $request->query->set('Nachname', $session->get('nachname'));
        $request->query->set('Strasse', $session->get('strasse'));
        $request->query->set('PLZ', $session->get('plz'));
        $request->query->set('Stadt', $session->get('stadt'));
        $request->query->set('Land', $session->get('land'));
        $request->query->set('Telefon', $session->get('telefon'));
        //$request->query->set('Email', $Rechnung->getEmail());

        $Gruende = [];
        $GruendeList = $entityManager->getRepository(Grund::class)->findAll();
        foreach ($GruendeList as $item) {
            $Gruende[$item->getTitel()] = $item->getId();
        }

        // creates a task object and initializes some data for this example
        $form2 = $this->createForm(AnfrageType::class, $request->query->all(), ['require_due_date' => false, 'Artikel' => $Artikel, 'Gruende' => $Gruende, 'Indexes' => 1]);

        if ($request->isMethod('POST')) {

            //$form2->submit($request->request->getIterator()->getArrayCopy()['anfrage']);
            $form2->handleRequest($request);

            if ($form2->isSubmitted() && $form2->isValid()) {

                $entityManager = $doctrine->getManager();

                $rechnung = $doctrine->getRepository(Rechnung::class)->findOneBy(['Rechnungsnummer' => $request->request->all('anfrage')['Rechnungsnummer']]);

                $conn = $entityManager->getConnection();

                $sql = 'UPDATE rechnung SET email = ? where rechnungsnummer = \'' . $request->request->all('anfrage')['Rechnungsnummer'] . '\'';
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(1, $form2->getData()['Email']);
                $resultSet = $stmt->executeQuery();

                $auftraege = [];

                foreach ($request->request->all('anfrage') as $key => $value) {
                    if (str_contains($key, 'Artikel-')) {
                        $auftrag = new Auftrag();
                        $auftrag->setRechnung($rechnung);
                        $statusTicket = $doctrine->getRepository(Status::class)->find('1');
                        $now = new DateTime();
                        $auftrag->setDatum($now);
                        $artikel = $doctrine->getRepository(Artikel::class)->findOneBy(['Artikelnummer' => $value['artikelnummer'], 'Rechnung' => $request->request->all('anfrage')['Rechnungsnummer']]);
                        $auftrag->setArtikel($artikel);
                        $auftrag->setStatus($statusTicket);
                        $Mitarbeiter = $doctrine->getRepository(Benutzer::class)->find(22);
                        $auftrag->setMitarbeiter($Mitarbeiter);
                        $auftrag->setIsArchiviert(false);

                        $grund = $doctrine->getRepository(Grund::class)->find($value['grund']);

                        $auftrag->setName($value['bezeichnung']);

                        $rechnung->setEmail($request->query->get('Email'));

                        foreach ($request->files->all('anfrage') as $keyFile => $valueFile) {

                            if (!is_null($valueFile)) {

                                $originalFilename = pathinfo($request->files->all('anfrage')[$keyFile]->getClientOriginalName(), PATHINFO_FILENAME);
                                // this is needed to safely include the file name as part of the URL
                                $safeFilename = $slugger->slug($originalFilename);
                                $newFilename = $safeFilename . '-' . uniqid() . '.' . $request->files->all('anfrage')[$keyFile]->guessExtension();

                                // Move the file to the directory where brochures are stored
                                try {
                                    $request->files->all('anfrage')[$keyFile]->move(
                                        '/usr/home/batteq/public_html/test/public/images/tickets/',
                                        $newFilename
                                    );
                                } catch (FileException $e) {
                                    echo $e->getMessage();
                                }

                                $File[$keyFile] = new File('/usr/home/batteq/public_html/test/public/images/tickets/' . $newFilename);
                            }
                        }

                        if (isset($File['Bild_1'])) $auftrag->setBild1($File['Bild_1']);
                        if (isset($File['Bild_2'])) $auftrag->setBild2($File['Bild_2']);
                        if (isset($File['Bild_3'])) $auftrag->setBild3($File['Bild_3']);
                        if (isset($File['Bild_4'])) $auftrag->setBild4($File['Bild_4']);
                        if (isset($File['Bild_5'])) $auftrag->setBild5($File['Bild_5']);

                        $auftrag->setGrund($grund);
                        $auftrag->setMenge($value['menge']);
                        $auftrag->setBeschreibung($value['beschreibung']);
                        $auftrag->setSendEmail(false);

                        $randomPassword = $this->randomPassword();
                        $neue_kunden = false;

                        $kunden = $doctrine->getRepository(Benutzer::class)->findOneBy(['KdNr' => $rechnung->getKdNr()]);
                        if (!$kunden) {
                            $neue_kunden = true;
                            $kunden = new Benutzer();
                            $kunden->setName($rechnung->getVorname() . ' ' . $rechnung->getNachname());
                            $kunden->setEmail($rechnung->getEmail());
                            $kunden->setPassword($userPasswordHasher->hashPassword($kunden, $randomPassword));
                            $kunden->setRoles(['ROLE_USER']);
                            $kunden->setKdNr($rechnung->getKdNr());
                        }

                        $errors = $validator->validate($auftrag);

                        if (count($errors) > 0) {
                            /*
                             * Uses a __toString method on the $errors variable which is a
                             * ConstraintViolationList object. This gives us a nice string
                             * for debugging.
                             */

                            return $this->check($errors);
                        }

                        $entityManager->persist($auftrag);
                        $entityManager->persist($kunden);

                        $entityManager->flush();
                        $auftraege[] = $auftrag;

                        $this->client->request('GET', 'https://turboemma.de8.quickconnect.to/direct/webapi/entry.cgi?api=SYNO.Chat.External&method=chatbot&version=2&token=EUZUAzSMahLGUzjYVvtg6T86OFpsHrvjgS3cnRu6pC6j2zneksQpaIVpXGOtNVFV&payload={%22text%22:%20%22Neuer Auftrag <https%3A%2F%2Ftest.batterie-reklamation.de%2Fadmin%3FcrudAction%3Ddetail%26crudControllerFqcn%3DApp%255CController%255CAdmin%255CAuftragCrudController%26entityId%3D' . $auftrag->getId() . '%26menuIndex%3D6%26referrer%3D%3FcrudAction%253Dindex%2526crudControllerFqcn%253DApp%25255CController%25255CAdmin%25255CAuftragCrudController%2526menuIndex%253D6%2526submenuIndex%253D-1%26submenuIndex%3D-1|No.' . $auftrag->getId() . '>!%22%2C"user_ids"%3A[27]}');

                        $auftragLink = $adminUrlGenerator
                            ->setController(AuftragCrudController::class)
                            ->setAction(Crud::PAGE_DETAIL)
                            ->setEntityId($auftrag->getId())
                            ->generateUrl();

                        $message = 'Sehr geehrte(r) ' . $rechnung->getAnrede() . ' ' . $rechnung->getVorname() . ' ' . $rechnung->getNachname() . ',' . "<br />";
                        $message .= "<br />";
                        $message .= 'Sie haben ein Ticket bei <a href="https://www.batterie-reklamation.de">Batterie Reklamation</a> erstellt.' . "<br /><br />";
                        if ($neue_kunden) {
                            $message .= 'Sie k&ouml;nnen das auf jeder Zeitpunkt abschauen, wenn Sie sich mit den folgenden Zugangsdaten anmelden werden:' . "<br /><br />";
                            $message .= 'Benutzer: ' . $rechnung->getEmail() . "<br />";
                            $message .= 'Passwort: ' . $randomPassword . "<br /><br />";
                        }
                        $message .= '<a href="' . $auftragLink . '">Ihr neues Ticket</a><br /><br />';
                        $message .= 'Mit freundlichen Gr&uuml;&szlig;en,<br />';
                        $message .= 'Ihr Team von Batterie Reklamation!';

                        MailerController::sendEmail($mailer, 'dragut@siga-batterien.de', 'Neue Ticket erstellt', $message); // $rechnung->getEmail()


                        /*$adminMessage = 'Hi ' . $Mitarbeiter->getName() . ',' . "<br />";
                        $adminMessage .= "<br />";
                        $adminMessage .= 'ein neues Ticket wurde erstellt und das kannst du abschauen bei:' . "<br /><br />";
                        $adminMessage .= '<a href="' . $auftragLink . '">Neu Ticket</a><br /><br />';
                        $adminMessage .= 'Schöne Gr&uuml;&szlig;e,<br />';
                        $adminMessage .= 'Batterie Reklamation';*/

                        //MailerController::sendEmail($mailer, $Mitarbeiter->getEmail(), 'Neue Ticket von Test Batterie Reklamation erstellt', $adminMessage);
                    }
                }

                return $this->render('anfrage/auftrag_erstellt.html.twig', ['Auftrag' => $auftraege, 'menu' => $this->menus]);
            } else {

                $errors = $validator->validate($form2);

                if (count($errors) > 0) {
                    /*
                     * Uses a __toString method on the $errors variable which is a
                     * ConstraintViolationList object. This gives us a nice string
                     * for debugging.
                     */


                    return $this->check($errors);
                }
            }
        }

        return $this->render('anfrage/new.html.twig', ['form' => $form2->createView(), 'menu' => $this->menus]);
    }

    #[Route('/anfrage/ausfuellen', name: 'anfrage_ausfuellen')]
    public function fill(Request $request, ManagerRegistry $doctrine): Response
    {

        $Rechnungsnummer = $request->request->get('Rechnungsnummer');
        $PLZ = $request->request->get('Postleitzahl');

        $entityManager = $doctrine->getManager();
        $Rechnung = $entityManager->getRepository(Rechnung::class)->findOneBy(['Rechnungsnummer' => $Rechnungsnummer, 'PLZ' => $PLZ]);

        $Gruende = $entityManager->getRepository(Grund::class)->findAll();
        if (is_object($Rechnung)) {
            $Artikel = $entityManager->getRepository(Artikel::class)->findBy(['Rechnung' => $Rechnungsnummer]);
            return $this->render('anfrage/produkt.html.twig', ['Artikel' => $Artikel, 'Gruende' => $Gruende]);
        } else {
            $content = '<div class="alert alert-danger" role="alert">Es wurde keine Bestellung gefunden, die diese Werte enthält!</div>';
        }
        return new Response($content);
    }

    public function randomPassword(): string
    {
        $alphabet = explode(',', "a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,W,X,Y,Z,0,1,2,3,4,5,6,7,8,9,!,*,?,;,:");
        for ($i = 0; $i < 12; $i++) {
            $n = rand(0, count($alphabet) - 1);
            $pass[$i] = $alphabet[$n];
        }
        return implode('', $pass);
    }
}
