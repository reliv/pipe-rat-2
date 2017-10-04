<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Doctrine\ORM\EntityManager;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Count implements \Reliv\PipeRat2\Repository\Api\Count
{
    /**
     * @var GetEntityRepository
     */
    protected $getEntityRepository;

    /**
     * @param GetEntityRepository $getEntityRepository
     */
    public function __construct(
        GetEntityRepository $getEntityRepository
    ) {
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
    ):int {
        $repository = $this->getEntityRepository->__invoke($options);
        $results = $repository->findBy($criteria);

        return count($results);
    }
}
