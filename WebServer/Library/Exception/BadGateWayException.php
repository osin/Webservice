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
 * Class NotYetImplementedException
 * @package Library\Exception
 */
class BadGateWayException extends Exception{
    private $_message = "BadGateWayException";
    private $_code = 4510;

    public function __construct(){
        parent::__construct(TranslateHandler::get('errors', $this->_message), $this->_code);
    }
} 