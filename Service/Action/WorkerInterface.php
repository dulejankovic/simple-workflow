<?php

namespace Xcentric\SimpleWorkflow\Service\Action;

use Xcentric\SimpleWorkflow\Mapping\ActionObject;
use Xcentric\SimpleWorkflow\Annotation\Workflow;

interface WorkerInterface
{
    public function execute(Workflow $workflow, ActionObject $actionObject);
    
    public function getName();
}