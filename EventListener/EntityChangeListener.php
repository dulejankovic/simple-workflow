<?php

namespace Xcentric\SimpleWorkflow\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Xcentric\SimpleWorkflow\Service\HandleEntitiesEventService;
use Xcentric\SimpleWorkflow\Service\WorkflowQueueService;

/**
 * Class EntityChangeListener
 * @package Xcentric\SimpleWorkflow\EventListener
 */
class EntityChangeListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $scheduledInsertions = [];
    protected static $scheduledUpdates = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();

        /** @var WorkflowQueueService $queueList */
        $queueList = $this->container->get(WorkflowQueueService::class);


        if (count($unitOfWork->getScheduledEntityInsertions()) > 0) {
            $queueList->addEntities($unitOfWork->getScheduledEntityInsertions(), WorkflowQueueService::SCHEDULED_INSERTIONS);
        }
        if (count($unitOfWork->getScheduledEntityUpdates()) > 0) {
            foreach ($unitOfWork->getScheduledEntityUpdates() as $scheduledEntityUpdate) {
                $queueList->addEntity($scheduledEntityUpdate, WorkflowQueueService::SCHEDULED_UPDATES);
                $queueList->addEntityChangSet($unitOfWork->getEntityChangeSet($scheduledEntityUpdate),$scheduledEntityUpdate, WorkflowQueueService::SCHEDULED_UPDATES);
            }
        }
        if (count($unitOfWork->getScheduledEntityDeletions()) > 0) {
            $queueList->addEntities($unitOfWork->getScheduledEntityDeletions(), WorkflowQueueService::SCHEDULED_DELETE);
        }
    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        /** @var HandleEntitiesEventService $entitiesChangeHandler */
        $entitiesChangeHandler = $this->container->get(HandleEntitiesEventService::class);

        /** @var WorkflowQueueService $queueList */
        $queueList = $this->container->get(WorkflowQueueService::class);

        while ($entity = $queueList->popEntity(WorkflowQueueService::SCHEDULED_INSERTIONS)){
            $entitiesChangeHandler->handle($entity, WorkflowQueueService::SCHEDULED_INSERTIONS);
        }

        while ($entity = $queueList->popEntity(WorkflowQueueService::SCHEDULED_UPDATES)){
            $entitiesChangeHandler->handle($entity, WorkflowQueueService::SCHEDULED_UPDATES, $queueList->getEntityChangeSet($entity,WorkflowQueueService::SCHEDULED_UPDATES));
        }

        while ($entity = $queueList->popEntity(WorkflowQueueService::SCHEDULED_DELETE)){
            $entitiesChangeHandler->handle($entity, WorkflowQueueService::SCHEDULED_DELETE);
        }
    }

    /**
     * @param AbstractEntity $entity
     * @param $changeSet
     * @throws \Exception
     */
    protected function handleUpdated(AbstractEntity $entity, $changeSet)
    {
        /** @var HandleEntitiesEventService $entitiesChangeHandler */
        $entitiesChangeHandler = $this->container->get('workflow.service_simple_workflow.handle_entities_event_service');
        $entitiesChangeHandler->handle($actionObject, 'update');
    }
}
