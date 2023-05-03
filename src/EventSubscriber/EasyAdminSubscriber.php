<?php

namespace App\EventSubscriber;

use App\Entity\Auftrag;
use App\Entity\AuftragStatus;
use App\Entity\Benutzer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private ManagerRegistry $doctrine;
    private Security $security;
    private HttpClientInterface $client;
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $doctrine, Security $security, HttpClientInterface $client, EntityManagerInterface $em)
    {
        $this->doctrine = $doctrine;
        $this->security = $security;
        $this->client = $client;
        $this->em = $em;
    }
    

    #[ArrayShape([AfterEntityUpdatedEvent::class => "string[]", BeforeEntityUpdatedEvent::class => "string[]"])]
    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityUpdatedEvent::class => ['setAuftragStatus'],
            BeforeEntityUpdatedEvent::class => ['setAuftragMitarbeiter']
        ];
    }

    public function setAuftragStatus(AfterEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Auftrag)) {
            return;
        }

        $status = $entity->getStatus();
        $entityManager = $this->doctrine->getManager();

        $loggedUser = $entityManager->getRepository(Benutzer::class)->find($this->security->getUser()->getId());

        $auftragStatusRepository = $entityManager->getRepository(AuftragStatus::class);
        $AuftragStatus = $auftragStatusRepository->findtheLatestfromAuftrag(['auftrag_id' =>$entity->getId()]);

        if (!isset($AuftragStatus)) {
            $conn = $entityManager->getConnection();

            $sql = 'INSERT INTO auftrag_status SET auftrag_id = "'.$entity->getId().'", status_id = "'.$status->getId().'", datum = "'.date('Y-m-d H:i:s').'", mitarbeiter_id ="'.$loggedUser->getId().'"';
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery();


        } elseif ($status !== $AuftragStatus->getStatus()) {
            $conn = $entityManager->getConnection();

            $sql = 'INSERT INTO auftrag_status SET auftrag_id = "'.$entity->getId().'", status_id = "'.$status->getId().'", datum = "'.date('Y-m-d H:i:s').'", mitarbeiter_id ="'.$loggedUser->getId().'"';
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery();
        }

    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function setAuftragMitarbeiter(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Auftrag)) {
            return;
        }

        $uow = $this->em->getUnitOfWork();
        $uow->computeChangeSets();

        $changes = $uow->getEntityChangeSet($entity);

        if (array_key_exists('Mitarbeiter', $changes)) {

            $entityManager = $this->doctrine->getManager();
            $loggedUser = $entityManager->getRepository(Benutzer::class)->find($this->security->getUser()->getId());

            $mitarbeiter = array(
                'Service' => 'Irini',
                'Kunden Service' => 'Irini',
                'Verkauf' => 'Paola',
                'Support' => 'JanniLager',
                'Mail' => 'Patrick',
                'Angela' => 'Angela'
            );
              $this->client->request('GET', 'https://turboemma.de8.quickconnect.to/direct/webapi/entry.cgi?api=SYNO.Chat.External&method=chatbot&version=2&token=EUZUAzSMahLGUzjYVvtg6T86OFpsHrvjgS3cnRu6pC6j2zneksQpaIVpXGOtNVFV&payload={%22text%22:%20%22%40'.$loggedUser->getName().' hat dir den Auftrag <https%3A%2F%2Ftest.batterie-reklamation.de%2Fadmin%3FcrudAction%3Ddetail%26crudControllerFqcn%3DApp%255CController%255CAdmin%255CAuftragCrudController%26entityId%3D'.$entity->getId().'%26menuIndex%3D6%26referrer%3D%3FcrudAction%253Dindex%2526crudControllerFqcn%253DApp%25255CController%25255CAdmin%25255CAuftragCrudController%2526menuIndex%253D6%2526submenuIndex%253D-1%26submenuIndex%3D-1|No.'.$entity->getId().'> geschickt!%22%2C"user_ids"%3A[27]}');
            //$this->client->request('GET', 'https://turboemma.de8.quickconnect.to/direct/webapi/entry.cgi?api=SYNO.Chat.External&method=incoming&version=2&token=%22zbIUA1nCEZWx4AjZBd3VxTCY77KwinaBnAk5y8m2grEgKEK019DBlOR2NoX799y8%22&payload={%22text%22:%20%22%40'.$loggedUser->getName().' hat den Auftrag <https%3A%2F%2Ftest.batterie-reklamation.de%2Fadmin%3FcrudAction%3Ddetail%26crudControllerFqcn%3DApp%255CController%255CAdmin%255CAuftragCrudController%26entityId%3D'.$entity->getId().'%26menuIndex%3D6%26referrer%3D%3FcrudAction%253Dindex%2526crudControllerFqcn%253DApp%25255CController%25255CAdmin%25255CAuftragCrudController%2526menuIndex%253D6%2526submenuIndex%253D-1%26submenuIndex%3D-1|No.'.$entity->getId().'> an %40'.$mitarbeiter[$entity->getMitarbeiter()->getName()].' geschickt!%22}');

        }
    }
}