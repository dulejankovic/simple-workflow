<?php

namespace Xcentric\SimpleWorkflow\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Xcentric\SimpleWorkflow\Annotation\OnDelete;
use Xcentric\SimpleWorkflow\Annotation\OnInsert;
use Xcentric\SimpleWorkflow\Annotation\OnUpdate;
use Xcentric\SimpleWorkflow\Mapping\ActionObject;
use Xcentric\SimpleWorkflow\Mapping\Workflow;
use Xcentric\SimpleWorkflow\Service\Action\WorkerInterface;
use Xcentric\SimpleWorkflow\Service\Condition\ConditionInterface;

/**
 * Class HandleEntitiesEventService
 * @package Xcentric\SimpleWorkflow\Service
 */
class HandleEntitiesEventService
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->annotationReader = new AnnotationReader();
    }

    /**
     * @param ActionObject $actionObject
     * @param string $eventKey
     * @throws \Exception
     */
    public function handle($entity, string $eventKey, $changeSet = [])
    {

        $className = $this->entityManager->getMetadataFactory()->getMetadataFor(get_class($entity))->getName();
        $reflectionClass = new \ReflectionClass($className);

        /** @var OnInsert|OnUpdate|OnDelete $workflowAnnotation */
        $workflowAnnotation = $this->annotationReader->getClassAnnotation($reflectionClass, $eventKey);
        if($workflowAnnotation){
            $actionObject = new ActionObject();
            $actionObject->setEntityId($entity->getId());
            $actionObject->setDatamodelName($className);
            $actionObject->setChangeSet($changeSet);

            /** @var Workflow $workflows */
            $workflows = $workflowAnnotation->workflows;

            foreach ($workflows as $workflow){
                if($this->checkCondition($workflow, $actionObject)){
                    $this->createJob($workflow, $actionObject);
                }
            }
        }
    }

    /**
     * @param Workflow $workflow
     * @param ActionObject $actionObject
     * @return bool
     * @throws \Exception
     */
    private function checkCondition(Workflow $workflow, ActionObject $actionObject): bool
    {
        if($workflow->condition){
            if(!$this->container->has($workflow->condition)){
                throw new \Exception('Condition class not fount');
            }
            $condition = $this->container->get($workflow->condition);
            if($condition instanceof ConditionInterface){
                return $condition->check($actionObject);
            }else{
                throw new \Exception('Condition must be instance of ConditionInterface');
            }
        }
        return true;
    }

    /**
     * @param Workflow $workflow
     * @param ActionObject $actionObject
     * @return bool
     * @throws \Exception
     */
    private function createJob(Workflow $workflow, ActionObject $actionObject)
    {
        if($workflow->action){
            if(!$this->container->has($workflow->action)){
                throw new \Exception('Worker class not fount');
            }
            $worker = $this->container->get($workflow->action);
            if($worker instanceof WorkerInterface){
                if($workflow->isAsync) {
                    return $worker->later()->execute($workflow, $actionObject);
                }else{
                    return $worker->execute($workflow, $actionObject);
                }
            }else{
                throw new \Exception('Worker must be instance of WorkerInterface');
            }
        }
    }
}
