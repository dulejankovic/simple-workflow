<?php

namespace Xcentric\SimpleWorkflow\Annotation;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\ORM\Mapping\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 * @Attributes(
 *     @Attribute("workflows", type="array")
 * )
 */
class OnDelete implements Annotation
{
    /**
     * @var array<Xcentric\SimpleWorkflow\Annotation\Workflow>
     */
    public $workflows = [];
}