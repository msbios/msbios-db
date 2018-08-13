<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Db;

use MSBios\Db\Exception\TableServiceNotFoundException;
use MSBios\Db\TableGateway\TableGatewayInterface;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class TablePluginManager
 * @package MSBios\Db
 */
class TablePluginManager extends AbstractPluginManager
{
    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function find($name, $id)
    {
        if (! $this->has($name)) {
            throw new TableServiceNotFoundException(
                "Table Service {$name} Not Found"
            );
        }

        /** @var TableGatewayInterface $tableGatewayService */
        $tableGatewayService = $this->get($name);

        return $tableGatewayService->fetchById($id);
    }
}
