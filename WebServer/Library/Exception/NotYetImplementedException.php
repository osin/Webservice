<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29/08/14
 * Time: 18:30
 */

namespace Library\Exception;

use Library\Handler\TranslateHandler;

/**
 * Class NotYetImplementedException
 * @package Library\Exception
 */
class NotYetImplementedException extends Exception{
    private $_message = "NotYetImplementedException";
    private $_code = 4530;

    public function __construct(){
        parent::__construct(TranslateHandler::get('errors', $this->_message), $this->_code);
    }
} 