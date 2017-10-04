<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;
use Reliv\PipeRat\Exception\DoctrineEntityException;
use Reliv\PipeRat2\Repository\Api\GetEntityClass;
use Reliv\PipeRat2\Repository\Api\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetEntityIdFieldName implements \Reliv\PipeRat2\Repository\Api\GetEntityIdFieldName
{
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
     * @return string
     * @throws DoctrineEntityException
     */
    public function __invoke(
        array $options
    ):string {
        if (array_key_exists(Options::ENTITY_ID_FIELD_NAME, $options)) {
            return $options[Options::ENTITY_ID_FIELD_NAME];
        }

        $entityClass = $this->getEntityClass->__invoke($options);
        $meta = $this->entityManager->getClassMetadata($entityClass);

        $idFieldName = $meta->getSingleIdentifierFieldName();

        if (empty($idFieldName)) {
            throw new DoctrineEntityException(
                'Could not get SingleIdentifierFieldName for entity ' . $entityClass
            );
        }

        return $idFieldName;
    }
}
