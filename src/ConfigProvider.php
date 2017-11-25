<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db;

/**
 * Class ConfigProvider
 * @package MSBios\Db
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'factories' => [
                TablePluginManager::class =>
                    Factory\TablePluginManagerFactory::class
            ],
            'aliases' => [
                'TableManager' => TablePluginManager::class
            ]
        ];
    }
}
