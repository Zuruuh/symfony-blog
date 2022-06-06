<?php

namespace App\EventListener;

use App\Common\Timestamp\TimestampedInterface;
use App\Entity\Post;
use DateTime;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;

class TimestampableListener
{
    public function onFlush(OnFlushEventArgs $event): void
    {
        $uow = $event->getEntityManager()->getUnitOfWork();
        $entities = [...$uow->getScheduledEntityUpdates(), $uow->getScheduledEntityInsertions()];

        foreach ($entities as $entity) {
            if ($entity instanceof TimestampedInterface) {
                $this->refreshLastUpdate($uow, $entity);
            }
        }
    }

    private function refreshLastUpdate(UnitOfWork $uow, TimestampedInterface $entity): void
    {
        $entity->setUpdatedAt(new DateTime());
        $class = $uow->getEntityPersister(Post::class)->getClassMetadata();

        $uow->recomputeSingleEntityChangeSet($class, $entity);
    }
}
