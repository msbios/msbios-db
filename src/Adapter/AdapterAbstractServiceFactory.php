<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db\Adapter;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterAbstractServiceFactory as DefaultAdapterAbstractServiceFactory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

/**
 * Class AdapterAbstractServiceFactory
 * @package MSBios\Db\Adapter
 */
class AdapterAbstractServiceFactory extends DefaultAdapterAbstractServiceFactory
{
    /** @var AdapterInterface */
    protected static $adapter;

    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return \Zend\Db\Adapter\Adapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!self::$adapter instanceof AdapterInterface) {
            /** @var AdapterInterface $adapter */
            self::$adapter = parent::__invoke($container, $requestedName, $options);
            GlobalAdapterFeature::setStaticAdapter(self::$adapter);
        }

        return self::$adapter;
    }

}