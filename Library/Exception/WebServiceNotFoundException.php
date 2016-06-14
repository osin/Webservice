<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 28/08/14
 * Time: 15:29
 */

namespace Library\Exception;

use Library\Handler\TranslateHandler;

/**
 * Class WebServiceNotFoundException
 * @package Library\Exception
 */
class WebServiceNotFoundException extends Exception{
    protected $_message = "WebServiceNotFoundException";
    protected $_code = 4470;

    public function __construct($serviceClass, $serviceFile){
        parent::__construct(TranslateHandler::get('errors', $this->_message, $serviceClass, $serviceFile), $this->_code);
    }
}