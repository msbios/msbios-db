<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Db;

use MSBios\Db\Feature\TableProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Class Module
 * @package MSBios\Db
 * @link https://github.com/ZendExperts/ZeDb
 */
class Module extends \MSBios\Module implements
    InitProviderInterface,
    ServiceProviderInterface
{
    /** @const VERSION */
    const VERSION = '1.0.17';

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getDir()
    {
        return __DIR__;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * @inheritdoc
     *
     * @param ModuleManagerInterface $manager
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
     * @inheritdoc
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return (new ConfigProvider)->getDependencyConfig();
    }
}
