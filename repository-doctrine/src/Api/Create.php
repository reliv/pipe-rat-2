<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;
use Reliv\PipeRat2\DataHydrator\Api\GetHydrator;
use Reliv\PipeRat2\DataHydrator\Api\Hydrate;
use Reliv\PipeRat2\Options\Options;
use Reliv\PipeRat2\Repository\Api\GetEntityClass;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Create implements \Reliv\PipeRat2\Repository\Api\Create
{
    const OPTION_ENTITY_CLASS_NAME = GetEntityClass::OPTION_ENTITY_CLASS_NAME;
    const OPTION_DATA_HYDRATE_API = GetHydrator::OPTION_DATA_HYDRATE_API;
    const OPTION_PROPERTY_LIST = Hydrate::OPTION_PROPERTY_LIST;
    const OPTION_DEPTH_LIMIT = Hydrate::OPTION_DEPTH_LIMIT;

    const DEFAULT_DATA_HYDRATE_API = Hydrate::class;
    const DEFAULT_PROPERTY_LIST = [];
    const DEFAULT_DEPTH_LIMIT = 1;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var GetEntityClass
     */
    protected $getEntityClass;

    /**
     * @var GetHydrator
     */
    protected $getHydrator;

    /**
     * @var array
     */
    protected $defaultHydratorPropertyList = self::DEFAULT_PROPERTY_LIST;

    /**
     * @var int
     */
    protected $defaultHydratorDepthLimit = self::DEFAULT_DEPTH_LIMIT;

    /**
     * @var string
     */
    protected $defaultHydratorServiceName = self::DEFAULT_DATA_HYDRATE_API;

    /**
     * @param EntityManager  $entityManager
     * @param GetEntityClass $getEntityClass
     * @param GetHydrator    $getHydrator
     * @param array          $defaultHydratorPropertyList
     * @param int            $defaultHydratorDepthLimit
     * @param string         $defaultHydratorServiceName
     */
    public function __construct(
        EntityManager $entityManager,
        GetEntityClass $getEntityClass,
        GetHydrator $getHydrator,
        array $defaultHydratorPropertyList = self::DEFAULT_PROPERTY_LIST,
        int $defaultHydratorDepthLimit = self::DEFAULT_DEPTH_LIMIT,
        string $defaultHydratorServiceName = self::DEFAULT_DATA_HYDRATE_API
    ) {
        $this->entityManager = $entityManager;
        $this->getEntityClass = $getEntityClass;
        $this->getHydrator = $getHydrator;
        $this->defaultHydratorPropertyList = $defaultHydratorPropertyList;
        $this->defaultHydratorDepthLimit = $defaultHydratorDepthLimit;
        $this->defaultHydratorServiceName = $defaultHydratorServiceName;
    }

    /**
     * @param object|array $data
     * @param array        $options
     *
     * @return mixed $data
     * @throws \Exception
     */
    public function __invoke(
        $data,
        array $options = []
    ) {
        $entityClass = $this->getEntityClass->__invoke($options);

        $entity = null;

        if ($data instanceof $entityClass) {
            $entity = $data;
        }

        if (!$entity instanceof $entityClass) {
            $entity = new $entityClass();

            $hydratorServiceName = Options::get(
                $options,
                self::OPTION_DATA_HYDRATE_API,
                $this->defaultHydratorServiceName
            );

            $hydrator = $this->getHydrator->__invoke(
                [GetHydrator::OPTION_DATA_HYDRATE_API => $hydratorServiceName]
            );

            $entity = $hydrator->__invoke(
                $data,
                $entity,
                $options
            );
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);

        return $entity;
    }
}
