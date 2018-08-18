<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\DB\Profiler;

/**
 * Class Query
 * @package MSBios\DB\Profiler
 */
class Query
{
    /** @var string */
    protected $sql = '';

    /** @var int */
    protected $queryType = 0;

    /** @var null */
    protected $startTime = null;

    /** @var null */
    protected $endTime = null;

    /** @var null */
    protected $parameters = null;

    /** @var array */
    protected $callStack = [];

    /**
     * Query constructor.
     * @param $sql
     * @param $queryType
     * @param null $parameters
     * @param array $stack
     */
    public function __construct($sql, $queryType, $parameters = null, $stack = array())
    {
        $this->sql = $sql;
        $this->queryType = $queryType;
        $this->parameters = $parameters;
        $this->callStack = $stack;
    }

    /**
     * @return $this
     */
    public function start()
    {
        $this->startTime = microtime(true);
        return $this;
    }

    /**
     * @return $this
     */
    public function end()
    {
        $this->endTime = microtime(true);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasEnded()
    {
        return ($this->endTime !== null);
    }

    /**
     * @return bool|null
     */
    public function getElapsedTime()
    {
        if (!$this->hasEnded()) {
            return false;
        }
        return $this->endTime - $this->startTime;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @return null
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return null
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @return int
     */
    public function getQueryType()
    {
        return $this->queryType;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        switch ($this->queryType) {
            case Profiler::SELECT:
                $type = 'SELECT';
                break;
            case Profiler::INSERT:
                $type = 'INSERT';
                break;
            case Profiler::UPDATE:
                $type = 'UPDATE';
                break;
            case Profiler::DELETE:
                $type = 'DELETE';
                break;
            case Profiler::QUERY:
                $type = 'OTHER';
                break;
            case Profiler::CONNECT:
                $type = 'CONNECT';
                break;
        }

        return [
            'type' => $type,
            'sql' => $this->sql,
            'start' => $this->startTime,
            'end' => $this->endTime,
            'elapsed' => $this->getElapsedTime(),
            'parameters' => $this->parameters,
            'stack' => $this->callStack
        ];
    }
}