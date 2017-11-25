<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Db\Feature;

/**
 * Interface TableProviderInterface
 * @package MSBios\Db\Feature
 */
interface TableProviderInterface
{
    /**
     * @return mixed
     */
    public function getTableManagerConfig();
}
