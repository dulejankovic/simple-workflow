<?php

namespace Xcentric\SimpleWorkflow\Mapping;

use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target("ANNOTATION")
 */
class Workflow implements Annotation
{
    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $condition;

    /**
     * @var bool
     */
    public $isAsync = true;
}