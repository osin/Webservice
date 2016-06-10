<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 29/08/14
 * Time: 18:30
 */


namespace Library\Exception;
use Library\Handler\TranslateHandler;

/**
 * Class AreaNotFoundException
 * @package Library\Exception
 */
class DuplicateEntityException extends Exception{
    private $_message = "DuplicateEntityException";
    private $_code = 4430;

    public function __construct($model, $string){
        parent::__construct(TranslateHandler::get('errors', $this->_message, $model, $string), $this->_code);
    }
} 