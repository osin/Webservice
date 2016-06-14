<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 01/09/14
 * Time: 17:40
 */

namespace Library\Handler;
use Library\Exception\JsonException;

/**
 * Class JsonHandler
 * @package Library\Handler
 */
abstract class JsonHandler implements OutputHandler {



    public static function encode($value, $options = 0) {
        $result = json_encode($value, $options);
        if($result)  {
            return $result;
        }
        throw new JsonException();
    }

    public static function decode($json, $assoc = false) {
        $result = json_decode($json, $assoc);
        if($result === null)
            throw new JsonException();
        return $result;
    }
}