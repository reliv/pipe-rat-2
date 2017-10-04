<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Create implements \Reliv\PipeRat2\Repository\Api\Create
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
     * @param mixed $data
     * @param array $options
     *
     * @return mixed $data
     * @throws \Exception
     */
    public function __invoke(
        $data,
        array $options = []
    ) {
        $entityClass = $this->getEntityClass->__invoke($options);

        if (!$data instanceof $entityClass) {
            throw new \Exception("Data must be of type: {$entityClass}");
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush($data);

        return $data;
    }
}
