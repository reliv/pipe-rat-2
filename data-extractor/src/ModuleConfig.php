<?php

namespace Reliv\PipeRat2\DataExtractor;

use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\Core\Api\GetServiceFromConfigOptions;
use Reliv\PipeRat2\Core\Api\GetServiceOptionsFromConfigOptions;
use Reliv\PipeRat2\Core\Api\ResponseWithDataBody;
use Reliv\PipeRat2\DataExtractor\Api\Extract;
use Reliv\PipeRat2\DataExtractor\Api\ExtractCollectionPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Api\ExtractPropertyGetter;
use Reliv\PipeRat2\DataExtractor\Http\ResponseDataExtractor;

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

                    ResponseDataExtractor::class => [
                        'arguments' => [
                            GetOptions::class,
                            GetServiceFromConfigOptions::class,
                            GetServiceOptionsFromConfigOptions::class,
                            GetDataModel::class,
                            ResponseWithDataBody::class
                        ]
                    ]
                ],
            ],
        ];
    }
}
