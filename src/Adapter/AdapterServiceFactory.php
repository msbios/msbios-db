<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db\Adapter;

use Interop\Container\ContainerInterface;
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
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Adapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config');

        if (array_key_exists($requestedName, $config)) {
            return new Adapter($config[$requestedName]);
        }

        /** @var AdapterInterface $adapter */
        $adapter = parent::__invoke($container, $requestedName, $options);
        GlobalAdapterFeature::setStaticAdapter($adapter);
        return $adapter;
    }
}
