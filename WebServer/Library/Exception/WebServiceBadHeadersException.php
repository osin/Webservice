<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/08/14
 * Time: 18:05
 */

namespace Library\Exception;

use Library\Handler\TranslateHandler;

/**
 * Class WebServiceBadHeadersException
 * @package Library\Exception
 */
class WebServiceBadHeadersException extends Exception{
    private $_message = "WebServiceBadHeadersException";
    private $_code = 4460;

    public function __construct(){
        parent::__construct(TranslateHandler::get('errors', $this->_message), $this->_code);
    }
}