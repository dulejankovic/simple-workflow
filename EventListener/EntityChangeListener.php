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

    /**
     * @var WorkflowQueueService
     */
    protected $queueList;

    /**
     * @var HandleEntitiesEventService
     */
    protected $entitiesChangeHandler;

    protected $scheduledInsertions = [];
    protected static $scheduledUpdates = [];

    public function __construct(ContainerInterface $container, WorkflowQueueService $queueList, HandleEntitiesEventService $entitiesChangeHandler)
    {
        $this->container = $container;
        $this->queueList = $queueList;
        $this->entitiesChangeHandler = $entitiesChangeHandler;
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $unitOfWork = $args->getEntityManager()->getUnitOfWork();

        if (count($unitOfWork->getScheduledEntityInsertions()) > 0) {
            $this->queueList->addEntities($unitOfWork->getScheduledEntityInsertions(), WorkflowQueueService::SCHEDULED_INSERTIONS);
        }
        if (count($unitOfWork->getScheduledEntityUpdates()) > 0) {
            foreach ($unitOfWork->getScheduledEntityUpdates() as $scheduledEntityUpdate) {
                $this->queueList->addEntity($scheduledEntityUpdate, WorkflowQueueService::SCHEDULED_UPDATES);
                $this->queueList->addEntityChangSet($unitOfWork->getEntityChangeSet($scheduledEntityUpdate),$scheduledEntityUpdate, WorkflowQueueService::SCHEDULED_UPDATES);
            }
        }
        if (count($unitOfWork->getScheduledEntityDeletions()) > 0) {
            $this->queueList->addEntities($unitOfWork->getScheduledEntityDeletions(), WorkflowQueueService::SCHEDULED_DELETE);
        }
    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        while ($entity = $this->queueList->popEntity(WorkflowQueueService::SCHEDULED_INSERTIONS)){
            $this->entitiesChangeHandler->handle($entity, WorkflowQueueService::SCHEDULED_INSERTIONS);
        }

        while ($entity = $this->queueList->popEntity(WorkflowQueueService::SCHEDULED_UPDATES)){
            $this->entitiesChangeHandler->handle($entity, WorkflowQueueService::SCHEDULED_UPDATES, $this->queueList->getEntityChangeSet($entity,WorkflowQueueService::SCHEDULED_UPDATES));
        }

        while ($entity = $this->queueList->popEntity(WorkflowQueueService::SCHEDULED_DELETE)){
            $this->entitiesChangeHandler->handle($entity, WorkflowQueueService::SCHEDULED_DELETE);
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
