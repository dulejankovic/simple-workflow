<?php

namespace Xcentric\SimpleWorkflow\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Xcentric\SimpleWorkflow\Annotation\OnDelete;
use Xcentric\SimpleWorkflow\Annotation\OnInsert;
use Xcentric\SimpleWorkflow\Annotation\OnUpdate;

/**
 * Class WorkflowQueueService
 * @package Xcentric\SimpleWorkflow\Service
 */
class WorkflowQueueService
{
    const SCHEDULED_INSERTIONS = OnInsert::class;
    const SCHEDULED_UPDATES = OnUpdate::class;
    const SCHEDULED_DELETE = OnDelete::class;

    /**
     * @var EntityManager
     */
    private $entityManager;

    private $entities;

    private $entitiesChangeSets;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entities = array(
            self::SCHEDULED_INSERTIONS => array(),
            self::SCHEDULED_UPDATES => array(),
            self::SCHEDULED_DELETE => array()
        );
    }

    /**
     * @param $entity
     * @param int $listType
     * @return WorkflowQueueService
     */
    public function addEntity($entity, string $listType = self::SCHEDULED_INSERTIONS): self
    {
        if(empty($entity)){
            return $this;
        }

        $this->entities[$listType][] = $entity;
        return $this;
    }

    /**
     * @param array $entities
     * @param string $listType
     * @return WorkflowQueueService
     */
    public function addEntities(array $entities, string $listType = self::SCHEDULED_INSERTIONS): self
    {
        if(empty($entities)){
            return $this;
        }
        $this->entities[$listType] = array_merge($this->entities[$listType], $entities);
        return $this;
    }

    /**
     * @param string $listType
     * @return array
     */
    public function getEntities(string $listType = self::SCHEDULED_INSERTIONS): array
    {
        return $this->entities[$listType];
    }

    /**
     * @param string $listType
     * @return mixed
     */
    public function popEntity(string $listType = self::SCHEDULED_INSERTIONS)
    {
        return array_pop($this->entities[$listType]);
    }

    /**
     * @param string $listType
     * @return WorkflowQueueService
     */
    public function removeAllEntities(string $listType = self::SCHEDULED_INSERTIONS): self
    {
        $this->entities[$listType] = [];
        return $this;
    }

    /**
     * @param $changeSet
     * @param $entity
     * @param string $listType
     * @return WorkflowQueueService
     */
    public function addEntityChangSet($changeSet, $entity, string $listType = self::SCHEDULED_INSERTIONS): self
    {
        if(empty($changeSet) || empty($entity)){
            return $this;
        }
        if(!isset($this->entitiesChangeSets[$listType])){
            $this->entitiesChangeSets[$listType] = [];
        }
        $this->entitiesChangeSets[$listType][get_class($entity).'-'.$entity->getId()] = $changeSet;
        return $this;
    }

    /**
     * @param $entity
     * @param string $listType
     * @return array
     */
    public function getEntityChangeSet($entity, string $listType = self::SCHEDULED_INSERTIONS): array
    {
        if(isset($this->entitiesChangeSets[$listType]) && isset($this->entitiesChangeSets[$listType][get_class($entity).'-'.$entity->getId()])) {
            return $this->entitiesChangeSets[$listType][get_class($entity).'-'.$entity->getId()];
        }
        return [];
    }
}
