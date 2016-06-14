<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 */

namespace Library\Service;
use Library\Exception\BadParametersException;
use Library\Exception\Exception;
use Library\Exception\MissingParametersException;
use Library\WebServiceGarbage;
use Library\Handler\HeaderHandler;
use Library\WebServiceOutput;


/**
 * Class Service
 * Each web service extend this class
 * @package Library\Service
 */
abstract class Service {
    /**
     * All parameters require by the web service is inside
     * @var array requirements
     * $_requirement could be an array;
     * $_requirement could be a string.
     * @see Service::checkType
     * @see Service::checkRequirement
     * @see CreateAccount::_requirements
     *
     */
    protected $_requirements = array();

    /** All data send by the webservicefactory to the service (post|get|put|delete) is inside
     * @var WebServiceGarbage $_garbage
     */
    private $_garbage;

    /**
     * Construct the web service, you must pass all parameters require by the web service here
     * You can pass the debug parameter to have each log request
     * @param WebServiceGarbage $garbage
     * @param bool $debugMode
     */
    function init(WebServiceGarbage $garbage){
        $this->_garbage = $garbage;
        $this->checkAuth();
        $this->beforeExec();
        $this->checkRequirements();
        $this->convertBasicData();
    }

    /**
     * @return WebServiceGarbage
     */
    protected function getGarbage(){
        return $this->_garbage;
    }


    function checkRequirements(){
        //do job
        if(!count($this->_requirements))
            return;
        foreach ($this->_requirements as $field => $requirement) {
            if (is_string($requirement)){
                $this->checkRequirement($field, 'type', $requirement);
                continue;
            }
            if (is_array($requirement))
                foreach ($requirement as $condition => $value)
                    $this->checkRequirement($field, $condition, $value);
        }
    }

    /** Check requirements based on the web service definition ::_parameters
     * @param $field
     * @param $condition
     * @param $value
     *
     * @throws \Library\Exception\BadParametersException
     */
    private function checkRequirement($field, $condition, $value){
        if($condition === 0)  //we supposed first argument is always the type (we can trust in programmer!?)
            $condition = 'type'; //get a good message when throw an exception

        if(!$this->_garbage instanceof WebServiceGarbage){
            HeaderHandler::setStatus(400);
            throw new BadParametersException('this::_garbage', 'WebServiceGarbage', 'valid');
        }
        if(isset($this->_garbage->getData()->$field))
            $data = $this->_garbage->getData()->$field;
        else
            $data = null;

        //It's important that require conditions is outside scope,
        //without process is always on errorrequire first

        if($condition == 'required')
            $result = $this->checkRequired($data, $value);

        elseif($data) //Because process need a not empty data value
            switch($condition){
                case 'type':
                    $result = self::checkType($data, $value);
                break;
                case 'min-length':
                    $result = self::checkLength($data, $value);
                break;
                case 'max-length':
                    $result = self::checkLength($data, 0, $value);
                break;
                case 'choices':
                    $result = self::checkChoice($data, $value, $this);
                    break;
                default:
                    throw new Exception("Conditions asked '$condition' as no rules'");
            }
        else
            $result = true; // This code trigger when you give no-require parameter with rules
        if(!$result){
            HeaderHandler::setStatus(400);
            throw new BadParametersException($field, $condition, $value);
        }

    }

    /**
     * Check that value passed correspond to one of list choices
     * @exemple: list = array('male','female'); Choice must be one of these elements
     * @exemple2: list = 'account_type'; checkChoice call ::getList().
     * Value passed must be one of key return by ::getList()
     * It's mandatory to implement getList in your service in case where $filter is a string
     * ::getList() must return an array
     * @param $data
     * @param string|array $list|$filter Put the name of the list @see getList() or a list
     * @param Service $service the service you want to check in
     * @return bool
     * @throws Exception
     */
    static function checkChoice($data, $filter, $service = null){
        switch(gettype($data)){
            //probably checkchoice parse a collection
            case "array":
                $result = true;
                foreach ($data as $node) {
                    if(!self::checkChoice($node, $filter))
                        $result = false;
                }
                break;
            //probably is on object type we looking a choice
            case "object":
                try{
                    $object = self::transformDataIntoObject($filter, $data);
                    $result = $object instanceof $filter;
                }catch(MissingParametersException $e){
                    $result = false;
                }
                break;
            default:
                if(!is_array($filter)) {
                    if (!$service OR !method_exists($service, 'getList'))
                        throw new Exception("condition 'choices' for prerequiste' must be an array; Or you have to provide the name of the list of choices and implement getList() in your service");
                    $list = $service->getList($filter);
                    $list = is_array($list) ? array_keys($list) : null;
                }else{
                    $list = $filter;
                }
                if (!is_array($list))
                    throw new Exception("filter $filter used from ".__FUNCTION__." should be a list or a name of list");
                $result = in_array($data, $list, true);

        }
        return $result;
    }

    /** If required the field must be set and not empty
     * @param $field field value
     * @param $value bool required or not?
     *
     * @return bool
     */
    static function checkRequired($field, $value){
        //@todo warning, required attribut not working with bool
        if(!$value)
            return true;
        return (isset($field) && !empty($field));
    }

    /** Check if field has the required length
     * @param $field
     * @param int $min
     * @param int $max
     * @return bool
     */
    static function checkLength($field, $min = 0, $max = 65536){
        $length = strlen($field);
        if($length > $max)
            return false;
        if($length < $min)
            return false;
        return true;
    }

    /**
     * check the type of the field between email, bool, boolean, double, float, int, ip, regexp, url, list, array, object
     * @param $field
     * @param $type
     *
     * @return bool
     */
    static function checkType($field, $type){
        switch($type){
            case "string":
                return (is_string($field));
            case 'email':
                return self::checkEmailAddress($field);
            case 'bool':
            case 'boolean':
                //@todo warning, required attribut not working with bool
                return is_bool($field);
            case 'double':
            case 'float':
                return is_float($field);
            case 'int':
                return is_int($field);
            case 'ip':
                return filter_var($field, FILTER_VALIDATE_IP);
            case "letters and space":
                return preg_match("/^[a-zA-Z ]+$/", $field);
            case 'url':
                return filter_var($field, FILTER_VALIDATE_URL);
            case 'collection':
            case 'list':
            case 'array':
                return is_array($field);
            case 'percent':
                return (0 <= $field && $field <= 100 );
            case 'phone':
                return preg_match("/^(\+)?(\d){4,15}$/", $field);
            case 'object';
                return is_object($field); //is not so goodly implemented
            case 'phoneFR':
                return (bool) preg_match('/^0[1-9][0-9]{8}$/', $field);
            case 'uuid':
                return (bool) preg_match("/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i", $field);
            case 'siret_siren':
                return (bool) preg_match("/(^\d{9}$)|(^\d{14}$)/", $field);
            case 'postalcode':
                return (bool) preg_match("/^[A-Z0-9-]{2,10}$/", $field);
            default:
                throw new Exception("Type '$type' is not defined");
        }
    }

    static function checkEmailAddress($email) {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }

    function convertBasicData(){
        if(!count($this->_requirements))
            return;
        foreach ($this->_requirements as $field => $requirement) {
            if(!isset($requirement['choices']) || !isset($this->getGarbage()->getData()->$field))
                continue;
            $type = isset($requirement['type']) ? $requirement['type'] : $requirement[0];
            if($type == 'object' && !empty($this->getGarbage()->getData()->$field)){
                $newNodeData = self::transformDataIntoObject($requirement['choices'], $this->getGarbage()->getData()->$field);
                $this->getGarbage()->getData()->$field = $newNodeData;
            }
            if($type == 'collection' && is_array($this->getGarbage()->getData()->$field)){
                $newCollection = array();
                foreach ($this->getGarbage()->getData()->$field as $dataToTransform) {
                    $newNodeData = self::transformDataIntoObject($requirement['choices'], $dataToTransform);
                    $newCollection[] = $newNodeData;
                }
                $this->getGarbage()->getData()->$field = $newCollection;
            }
        }
    }

    /**
     * Transform data you're passing into an object into another object by ReflectionClass
     * @param $classNameToInstanciate
     * @param \stdClass $data
     * @return object the object you're instanciate
     * @throws MissingParametersException if object in constructor need something mandatory
     */
    protected static function transformDataIntoObject($classNameToInstanciate, $data){
        $reflection = new \ReflectionClass($classNameToInstanciate);
        $reflectionParameters = $reflection->getConstructor()->getParameters();
        $argsForConstructor = array();
        foreach ($reflectionParameters as $reflectionParameter) {
            if (!isset($data->{$reflectionParameter->name}) && !$reflectionParameter->isOptional()){
                throw new MissingParametersException($classNameToInstanciate, $reflectionParameter->getName());
            }
            if(isset($data->{$reflectionParameter->name})){
                $argsForConstructor[] = $data->{$reflectionParameter->name};
            }
        }
        return $reflection->newInstanceArgs((array)$argsForConstructor);
    }

    /**
     * this function is the main job of your webservice
     * you must implement in your webservice this function
     * @return void|\Library\WebServiceOutput
     */
    abstract function exec();

    /** this methode is call by child service to addRequirements
     *
     */
    abstract protected function beforeExec();

    /**
     * It has to provide security autorisation based on the webservice restriction
     * you must implement in your webservice this function
     *
     */
    abstract protected function checkAuth();
}