<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db\Adapter;

use Interop\Container\ContainerInterface;
use MSBios\Db\Exception\RuntimeException;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\AdapterServiceFactory as DefaultAdapterServiceFactory;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

/**
 * Class AdapterServiceFactory
 * @package MSBios\Db\Adapter
 */
class AdapterServiceFactory extends DefaultAdapterServiceFactory
{
    /** @var AdapterInterface */
    protected static $adapter;

    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Adapter|AdapterInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!self::$adapter instanceof AdapterInterface) {
            /** @var AdapterInterface $adapter */
            $adapter = parent::__invoke($container, $requestedName, $options);
            GlobalAdapterFeature::setStaticAdapter($adapter);
            self::$adapter = $adapter;
        }

        return self::$adapter;
    }
}
