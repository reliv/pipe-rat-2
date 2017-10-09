<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DeleteById implements \Reliv\PipeRat2\Repository\Api\DeleteById
{
    const OPTION_ENTITY_CLASS_NAME = GetEntityRepository::OPTION_ENTITY_CLASS_NAME;

    /**
     * @var GetEntityRepository
     */
    protected $getEntityRepository;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param GetEntityRepository $getEntityRepository
     * @param EntityManager       $entityManager
     */
    public function __construct(
        GetEntityRepository $getEntityRepository,
        EntityManager $entityManager
    ) {
        $this->getEntityRepository = $getEntityRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int|string $id
     * @param array      $options
     *
     * @return bool
     */
    public function __invoke(
        $id,
        array $options = []
    ): bool {
        $repository = $this->getEntityRepository->__invoke($options);
        $result = $repository->find($id);

        if (empty($result)) {
            return false;
        }

        $this->entityManager->remove($result);
        $this->entityManager->flush($result);

        return true;
    }
}
