<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 06/01/15
 * Time: 14:22
 */

namespace Library\Helper;


abstract class QueryParser
{
    /**
     * convert list arguments to array
     * Actually not cover by phpunit test
     * @param $arguments
     * @return array
     */
    static function processListArguments($argument)
    {
        if (strpos($argument, ',')) {
            $lessWhiteSpacedArgument = preg_replace('/\s+/', '', $argument);
            $arguments = explode(',', $lessWhiteSpacedArgument);
            return $arguments;
        }
        return $argument;
    }

    /**
     * Get offset from argument
     * @param $args come in from WebServiceGarbage->getArguments()
     * @return int 0 by default
     */
    static function getOffset($args)
    {
        return isset($args['offset']) ? $args['offset'] : 0;
    }

    /**
     * Get limit from arguments
     * @param $args come in from WebServiceGarbage->getArguments()
     * @return int
     */
    static function getLimit($args)
    {
        return isset($args['limit']) ? $args['limit'] : -1;
    }

    /**
     * get Order from arguments
     * order can have multiple parameters (you must separate them by ','
     * you can specify order type with $args['order_type']
     * By defaut order type is ASC, only ASC and DESC is allowed
     * Filters you specify must be allowed by the web service (please implement static::$filters in your webservice)
     * @param array $args
     * @param array $filters filters allowed for order, the key correspond to name of filter, the value correspond to name of field
     * @return string "" or the order generated
     */
    static function getOrder($args, $filters)
    {
        if (!isset($args['order']))
            return "";

        if (isset($args['order_type']) &&
            (
                strtoupper($args['order_type']) == 'ASC'
                || strtoupper($args['order_type']) == 'DESC'
            )
        )
            $orderType = mysql_escape_string($args['order_type']);
        else
            $orderType = 'ASC';
        $orderBuildSQL = array();
        if (is_string($args['order'])) {
            $args['order'] = array($args['order']);
        }
        $orderArgs = self::replaceFieldName($args['order'], $filters);
        foreach ($orderArgs as $key => $orderParam) {
            if ($key == 0)
                $orderBuildSQL[] = mysql_escape_string($orderParam) .' ' . mysql_escape_string($orderType);
            else
                $orderBuildSQL[] = mysql_escape_string($orderParam);
        }
        $orderSQL = implode(', ', $orderBuildSQL);
        return $orderSQL;
    }

    /**
     * Replace each field in fields identified by the value identified by key in fieldsMappings
     * return only the fields identified and not the field not identified
     * @example: Your $fields is array('customer_id', 'is_parent', 'date_entered');
     * If your $fieldMapping = array('customer_id' => 'customer_id_c',
     * 'is_parent' => 'is_parent_c','date_entered' => 'date_entered','date_modified' => 'date_modified',
     * 'empreinte_prise' => 'empreinte_prise_c','created_from' => 'created_from_c');
     * Your mapping will be array('customer_id_c', 'is_parent_c', 'date_entered'); because theses values was found in
     * fieldsMapping
     * @param $fields = array()
     * @param $fieldsMapping
     * @return array
     */
    static function replaceFieldName($fields=array(), $fieldsMapping)
    {
        $mapping = array();
        foreach ($fields as $field) {
            if(isset($fieldsMapping[$field]))
                $mapping[] = $fieldsMapping[$field];
        }

        return $mapping;
    }

    /**
     * get Where from arguments
     * where is based on allowed argument specified by $filters
     * generate a Where SQL Query part by generating fields present in args with mapping of fields present in $filters
     * generate after with operators the SQL query
     * @see self::getOrder to know how field mapping is done
     * @param $args
     * @return string "" or the where generated
     */
     static function getWhere($args, $filters)
    {
        $where = '';
        $whereOperators = isset($args['where_operator']) ? mysql_escape_string($args['where_operator']) : ' AND';
        $whereSubOperator = isset($args['where_sub_operator']) ? mysql_escape_string($args['where_sub_operator']) : ' OR';
        $whereFilters = array_intersect_key($args, $filters);
        if(count($whereFilters)){
            $whereFiltersCounter = 0;
            foreach ($whereFilters as $whereField => $whereValues) {
                $whereFiltersCounter++;
                if(!is_array($whereValues))
                    $whereValues = array($whereValues);
                foreach ($whereValues as $key => $whereValue) {
                    if($key == 0){
                        $where .= ' ('; //isolate parameters by field
                    }
                    $where .= " {$filters[$whereField]} = '".mysql_escape_string($whereValue)."'";
                    if(count($whereValues) > 1 && ($key+1) != count($whereValues))
                        $where .= $whereSubOperator;
                    if($key+1 == count($whereValues)){
                        $where .= ') '; //isolate parameters by field
                    }
                }
                if(count($whereFilters) > 1  && ($whereFiltersCounter != count($whereFilters)))
                    $where .= $whereOperators;
            }
        }
        return $where;
    }
}