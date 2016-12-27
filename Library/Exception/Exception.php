<?php
/**
 * WebService Exception Class overriding default exception with localisation
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 04/08/14
 * Time: 10:28
 */

namespace Library\Exception;


use Library\WebServiceOutput;
use Library\Handler\HeaderHandler;

class Exception extends \Exception {

    /**
     * Exception constructor.
     * Construct an exception with a message. Should be extend to use message and code
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = -1, Exception $previous = null){
        $output = WebServiceOutput::getInstance();
        $output
            ->setState(WebServiceOutput::errorState)
            ->addError($code, $message);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Handle eache type of exception,
     * if a fatal exception is catch, the handler will stop process
     * @return false, always returning false to allow php to hand error
     */
    static function exceptionHandler() {
        $error = error_get_last();
        $output = WebServiceOutput::getInstance();
        $output->addError(-1, $error);
        if($error['type'] == 1){
            if(HeaderHandler::getStatus() < 500)
                HeaderHandler::setStatus(500);
            $output->write();
            exit();
        }
        // we execute after the local php error handler
        return false;
    }

    /**
     * This function allow you to use the exception handler
     */
    static function registerExceptionHandler(){
        register_shutdown_function(array(__CLASS__, 'exceptionHandler'));
    }
}
