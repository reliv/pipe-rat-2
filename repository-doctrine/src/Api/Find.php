<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Find implements \Reliv\PipeRat2\Repository\Api\Find
{
    const OPTION_ENTITY_CLASS_NAME = GetEntityRepository::OPTION_ENTITY_CLASS_NAME;

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
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     * @param array      $options
     *
     * @return array|null
     */
    public function __invoke(
        array $criteria = [],
        array $orderBy = null,
        $limit = null,
        $offset = null,
        array $options = []
    ) {
        $repository = $this->getEntityRepository->__invoke($options);
        return $repository->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset
        );
    }
}
