<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29/08/14
 * Time: 18:30
 */

namespace Library\Exception;

use Library\Handler\TranslateHandler;

/**
 * Class NotYetImplementedException
 * @package Library\Exception
 */
class JsonException extends Exception{
    protected static $_messages = array(
        0 => 'JSON_ERROR_NONE',
        1 => 'JSON_ERROR_DEPTH',
        2 => 'JSON_ERROR_STATE_MISMATCH',
        3 => 'JSON_ERROR_CTRL_CHAR',
        4 => 'JSON_ERROR_SYNTAX',
        5 => 'JSON_ERROR_UTF8',
        6 => 'JSON_ERROR_RECURSION', //not supported
        7 => 'JSON_ERROR_INF_OR_NAN', //not supported
        8 => 'JSON_ERROR_UNSUPPORTED_TYPE' //not supported
    );
    private $_code = 4520;

    /**
     * Message is the message type coming from JsonHandler
     * @see Library/Handler/JsonHandler
     */
    public function __construct(){
        $error = json_last_error();
        parent::__construct(TranslateHandler::get('errors', static::$_messages[$error]), $this->_code);
    }
} 