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
 * Class EntityNotFoundException
 * @package Library\Exception
 */
class EntityNotFoundException extends Exception{
    private $_message = "EntityNotFoundException";
    private $_code = 4440;

    public function __construct($model = null, $identifiers = null){
        parent::__construct(TranslateHandler::get('errors', $this->_message, $model, $identifiers), $this->_code);
    }
} 