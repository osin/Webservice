<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 01/09/14
 * Time: 17:40
 */

namespace Library\Handler;

use Library\Exception\NotYetImplementedException;
use Library\Handler\HeaderHandler;

/**
 * Class XmlHandler
 * @package Library\Handler
 */
abstract class XmlHandler implements OutputHandler{
    static function encode($data){
        /**
         * @see http://www.akchauhan.com/php-class-for-converting-xml-to-object-and-object-to-xml/
         * You'll find help to implement this method
         */
        HeaderHandler::setStatus(501);
        throw new NotYetImplementedException();
    }

    static function decode($data){
        return simplexml_load_string($data);
    }
} 