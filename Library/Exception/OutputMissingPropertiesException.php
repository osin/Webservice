<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 28/08/14
 * Time: 15:51
 */

namespace Library\Exception;

use Library\Handler\TranslateHandler;

/**
 * Class OutputMissingPropertiesException
 * @package Library\Exception
 */
class OutputMissingPropertiesException extends Exception{

    private $_message = "OutputMissingPropertiesException";
    private $_code = 4540;

    public function __construct(){
        parent::__construct(TranslateHandler::get('errors', $this->_message), $this->_code);
    }
} 