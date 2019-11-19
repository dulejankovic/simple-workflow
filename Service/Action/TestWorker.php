<?php

namespace Xcentric\SimpleWorkflow\Service\Action;

use Xcentric\SimpleWorkflow\Mapping\ActionObject;
use Xcentric\SimpleWorkflow\Annotation\Workflow;

/**
 * Class TestWorker
 * @package WorkflowBundle\Worker\Simple
 */
class TestWorker extends AbstractAction implements WorkerInterface
{

    public function execute(Workflow $workflow, ActionObject $actionObject)
    {
        sleep(10);
    }

    public function getName()
    {
        return 'Test';
    }
}