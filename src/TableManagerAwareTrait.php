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
trait TableManagerAwareTrait
{
    /** @var  TablePluginManager */
    protected $tableManager;

    /**
     * @return TablePluginManager
     */
    public function getTableManager(): TablePluginManager
    {
        return $this->tableManager;
    }

    /**
     * @param TablePluginManager $tableManager
     * @return $this
     */
    public function setTableManager(TablePluginManager $tableManager)
    {
        $this->tableManager = $tableManager;
        return $this;
    }
}
