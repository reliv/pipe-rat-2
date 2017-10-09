<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;
use Reliv\PipeRat2\DataHydrator\Api\GetHydrator;
use Reliv\PipeRat2\DataHydrator\Api\Hydrate;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UpdateProperties implements \Reliv\PipeRat2\Repository\Api\UpdateProperties
{
    const OPTION_ENTITY_CLASS_NAME = GetEntityRepository::OPTION_ENTITY_CLASS_NAME;
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
     * @param GetEntityRepository $getEntityRepository
     * @param EntityManager       $entityManager
     * @param GetHydrator         $getHydrator
     * @param array               $defaultHydratorPropertyList
     * @param int                 $defaultHydratorDepthLimit
     * @param string              $defaultHydratorServiceName
     */
    public function __construct(
        GetEntityRepository $getEntityRepository,
        EntityManager $entityManager,
        GetHydrator $getHydrator,
        array $defaultHydratorPropertyList = self::DEFAULT_PROPERTY_LIST,
        int $defaultHydratorDepthLimit = self::DEFAULT_DEPTH_LIMIT,
        string $defaultHydratorServiceName = self::DEFAULT_DATA_HYDRATE_API
    ) {
        $this->getEntityRepository = $getEntityRepository;
        $this->entityManager = $entityManager;
        $this->getHydrator = $getHydrator;
        $this->defaultHydratorPropertyList = $defaultHydratorPropertyList;
        $this->defaultHydratorDepthLimit = $defaultHydratorDepthLimit;
        $this->defaultHydratorServiceName = $defaultHydratorServiceName;
    }

    /**
     * @param int|string $id
     * @param array      $properties
     * @param array      $options
     *
     * @return null|object
     * @throws \Exception
     */
    public function __invoke(
        $id,
        array $properties,
        array $options = []
    ) {
        $repository = $this->getEntityRepository->__invoke($options);

        $entity = $repository->find($id);

        if (empty($entity)) {
            return null;
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
            $properties,
            $entity,
            $options
        );

        $this->entityManager->flush($entity);

        return $entity;
    }
}
