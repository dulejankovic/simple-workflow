<?php

namespace Xcentric\SimpleWorkflow\Service\Condition;

use Xcentric\SimpleWorkflow\Mapping\ActionObject;

interface ConditionInterface
{
    public function check(ActionObject $actionObject): bool;
}