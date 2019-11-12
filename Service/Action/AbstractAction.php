<?php

namespace Xcentric\SimpleWorkflow\Service\Action;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Dtc\QueueBundle\Model\Worker;
use Xcentric\SimpleWorkflow\Mapping\ActionObject;

/**
 * Class AbstractAction
 * @package WorkflowBundle\Service\SimpleWorkflow\Action
 */
abstract class AbstractAction extends Worker
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * UpdateLeasedEmployeeStatus constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
    }

    public function getEntity(ActionObject $actionObject)
    {
        $entity = null;
        if($actionObject->getDatamodelName() && $actionObject->getEntityId()) {
            $entityClassName = str_replace('.', ':', $actionObject->getDatamodelName());
            $entity = $this->entityManager->getRepository($entityClassName)->find($actionObject->getEntityId());
        }
        return $entity;
    }
}