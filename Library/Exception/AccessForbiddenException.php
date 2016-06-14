<?php
/**
 * Access Forbidden Exception Class
 * 
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 29/08/14
 * Time: 18:30
 */


namespace Library\Exception;
use Library\Handler\TranslateHandler;

/**
 * Class AccessForbiddenException
 * @package Library\Exception
 */
class AccessForbiddenException extends Exception{
    private $_message = "AccessForbiddenException";
    private $_code = 4310;

    public function __construct(){
        parent::__construct(TranslateHandler::get('errors', $this->_message), $this->_code);
    }
} 