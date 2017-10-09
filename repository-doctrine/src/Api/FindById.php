<?php

namespace Reliv\PipeRat2\RepositoryDoctrine\Api;

use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindById implements \Reliv\PipeRat2\Repository\Api\FindById
{
    const OPTION_ENTITY_CLASS = 'entity-class';

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
        $entityClass = Options::get(
            $options,
            self::OPTION_ENTITY_CLASS
        );

        if(empty($entityClass)) {
            // @todo MAYBE??????
        }

        $repository = $this->getEntityRepository->__invoke($options);


        return $repository->find($id);
    }
}
