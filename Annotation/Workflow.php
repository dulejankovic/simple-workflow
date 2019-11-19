<?php

namespace Xcentric\SimpleWorkflow\Annotation;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("ANNOTATION")
 * @Attributes(
 *     @Attribute("action", type="string", required=true),
 *     @Attribute("condition", type="string"),
 *     @Attribute("isAsync", type="boolean")
 * )
 */
class Workflow implements Annotation
{
    /**
     * @var string #Class
     */
    public $action;

    /**
     * @var string #Class
     */
    public $condition;

    /**
     * @var bool
     */
    public $isAsync = true;
}