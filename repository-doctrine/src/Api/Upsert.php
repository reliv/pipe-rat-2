<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Upsert implements \Reliv\PipeRat2\Repository\Api\Upsert
{
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
     * @var PopulateEntity
     */
    protected $populateEntity;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param GetEntityRepository  $getEntityRepository
     * @param GetEntityClass       $getEntityClass
     * @param GetEntityIdFieldName $getEntityIdFieldName
     * @param PopulateEntity       $populateEntity
     * @param EntityManager        $entityManager
     */
    public function __construct(
        GetEntityRepository $getEntityRepository,
        GetEntityClass $getEntityClass,
        GetEntityIdFieldName $getEntityIdFieldName,
        PopulateEntity $populateEntity,
        EntityManager $entityManager
    ) {
        $this->getEntityRepository = $getEntityRepository;
        $this->getEntityClass = $getEntityClass;
        $this->getEntityIdFieldName = $getEntityIdFieldName;
        $this->populateEntity = $populateEntity;
        $this->entityManager = $entityManager;
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

        $entity = $repository->findOneBy([$idFieldName => $data[$idFieldName]]);

        if (!is_object($entity)) {
            $entity = new $entityClass();
            $this->entityManager->persist($entity);
        }

        $this->populateEntity->__invoke($options, $data, $entity);

        $this->entityManager->flush($entity);

        return $entity;
    }
}
