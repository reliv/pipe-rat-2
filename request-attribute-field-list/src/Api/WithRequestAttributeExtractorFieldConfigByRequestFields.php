<?php

namespace Reliv\PipeRat2\RequestAttributeFieldList\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\FieldNotAllowed;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\InvalidFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Exception\UnknownFieldType;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithRequestAttributeExtractorFieldConfigByRequestFields implements WithRequestAttributeExtractorFieldConfig
{
    const OPTION_FIELD_LIST = 'field-list';

    const DEFAULT_FIELD_LIST = [];

    protected $filterAllowedFieldListByRequestFieldList;
    protected $filterAllowedFieldListByIncludeKey;

    /**
     * @param FilterAllowedFieldListByRequestFieldList $filterAllowedFieldListByRequestFieldList
     * @param FilterAllowedFieldListByIncludeKey       $filterAllowedFieldListByIncludeKey
     */
    public function __construct(
        FilterAllowedFieldListByRequestFieldList $filterAllowedFieldListByRequestFieldList,
        FilterAllowedFieldListByIncludeKey $filterAllowedFieldListByIncludeKey
    ) {
        $this->filterAllowedFieldListByRequestFieldList = $filterAllowedFieldListByRequestFieldList;
        $this->filterAllowedFieldListByIncludeKey = $filterAllowedFieldListByIncludeKey;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $options
     *
     * @return ServerRequestInterface
     * @throws FieldNotAllowed
     * @throws InvalidFieldConfig
     * @throws UnknownFieldType
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ): ServerRequestInterface {
        $allowedFieldConfig = $request->getAttribute(
            WithRequestAttributeAllowedFieldConfig::ATTRIBUTE
        );

        if (empty($allowedFieldConfig)) {
            throw new InvalidFieldConfig(
                'No allowed fields found to build list'
            );
        }

        $requestFieldList = (array)$request->getAttribute(
            WithRequestAttributeFields::ATTRIBUTE,
            []
        );

        $requestFieldConfig = $this->filterAllowedFieldListByRequestFieldList->__invoke(
            $allowedFieldConfig,
            $requestFieldList
        );

        $extractorFieldConfig = $this->filterAllowedFieldListByIncludeKey->__invoke(
            $requestFieldConfig
        );

        return $request->withAttribute(self::ATTRIBUTE, $extractorFieldConfig);
    }
}
