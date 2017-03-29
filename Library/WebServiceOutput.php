<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 */
namespace Library;


use Library\Exception\OutputMissingPropertiesException;
use Library\Handler\HeaderHandler;

/**
 * Class WebServiceOutput
 * @package Library
 */
class WebServiceOutput {

    const okState = 'OK';
    const errorState = 'ERRORS';
    const obsoleteState = 'NOMORESUPPORTED';
    const maintenanceState = 'MAINTENANCE';

    /**
     * @var WebServiceOutput
     * @access private
     * @static
     */
    private static $_instance = null;

    /** get the type of output, ex: json|xml
     * @var string
     */
    private $type;

    /** get the state of the output, ex: OK|ERRORS|DEPRECATED
     * @var string
     */
    private $state;

    /**
     * @var json string
     */
    private $message;
    /** errors is inside
     * @var array
     */
    private $errors = array();

    /**
     * @throws OutputMissingPropertiesException
     * return WebServiceOutput
     */
    function __construct(){
        $this->type = __OUTPUT_TYPE__;
        if (!defined('__WSVERSION__')){
            HeaderHandler::setStatus(500);
            throw new OutputMissingPropertiesException();
        }

        return $this;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Write the output object to the standard output
     * @param bool $return
     * @return mixed
     */
    public function write($return = false){
        global $auth;
        $id = (is_subclass_of($auth, 'Library\Security\Security'))? $auth->getId():null;
        //@todo: comment controler que tout champs requis sont affichés?
        //@todo: eviter de créer un stdClass mais plutot un ObjectValue adapté

        $output= new \stdClass();
        $output->version = __WSVERSION__;
        $output->state = $this->state;
        $output->errors = $this->errors;
        $output->client = array('ext_session_id' => $id);
        $output->time = microtime(true) - __START_EXECUTION_TIME__ . " sec";
        $wsOutput = (object) array_merge((array) $output, (array) $this->message);
        $result = call_user_func_array('Library\Handler\\'.ucfirst(__OUTPUT_TYPE__).'Handler::encode', array($wsOutput));
        if($return)
            return $result;
        echo $result;
    }

    /**
     * Create unique class instance
     * @param void
     * @return WebServiceOutput
     */
    public static function getInstance() {

        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Return all errors
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }

    /**
     * Add error to output
     * @param $code
     * @param $message
     * @return $this
     */
    public function addError($code, $message){
        $error = new \stdClass();
        $error->code = $code;
        $error->message = $message;
        $this->errors[] = $error;
        return $this;
    }
}