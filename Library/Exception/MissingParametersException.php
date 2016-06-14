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
class MissingParametersException extends Exception{
    private $_message = "MissingParametersException";
    private $_code = 4450;

    /**
     * @param string|null $model
     * @param string|null $missingArgs
     */
    public function __construct($model = null, $missingArgs = null){
        parent::__construct(TranslateHandler::get('errors', $this->_message, $model, $missingArgs), $this->_code);
    }
} 