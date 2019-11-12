<?php

namespace Xcentric\SimpleWorkflow\Service\Action;

use Xcentric\SimpleWorkflow\Mapping\ActionObject;
use Xcentric\SimpleWorkflow\Mapping\Workflow;

/**
 * Class TestWorker
 * @package WorkflowBundle\Worker\Simple
 */
class TestWorker extends AbstractAction implements WorkerInterface
{

    public function execute(Workflow $workflow, ActionObject $actionObject)
    {
        sleep(30);
    }
}