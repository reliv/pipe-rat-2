<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindById implements \Reliv\PipeRat2\Repository\Api\FindById
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
     * @param int|string $id
     * @param array      $options
     *
     * @return null|object
     * @throws \Exception
     */
    public function __invoke(
        $id,
        array $options = []
    ) {
        $repository = $this->getEntityRepository->__invoke($options);

        return $repository->find($id);
    }
}
