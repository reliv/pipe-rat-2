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
class Upsert implements \Reliv\PipeRat2\Repository\Api\Upsert
{
    const OPTION_ENTITY_CLASS_NAME = GetEntityClass::OPTION_ENTITY_CLASS_NAME;
    const OPTION_ENTITY_ID_FIELD_NAME = GetEntityIdFieldName::OPTION_ENTITY_ID_FIELD_NAME;
    const OPTION_DATA_HYDRATE_API = GetHydrator::OPTION_DATA_HYDRATE_API;
    const OPTION_PROPERTY_LIST = Hydrate::OPTION_PROPERTY_LIST;
    const OPTION_DEPTH_LIMIT = Hydrate::OPTION_DEPTH_LIMIT;

    const DEFAULT_DATA_HYDRATE_API = Hydrate::class;
    const DEFAULT_PROPERTY_LIST = [];
    const DEFAULT_DEPTH_LIMIT = 1;

    /**
     * @var GetEntityRepository
     */
    protected $getEntityRepository;

    /**
     * @var GetEntityClass
     */
    protected $getEntityClass;

    /**
     * @var GetEntityIdFieldName
     */
    protected $getEntityIdFieldName;

    /**
     * @var EntityManager
     */
    protected $entityManager;

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
     * @param GetEntityRepository  $getEntityRepository
     * @param GetEntityClass       $getEntityClass
     * @param GetEntityIdFieldName $getEntityIdFieldName
     * @param EntityManager        $entityManager
     * @param GetHydrator          $getHydrator
     * @param array                $defaultHydratorPropertyList
     * @param int                  $defaultHydratorDepthLimit
     * @param string               $defaultHydratorServiceName
     */
    public function __construct(
        GetEntityRepository $getEntityRepository,
        GetEntityClass $getEntityClass,
        GetEntityIdFieldName $getEntityIdFieldName,
        EntityManager $entityManager,
        GetHydrator $getHydrator,
        array $defaultHydratorPropertyList = self::DEFAULT_PROPERTY_LIST,
        int $defaultHydratorDepthLimit = self::DEFAULT_DEPTH_LIMIT,
        string $defaultHydratorServiceName = self::DEFAULT_DATA_HYDRATE_API
    ) {
        $this->getEntityRepository = $getEntityRepository;
        $this->getEntityClass = $getEntityClass;
        $this->getEntityIdFieldName = $getEntityIdFieldName;
        $this->entityManager = $entityManager;
        $this->getHydrator = $getHydrator;
        $this->defaultHydratorPropertyList = $defaultHydratorPropertyList;
        $this->defaultHydratorDepthLimit = $defaultHydratorDepthLimit;
        $this->defaultHydratorServiceName = $defaultHydratorServiceName;
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return object
     * @throws \Exception
     */
    public function __invoke(
        array $data,
        array $options = []
    ) {
        if (!is_array($data)) {
            throw new \Exception('Can only handle data as array, got: ' . var_export($data, true));
        }
        $repository = $this->getEntityRepository->__invoke(
            $options
        );

        $entityClass = $this->getEntityClass->__invoke($options);

        $idFieldName = $this->getEntityIdFieldName->__invoke($options);

        $entity = null;

        if (array_key_exists($idFieldName, $data)) {
            $entity = $repository->findOneBy([$idFieldName => $data[$idFieldName]]);
        }

        if (!is_object($entity)) {
            $entity = new $entityClass();
            $this->entityManager->persist($entity);
        }

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

        $this->entityManager->flush($entity);

        return $entity;
    }
}
