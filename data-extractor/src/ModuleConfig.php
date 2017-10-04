<?php

namespace Reliv\PipeRat2\DataExtractor;

use Reliv\PipeRat2\DataExtractor\Api\Extract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractCollectionPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    Extract::class => [
                        'class' => ExtractPropertyGetter::class,
                    ],
                    ExtractCollectionPropertyGetter::class => [],
                    ExtractPropertyGetter::class => [],
                ],
            ],
        ];
    }
}
