<?php
/**
 * Created by PhpStorm.
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 29/08/14
 * Time: 18:30
 */


namespace Library\Exception;
use Library\Handler\TranslateHandler;

/**
 * Class BadParametersException
 * @package Library\Exception
 */
class BadParametersException extends Exception{
    private $_message = "BadParameterException";
    private $_code = 4420;

    public function __construct($field, $condition, $value){
        parent::__construct(TranslateHandler::get('errors', $this->_message, $field, $condition, $value), $this->_code);
    }
} 