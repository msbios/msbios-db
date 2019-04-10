<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db\Adapter;

use Interop\Container\ContainerInterface;
use MSBios\Db\Profiler\Logging as LoggingProfiler;
use MSBios\Db\Profiler\Profiler;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Profiler\ProfilerInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * Class AdapterServiceFactory
 * @package MSBios\Db\Adapter
 */
class ProfileServiceFactory extends AdapterServiceFactory
{
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
        /** @var AdapterInterface $adapter */
        $adapter = parent::__invoke($container, $requestedName, $options);

        /** @var ProfilerInterface $profiler */
        $profiler = new Profiler;

        if ('cli' == php_sapi_name()) {
            $logger = (new Logger)
                ->addWriter(new Stream('php://output'), Logger::DEBUG);

            $profiler = new LoggingProfiler($logger);
        }

        $adapter->setProfiler($profiler);

        return $adapter;
    }
}
