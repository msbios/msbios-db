<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Db\Profiler;

use Zend\Db\Adapter\Profiler\ProfilerInterface;

/**
 * Class Profiler
 * @package MSBios\Db\Profiler
 */
class Profiler implements ProfilerInterface
{
    /**
     * Logical OR these together to get a proper query type filter
     */
    const CONNECT = 1;
    const QUERY = 2;
    const INSERT = 4;
    const UPDATE = 8;
    const DELETE = 16;
    const SELECT = 32;
    const TRANSACTION = 64;

    /** @var array */
    protected $profiles = [];

    /** @var int */
    protected $filterTypes = 127;

    /**
     * @param null $filterTypes
     * @return $this
     */
    public function setFilterTypes($filterTypes = null)
    {
        $this->filterTypes = $filterTypes;
        return $this;
    }

    /**
     * @return int
     */
    public function getFilterTypes()
    {
        return $this->filterTypes;
    }

    /**
     * @param $sql
     * @param null $parameters
     * @param null $stack
     * @return mixed|null
     */
    public function startQuery($sql, $parameters = null, $stack = null)
    {
        if (is_null($stack)) {

            /** @var array $stack */
            $stack = [];

            if (version_compare('5.3.6', phpversion(), '<=')) {
                $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            }
        }

        // try to detect the query type
        switch (strtolower(substr(ltrim($sql), 0, 6))) {
            case 'select':
                $queryType = static::SELECT;
                break;
            case 'insert':
                $queryType = static::INSERT;
                break;
            case 'update':
                $queryType = static::UPDATE;
                break;
            case 'delete':
                $queryType = static::DELETE;
                break;
            default:
                $queryType = static::QUERY;
                break;
        }

        /** @var Query $profile */
        $profile = new Query($sql, $queryType, $parameters, $stack);

        $this->profiles[] = $profile;
        $profile->start();
        end($this->profiles);

        return key($this->profiles);
    }

    /**
     * @return bool
     */
    public function endQuery()
    {
        end($this->profiles)->end();
        return true;
    }

    /**
     * @param null $queryTypes
     * @return array
     */
    public function getQueryProfiles($queryTypes = null)
    {
        $profiles = [];
        if (count($this->profiles)) {
            foreach ($this->profiles as $id => $profile) {
                if ($queryTypes === null) {
                    $queryTypes = $this->filterTypes;
                }
                if ($profile->getQueryType() & $queryTypes) {
                    $profiles[$id] = $profile;
                }
            }
        }
        return $profiles;
    }

    /**
     * @param string|\Zend\Db\Adapter\StatementContainerInterface $target
     * @return mixed
     */
    public function profilerStart($target)
    {
        $this->startQuery(
            $target->getSql(),
            $target->getParameterContainer()->getNamedArray()
        );
    }

    /**
     *
     */
    public function profilerFinish()
    {
        $this->endQuery();
    }
}
