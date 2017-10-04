<?php

namespace Reliv\PipeRat2\DataHydrator\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydratePropertySetter extends HydrateAbstract implements Hydrate
{
    /**
     * const
     */
    const METHOD_PREFIX = 'set';

    /**
     * @param array        $data
     * @param object|array $dataModel
     * @param array        $options
     *
     * @return mixed
     */
    public function __invoke(array $data, $dataModel, array $options)
    {
        $properties = $this->getPropertyList($options, null);

        // If no properties are set, we get them all if we can
        if (!is_array($properties)) {
            $properties = $this->getPropertyListByMethods($dataModel);
        }

        $this->setProperties($data, $dataModel, $properties);
    }

    /**
     * setProperties
     *
     * @param array $data
     * @param       $dataModel
     * @param array $properties
     *
     * @return void
     */
    protected function setProperties(
        array $data,
        $dataModel,
        array $properties
    ) {
        foreach ($properties as $property => $value) {

            if ($value === false) {
                continue;
            }

            if (is_object($dataModel) && array_key_exists($property, $data)) {
                $this->setDataToObject($property, $data[$property], $dataModel);
            }

            if (is_array($dataModel)) {
                $this->setDataToArray($property, $data[$property], $dataModel);
            }
        }
    }

    /**
     * setDataToArray
     *
     * @param string $property
     * @param mixed $value
     * @param array $dataModel
     *
     * @return void
     */
    protected function setDataToArray($property, $value, array $dataModel)
    {
        if (array_key_exists($property, $dataModel)) {
            $dataModel[$property] = $value;
        }
    }

    /**
     * setDataToObject
     *
     * @param $property
     * @param $value
     * @param $dataModel
     *
     * @return void
     */
    protected function setDataToObject($property, $value, $dataModel)
    {
        $method = self::METHOD_PREFIX . ucfirst($property);

        if (method_exists($dataModel, $method)) {
            $dataModel->$method($value);
        }
    }

    /**
     * getPropertiesByMethods
     *
     * @param \stdClass|array $dataModel
     *
     * @return array
     */
    protected function getPropertyListByMethods($dataModel)
    {
        $properties = [];

        if (!is_object($dataModel)) {
            return $properties;
        }

        $methods = get_class_methods(get_class($dataModel));

        foreach ($methods as $method) {

            $prefixLen = strlen(self::METHOD_PREFIX);
            if (substr($method, 0, $prefixLen) == self::METHOD_PREFIX) {
                $property = lcfirst(substr($method, $prefixLen));
                $properties[$property] = true;
            }
        }

        return $properties;
    }
}
