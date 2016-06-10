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
 * Class WebServiceOutputIncorrect
 * @package Library\Exception
 */
class WebServiceOutputIncorrect extends Exception{
    private $_message = "WebServiceOutputIncorrect";
    private $_code = 4570;

    public function __construct(){
        parent::__construct(TranslateHandler::get('errors', $this->_message), $this->_code);
    }
} 