<?php

namespace Reliv\PipeRat2\Repository\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PopulateEntityBasic implements PopulateEntity
{
    /**
     * @var GetEntityClass
     */
    protected $getEntityClass;

    /**
     * @var GetHydrator
     */
    protected $getHydrator;

    /**
     * @param GetEntityClass $getEntityClass
     * @param GetHydrator    $getHydrator
     */
    public function __construct(
        GetEntityClass $getEntityClass,
        GetHydrator $getHydrator
    ) {
        $this->getEntityClass = $getEntityClass;
        $this->getHydrator = $getHydrator;
    }

    /**
     * @param array  $options
     * @param array  $properties
     * @param object $entity
     *
     * @return object
     * @throws \Exception
     */
    public function __invoke(
        array $options,
        array $properties,
        $entity
    ) {
        $entityClass = $this->getEntityClass->__invoke(
            $options
        );

        if (!$entity instanceof $entityClass) {
            throw new \Exception("Entity must be of type: {$entityClass}");
        }

        $hydrator = $this->getHydrator->__invoke(
            $options
        );

        return $hydrator->__invoke(
            $properties,
            $entity,
            $options
        );
    }
}
