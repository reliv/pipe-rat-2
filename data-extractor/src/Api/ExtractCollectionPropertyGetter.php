<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

use Reliv\PipeRat\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ExtractCollectionPropertyGetter extends ExtractPropertyGetter implements Extract
{
    /**
     * extract and return data if possible
     *
     * @param object|array $collectionDataModel
     * @param array        $options
     *
     * @return array|mixed
     */
    public function __invoke($collectionDataModel, array $options)
    {
        $properties = $this->getPropertyList($options, null);

        // If no properties are set, we get them all if we can
        if (!is_array($properties)) {
            $properties = $this->getPropertyListByCollectionMethods($collectionDataModel);
        }

        $depthLimit = $this->getPropertyDepthLimit($options, 1);

        return $this->getCollectionProperties($collectionDataModel, $properties, 1, $depthLimit);
    }

    /**
     * getPropertyListByCollectionMethods
     *
     * @param \stdClass|array $collectionDataModel
     *
     * @return array
     */
    protected function getPropertyListByCollectionMethods($collectionDataModel)
    {
        $dataModel = null;

        foreach ($collectionDataModel as $ldataModel) {
            $dataModel = $ldataModel;
            break;
        }

        return $this->getPropertyListFromProperties($dataModel);
    }
}
