<?php

namespace App\EventListener;

use App\Entity\Auftrag;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class AuftragStatusChangedNotifier
{

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postUpdate(Auftrag $auftrag, LifecycleEventArgs $event): void
    {
        if ($auftrag->getStatus() !== $event->getObject()->getStatus()) {

        }
    }

}