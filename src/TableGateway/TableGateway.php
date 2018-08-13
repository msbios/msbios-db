<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @access protected
 * @author ZendExperts <team@zendexperts.com>, Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Db\TableGateway;

use MSBios\Db\Exception\Exception;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\RowGateway\RowGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway as DefaultTableGateway;

/**
 * Table Gateway class for requests to the database using simplified functions calls
 *
 * @package MSBios\Db\TableGateway
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 */
class TableGateway extends DefaultTableGateway implements TableGatewayInterface
{
    /**
     * Available function patterns that can be called to execute requests on the database
     * @var array
     */
    protected static $PATTERNS = [
        '/^fetchAll(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/U' => '__fetchAll',
        '/^fetchByColumns(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/U' => '__fetchBy',
        '/^fetchAllByColumns(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/U' => '__fetchAll',
        '/^fetchBy(?P<fields>[A-Z][a-zA-Z0-9]+)(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/U' => '__fetchBy',
        '/^fetchAllBy(?P<fields>[A-Z][a-zA-Z0-9]+)(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/U' => '__fetchAll',
        '/^fetchLike(?P<fields>[A-Z][a-zA-Z0-9]+)(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/U' => '__fetchLike',
        '/^fetchAllLike(?P<fields>[A-Z][a-zA-Z0-9]+)(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/U' => '__fetchAllLike',
        '/^count$/U' => '__count',
        '/^countBy(?P<fields>[A-Z][a-zA-Z0-9]+)?$/U' => '__countBy',
        '/^countDistinctBy(?P<fields>[A-Z][a-zA-Z0-9]+)?$/U' => '__countDistinctBy',
        '/^removeBy(?P<fields>[A-Z][a-zA-Z0-9]+)$/U' => '__removeBy',
    ];

    /**
     * Magic function handler
     *
     * @param $name
     * @param $args
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $args)
    {
        /**
         * Go through all the existing pattenrs
         * @var string $pattern
         * @var string $function
         */
        foreach (static::$PATTERNS as $pattern => $function) {
            $matches = null;
            $found = preg_match($pattern, $name, $matches);
            //if a matched pattern was found, call the associated function with the matches and args and return the result
            if ($found) {

                /** @var array $options */
                $options = [];
                /**
                 * @var int $key
                 * @var  $value
                 */
                foreach ($matches as $key => $value) {
                    if (! is_int($key)) {
                        $options[$key] = $value;
                    }
                }

                return $this->$function($options, $args);
            }
        }

        throw new Exception("Invalid method called: {$name}");
    }

    /**
     * Handler for RemoveBy magic functions
     *
     * @param $matches
     * @param $args
     * @return int
     */
    private function __removeBy($matches, $args)
    {
        //get arguments from the function name
        $where = $this->_parseWhere($matches, $args);
        //execute the delete procedure with the abobe arguments
        $result = $this->delete(function ($select) use ($where) {
            $select->where($where);
        });
        //return the result
        return $result;
    }

    /**
     * Handler for GetBy magic function
     * @param $matches
     * @param $args
     * @return array|\ArrayObject|null|RowGateway
     */
    private function __fetchBy($matches, $args)
    {
        /** @var ResultSetInterface $resultSet select a single row and return it */
        $resultSet = $this->_getResultSet($matches, $args);
        /** @var array|\ArrayObject|null|RowGateway $row */
        $row = $resultSet->current();
        return $row;
    }

    /**
     * Handler for GetAll magic functions
     * @param $matches
     * @param $args
     * @return array
     */
    private function __fetchAll($matches, $args)
    {
        /** @var ResultSetInterface $resultSet Select all the matching results */
        $resultSet = $this->_getResultSet($matches, $args);

        return $resultSet;

        /** @var array $rows Parse the result set and return all the entitites */
        $rows = [];

        /** @var array|\ArrayObject|null|RowGateway $row */
        foreach ($resultSet as $row) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Handler for GetAllLike magic function
     * @param $matches
     * @param $args
     * @return array
     */
    private function __fetchAllLike($matches, $args)
    {
        /** @var ResultSetInterface $resultSet */
        $resultSet = $this->_getLikeResultSet($matches, $args);

        /** @var array $rows */
        $rows = [];

        /** @var array|\ArrayObject|null|RowGateway $row */
        foreach ($resultSet as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Handler for count magic function
     *
     * @return int
     */
    private function __count()
    {
        return $this->_getCount(null, false);
    }

    /**
     * Handler for countBy magic function
     *
     * @param array $matches
     * @return int
     */
    private function __countBy(array $matches)
    {
        return $this->_getCount($matches, false);
    }

    /**
     * Handler for countDistinctBy magic function
     *
     * @param array $matches
     * @return int
     */
    private function __countDistinctBy($matches)
    {
        return $this->_getCount($matches, true);
    }

    /**
     * Count sql rows
     * @param $matches
     * @param $distinct
     * @return int
     */
    private function _getCount($matches = null, $distinct = false)
    {
        //initialize table gateways of not initialized
        if (! $this->isInitialized) {
            $this->initialize();
        }
        //build count expression
        $expression = new Expression();
        $expression->setExpression("COUNT(" . ($distinct ? ' DISTINCT ' : '') . "?)");
        if (empty($matches)) {
            $expression->setParameters('*');
            $expression->setTypes([Expression::TYPE_LITERAL]);
        } else {
            $field = $this->__normalizeKeys($matches['fields']);
            $expression->setParameters($field);
            $expression->setTypes([Expression::TYPE_IDENTIFIER]);
        }
        $select = $this->sql->select();
        //set columns to just the count expression
        $select->columns(['count' => $expression], false);
        $statement = $this->sql->prepareStatementForSqlObject($select);
        //execute the statement and return the result
        $result = $statement->execute()->current();
        return $result['count'];
    }

    /**
     * Get ResultSet object when selecting rows
     * @param $matches
     * @param $args
     * @return \Zend\Db\ResultSet\ResultSet
     */
    private function _getResultSet($matches, $args)
    {
        //parse arguments from the function name
        $where = $this->_parseWhere($matches, $args);
        $order = $this->_parseOrder($matches);
        $limit = $this->_parseLimit($matches);
        $offset = null;
        if (is_array($limit)) {
            list($limit, $offset) = $limit;
        }
        //run the query based on the above arguments and return the result set
        $resultSet = $this->select(function ($select) use ($where, $order, $limit, $offset) {
            $select->where($where);
            if ($order) {
                $select->order($order);
            }
            if ($limit !== null) {
                $select->limit(1 * $limit);
                if ($offset !== null) {
                    $select->offset(1 * $offset);
                }
            }
        });
        return $resultSet;
    }

    /**
     * Get ResultSet object when selecting rows using the GetAllLike magic function
     * @param $matches
     * @param $args
     * @return \Zend\Db\ResultSet\ResultSet
     */
    private function _getLikeResultSet($matches, $args)
    {
        //parse arguments from the function name
        $where = $this->_parseLikeWhere($matches, $args);
        $order = $this->_parseOrder($matches);
        $limit = $this->_parseLimit($matches);
        $offset = null;
        if (is_array($limit)) {
            list($limit, $offset) = $limit;
        }
        //run the query based on the above arguments and return the result set
        $resultSet = $this->select(function ($select) use ($where, $order, $limit, $offset) {
            $select->where($where);
            if ($order) {
                $select->order($order);
            }
            if ($limit !== null) {
                $select->limit(1 * $limit);
                if ($offset !== null) {
                    $select->offset(1 * $offset);
                }
            }
        });
        return $resultSet;
    }

    /**
     * Parse query conditions using LIKE
     * @param $matches
     * @param $args
     * @return array
     */
    private function _parseLikeWhere($matches, $args)
    {
        $where = [];
        if (array_key_exists('fields', $matches) && ! empty($matches['fields'])) {
            $fields = explode('And', $matches['fields']);
            $fields = $this->__normalizeKeys($fields);
            foreach ($fields as $k => $field) {
                $where[$field . " LIKE ?"] = $args[$k];
            }
        }
        return $where;
    }

    /**
     * Parse query conditions
     * @param $matches
     * @param $args
     * @return array
     */
    private function _parseWhere($matches, $args)
    {
        $where = [];
        if (array_key_exists('fields', $matches) && ! empty($matches['fields'])) {
            $k = 0;
            $fields = explode('And', $matches['fields']);
            $fields = $this->__normalizeKeys($fields);
            $where = array_combine($fields, $args);
        } else {
            if (count($args)) {
                //handle by columns
                $where = $args[0];
            } else {
                $where = [];
            }
        }
        return $where;
    }

    /**
     * Parse order by
     * @param $matches
     * @return array
     */
    private function _parseOrder($matches)
    {
        $order = [];
        if (array_key_exists('orderBy', $matches) && ! empty($matches['orderBy'])) {
            $orderBy = $matches['orderBy'];
            $orderBy = explode('And', $orderBy);
            foreach ($orderBy as $value) {
                if (substr($value, -4) == 'Desc') {
                    $order[$this->__normalizeKeys(substr($value, 0, -4))] = 'DESC';
                } else {
                    $order[$this->__normalizeKeys($value)] = 'ASC';
                }
            }
        }
        return $order;
    }

    /**
     * Parse limit and offset
     * @param $matches
     * @return array|null
     */
    private function _parseLimit($matches)
    {
        $limit = (array_key_exists('limit', $matches) ? $matches['limit'] : null);
        $offset = (array_key_exists('offset', $matches) ? $matches['offset'] : null);
        if (! $limit) {
            return null;
        }
        if ($limit && $offset === null) {
            return $limit;
        }
        return [$limit, $offset];
    }

    /**
     * Transform keys from camelCase to underscode
     *
     * @param $keys
     * @return array|string
     */
    private function __normalizeKeys($keys)
    {
        if (! is_array($keys)) {
            return strtolower(preg_replace('/([A-Z]+)/', '_\1', lcfirst($keys)));
        }
        foreach ($keys as $k => $v) {
            $keys[$k] = strtolower(preg_replace('/([A-Z]+)/', '_\1', lcfirst($v)));
        }
        return $keys;
    }
}
