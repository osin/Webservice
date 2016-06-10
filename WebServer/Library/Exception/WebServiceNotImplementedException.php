<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/08/14
 * Time: 15:51
 */

namespace Library\Exception;

use Library\Handler\TranslateHandler;

/**
 * Class WebServiceNotImplementedException
 * @package Library\Exception
 */
class WebServiceNotImplementedException extends Exception{

    protected $_message = "WebServiceNotImplementedException";
    protected $_code = 4560;

    public function __construct(){
        parent::__construct(TranslateHandler::get('errors', $this->_message), $this->_code);
    }
} 