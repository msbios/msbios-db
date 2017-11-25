<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db;

use MSBios\Db\Exception\TableServiceNotFoundException;
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
     */
    public function find($name, $id)
    {
        if (! $this->has($name)) {
            throw new TableServiceNotFoundException(
                'Table Service "' . $name . '" Not Found'
            );
        }

        /** @var  $tableGateway */
        $tableGateway = $this->get($name);
        // TODO Add functionality
    }
}
