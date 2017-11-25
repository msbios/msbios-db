<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Db;

/**
 * Trait TableManagerAwareTrait
 * @package MSBios\Db
 */
interface TableManagerAwareInterface
{
    /**
     * @return TablePluginManager
     */
    public function getTableManager(): TablePluginManager;

    /**
     * @param TablePluginManager $tableManager
     * @return mixed
     */
    public function setTableManager(TablePluginManager $tableManager);
}
