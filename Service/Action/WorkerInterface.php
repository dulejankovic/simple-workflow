<?php

namespace Xcentric\SimpleWorkflow\Service\Action;

use Xcentric\SimpleWorkflow\Mapping\ActionObject;
use Xcentric\SimpleWorkflow\Mapping\Workflow;

interface WorkerInterface
{
    public function execute(Workflow $workflow, ActionObject $actionObject);
}