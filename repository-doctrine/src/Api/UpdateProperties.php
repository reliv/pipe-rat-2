<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UpdateProperties implements \Reliv\PipeRat2\Repository\Api\UpdateProperties
{
    /**
     * @var GetEntityRepository
     */
    protected $getEntityRepository;

    /**
     * @var PopulateEntity
     */
    protected $populateEntity;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param GetEntityRepository $getEntityRepository
     * @param PopulateEntity      $populateEntity
     * @param EntityManager       $entityManager
     */
    public function __construct(
        GetEntityRepository $getEntityRepository,
        PopulateEntity $populateEntity,
        EntityManager $entityManager
    ) {
        $this->getEntityRepository = $getEntityRepository;
        $this->populateEntity = $populateEntity;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int|string $id
     * @param array      $properties
     * @param array      $options
     *
     * @return mixed $data
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

        $entity = $this->populateEntity->__invoke(
            $options,
            $properties,
            $entity
        );

        $this->entityManager->flush($entity);

        return $entity;
    }
}
