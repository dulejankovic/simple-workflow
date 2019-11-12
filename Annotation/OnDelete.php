<?php

namespace Xcentric\SimpleWorkflow\Annotation;

use Doctrine\ORM\Mapping\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Xcentric\SimpleWorkflow\Mapping\Workflow;

/**
 * @Annotation
 * @Target("CLASS")
 */
class OnDelete implements Annotation
{
    /**
     * @var array<Workflow>
     */
    public $workflows = [];
}