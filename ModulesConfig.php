<?php

namespace Reliv\PipeRat2;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModulesConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        $modules = [
            new \Reliv\PipeRat2\Core\ModuleConfig(),
            new \Reliv\PipeRat2\Options\ModuleConfig(),
            new \Reliv\PipeRat2\DataError\ModuleConfig(),
            new \Reliv\PipeRat2\Acl\ModuleConfig(),
            new \Reliv\PipeRat2\DataExtractor\ModuleConfig(),
            new \Reliv\PipeRat2\DataHydrator\ModuleConfig(),
            new \Reliv\PipeRat2\DataValidate\ModuleConfig(),
            new \Reliv\PipeRat2\DataValueTypes\ModuleConfig(),
            new \Reliv\PipeRat2\Repository\ModuleConfig(),
            new \Reliv\PipeRat2\RequestAttribute\ModuleConfig(),
            // Overrides RequestAttribute
            new \Reliv\PipeRat2\RequestAttributeFieldList\ModuleConfig(),

            new \Reliv\PipeRat2\RequestFormat\ModuleConfig(),
            new \Reliv\PipeRat2\ResponseFormat\ModuleConfig(),
            new \Reliv\PipeRat2\ResponseHeaders\ModuleConfig(),
            new \Reliv\PipeRat2\RepositoryDoctrine\ModuleConfig(),
            /** EXAMPLE ONLY *
            new \Reliv\PipeRat2\XampleRepositoryDoctrine\ModuleConfig(),
            /* */
        ];

        $configManager = new \Zend\ConfigAggregator\ConfigAggregator(
            $modules
        );

        return $configManager->getMergedConfig();
    }
}
