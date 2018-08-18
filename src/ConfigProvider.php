<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db;

use MSBios\Db\Adapter\AdapterServiceFactory;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;

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

                AdapterInterface::class =>
                    AdapterServiceFactory::class,

                TablePluginManager::class =>
                    Factory\TablePluginManagerFactory::class
            ],
            'aliases' => [

                Adapter::class =>
                    AdapterInterface::class,

                'TableManager' =>
                    TablePluginManager::class
            ]
        ];
    }
}
