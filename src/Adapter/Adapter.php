<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db\Adapter;

use Zend\Db\Adapter\Adapter as DefaultAdapter;
use Zend\Db\Adapter\Profiler\ProfilerInterface;
use Zend\Db\ResultSet\ResultSetInterface;

/**
 * Class Adapter
 * @package MSBios\Db\Adapter
 */
class Adapter extends DefaultAdapter
{
    /**
     * @inheritdoc
     *
     * @param string $sql
     * @param array|string|\Zend\Db\Adapter\ParameterContainer $parametersOrQueryMode
     * @param ResultSetInterface|null $resultPrototype
     * @return \Zend\Db\Adapter\Driver\StatementInterface|\Zend\Db\ResultSet\ResultSet
     */
    public function query(
        $sql,
        $parametersOrQueryMode = self::QUERY_MODE_PREPARE,
        ResultSetInterface $resultPrototype = null
    ) {
        /** @var ProfilerInterface $profiler */
        $profiler = $this->getProfiler();
        $profiler->startQuery($sql);
        /** @var \Zend\Db\Adapter\Driver\StatementInterface|\Zend\Db\ResultSet\ResultSet $result */
        $result = parent::query($sql, $parametersOrQueryMode, $resultPrototype);
        $profiler->endQuery($sql);
        return $result;
    }
}
