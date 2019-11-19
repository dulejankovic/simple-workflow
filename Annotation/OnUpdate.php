<?php

namespace Xcentric\SimpleWorkflow\Annotation;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 * @Attributes(
 *     @Attribute("workflows", type="array")
 * )
 */
class OnUpdate implements Annotation
{
    /**
     * @var array<Xcentric\SimpleWorkflow\Annotation\Workflow>
     */
    public $workflows = [];
}