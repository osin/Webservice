<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 9/30/14
 * Time: 7:00 PM
 */

namespace Library\Handler;

/**
 * Interface OutputHandler
 * @package Library\Handler
 */
interface OutputHandler {
    static function encode($data);
    static function decode($data);
}