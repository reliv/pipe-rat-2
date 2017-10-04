<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindById implements \Reliv\PipeRat2\Repository\Api\FindById
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
     * @param int|string $id
     * @param array      $options
     *
     * @return mixed $data
     */
    public function __invoke(
        $id,
        array $options = []
    ) {
        $repository = $this->getEntityRepository->__invoke($options);
        return $repository->find($id);
    }
}
