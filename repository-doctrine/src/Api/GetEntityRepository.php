<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Reliv\PipeRat2\Repository\Api\GetEntityClass;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetEntityRepository
{
    const OPTION_ENTITY_CLASS_NAME = GetEntityClass::OPTION_ENTITY_CLASS_NAME;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var GetEntityClass
     */
    protected $getEntityClass;

    /**
     * @param EntityManager  $entityManager
     * @param GetEntityClass $getEntityClass
     */
    public function __construct(
        EntityManager $entityManager,
        GetEntityClass $getEntityClass
    ) {
        $this->entityManager = $entityManager;
        $this->getEntityClass = $getEntityClass;
    }

    /**
     * @param array $options
     *
     * @return EntityRepository
     * @throws \Exception
     */
    public function __invoke(
        array $options
    ) {
        $entityClass = $this->getEntityClass->__invoke($options);

        if (empty($entityClass)) {
            throw new \Exception("Repository not found, entity not defined");
        }

        return $this->entityManager->getRepository($entityClass);
    }
}
