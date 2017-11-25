<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Db\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Db\TablePluginManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class TablePluginManagerFactory
 * @package MSBios\Db\Factory
 */
class TablePluginManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return TablePluginManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var TablePluginManager $tableManager */
        $tableManager = new TablePluginManager($container, $options ?: []);

        // If this is in a zend-mvc application, the ServiceListener will inject
        // merged configuration during bootstrap.
        if ($container->has('ServiceListener')) {
            return $tableManager;
        }

        // If we do not have a config service, nothing more to do
        if (! $container->has('config')) {
            return $tableManager;
        }

        $config = $container->get('config');

        // If we do not have filters configuration, nothing more to do
        if (! isset($config['table_manager']) || ! is_array($config['table_manager'])) {
            return $tableManager;
        }

        // Wire service configuration for validators
        (new Config($config['table_manager']))->configureServiceManager($tableManager);

        return $tableManager;
    }
}
