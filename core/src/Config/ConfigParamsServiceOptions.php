<?php

namespace Reliv\PipeRat2\Core\Config;

/**
 * @todo Finish me
 * @author James Jervis - https://github.com/jerv13
 */
class ConfigParamsServiceOptions
{
    /**
     * @param array $config
     * @param array $params
     *
     * @return array
     */
    public static function parse(
        array $config,
        array $params
    ) {
        $config = ConfigParams::parse(
            $config,
            $params
        );

        /* @todo Support config overrides from params like:
         * 'request-acl' => [
         * RequestAcl::OPTION_SERVICE_NAME => ['xxx']
         *     IsAllowedRcmUser::OPTION_RESOURCE_ID => 'sites',
         * IsAllowedRcmUser::OPTION_RESOURCE_ID => 'admin',
         * Attr::OPTION_RESOURCE_ID => 'admin',
         * ],
         * 'request-attr' => [
         * RequestAcl::OPTION_SERVICE_NAME => 'xxx'
         *     Attr::OPTION_RESOURCE_ID => 'sites',
         * IsAllowedRcmUser::OPTION_RESOURCE_ID => 'admin',
         * ],
         */

        return $config;
    }

    /**
     * @param array $configOptions
     * @param array $params
     *
     * @return array
     */
    public static function parseServiceOptions(
        array $configOptions,
        array $params
    ) {
        foreach ($configOptions as $key => $value) {
            if (array_key_exists($key, $params) && is_array($params[$key])) {
                // @todo Support config overrides from params
            }
        }

        return $configOptions;
    }

    /**
     * @param array $configValue
     * @param array $paramValue
     *
     * @return array
     */
    public static function parseServiceOption(
        array $configValue,
        array $paramValue
    ) {
        // @todo Support config overrides from params
        return $configValue;
    }
}
