<?php

namespace Xcentric\SimpleWorkflow\Mapping;


/**
 * Class ActionObject
 * @package Xcentric\SimpleWorkflow\Entity
 */
class ActionObject
{
    /**
     * @var string
     */
    protected $datamodelName;

    protected $entityId;

    protected $additionalData;

    protected $changeSet;

    protected $entity;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return ActionObject
     */
    public function setData($data): ActionObject
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    /**
     * @param mixed $additionalData
     * @return ActionObject
     */
    public function setAdditionalData($additionalData): ActionObject
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChangeSet()
    {
        return $this->changeSet;
    }

    /**
     * @param mixed $changeSet
     * @return ActionObject
     */
    public function setChangeSet($changeSet): ActionObject
    {
        $this->changeSet = $changeSet;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatamodelName(): string
    {
        return (string)$this->datamodelName;
    }

    /**
     * @param string $datamodelName
     * @return ActionObject
     */
    public function setDatamodelName(?string $datamodelName): ActionObject
    {
        $this->datamodelName = (string)$datamodelName;
        return $this;
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param int $entityId
     * @return ActionObject
     */
    public function setEntityId($entityId): ActionObject
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     * @return ActionObject
     */
    public function setEntity($entity): ActionObject
    {
        $this->entity = $entity;
        return $this;
    }
}