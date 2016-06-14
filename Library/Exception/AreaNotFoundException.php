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
 * Class AreaNotFoundException
 * @package Library\Exception
 */
class AreaNotFoundException extends Exception{
    private $_message = "AreaNotFoundException";
    private $_code = 4410;

    public function __construct($area){
        TranslateHandler::get('errors', $this->_message, $area);
        parent::__construct(TranslateHandler::get('errors', $this->_message, $area), $this->_code);
    }
} 