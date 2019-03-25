<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Db\Initializer;

use Interop\Container\ContainerInterface;
use MSBios\Db\TableManagerAwareInterface;
use MSBios\Db\TablePluginManager;
use Zend\ServiceManager\Initializer\InitializerInterface;

/**
 * Class TableManagerInitializer
 * @package MSBios\Db\Initializer
 */
class TableManagerInitializer implements InitializerInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param object $instance
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if ($instance instanceof TableManagerAwareInterface) {
            $instance->setTableManager(
                $container->get(TablePluginManager::class)
            );
        }
    }
}
