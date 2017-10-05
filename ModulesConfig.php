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

        ];

        $configManager = new \Zend\ConfigAggregator\ConfigAggregator(
            $modules
        );

        return $configManager->getMergedConfig();
    }
}
