<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;
use Reliv\PipeRat2\Repository\Api\GetEntityClass;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Count implements \Reliv\PipeRat2\Repository\Api\Count
{
    const OPTION_ENTITY_CLASS_NAME = GetEntityClass::OPTION_ENTITY_CLASS_NAME;

    /**
     * @var GetEntityClass
     */
    protected $getEntityClass;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var GetEntityRepository
     */
    protected $getEntityRepository;

    /**
     * @param GetEntityClass      $getEntityClass
     * @param EntityManager       $entityManager
     * @param GetEntityRepository $getEntityRepository
     */
    public function __construct(
        GetEntityClass $getEntityClass,
        EntityManager $entityManager,
        GetEntityRepository $getEntityRepository
    ) {
        $this->getEntityClass = $getEntityClass;
        $this->entityManager = $entityManager;
        $this->getEntityRepository = $getEntityRepository;
    }

    /**
     * @param array $criteria
     * @param array $options
     *
     * @return int
     */
    public function __invoke(
        array $criteria = [],
        array $options = []
    ):int
    {
        if (empty($criteria)) {
            //When there is no $criteria, running a query is likely faster than findBy.
            $entityName = $this->getEntityClass->__invoke($options);
            $count = $this->entityManager
                ->createQuery('SELECT COUNT(entity) FROM ' . $entityName . ' entity')
                ->getSingleScalarResult();

            return $count;
        }

        $repository = $this->getEntityRepository->__invoke($options);
        $results = $repository->findBy($criteria);

        return count($results);
    }
}
