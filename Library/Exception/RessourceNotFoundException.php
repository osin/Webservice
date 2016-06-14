<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 22/06/15
 * Time: 14:46
 */

namespace Library\Exception;
use Library\Handler\TranslateHandler;


class RessourceNotFoundException extends Exception {
    private $_message = "RessourceNotFoundException";
    private $_code = 4450;

    public function __construct($ressourceName){
        parent::__construct(TranslateHandler::get('errors', $this->_message, $ressourceName), $this->_code);
    }
}