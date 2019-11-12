<?php

namespace Xcentric\SimpleWorkflow\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping\Annotation;
use Xcentric\SimpleWorkflow\Mapping\Workflow;

/**
 * @Annotation
 * @Target("CLASS")
 */
class OnUpdate implements Annotation
{
    /**
     * @var array<Workflow>
     */
    public $workflows = [];
}