<?php
/**
 * @access protected
 */

namespace MSBios\Db;

use MSBios\Db\Feature\TableProviderInterface;
use MSBios\Db\Initializer\TableManagerInitializer;
use MSBios\ModuleInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class Module
 * @package MSBios\Db
 * @link https://github.com/ZendExperts/ZeDb
 */
class Module implements
    ModuleInterface,
    AutoloaderProviderInterface,
    InitProviderInterface,
    ServiceProviderInterface
{
    /** @const VERSION */
    const VERSION = '1.0.6';

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return ArrayUtils::merge(
            include __DIR__ . '/../config/module.config.php',
            [
                'service_manager' => (new ConfigProvider)->getDependencyConfig()
            ]
        );
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            AutoloaderFactory::STANDARD_AUTOLOADER => [
                StandardAutoloader::LOAD_NS => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }

    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     * @return void
     */
    public function init(ModuleManagerInterface $manager)
    {
        $event = $manager->getEvent();
        $container = $event->getParam('ServiceManager');
        $serviceListener = $container->get('ServiceListener');

        $serviceListener->addServiceManager(
            'TableManager',
            'table_manager',
            TableProviderInterface::class,
            'getTableManagerConfig'
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {

        return [
            // 'initializers' => [
            //     new TableManagerInitializer
            // ],
        ];
    }
}
