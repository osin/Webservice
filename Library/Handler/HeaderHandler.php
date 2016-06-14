<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 09/12/14
 * Time: 11:49
 */

namespace Library\Handler;
use Library\WebServiceDriver;

/**
 * Class HeaderHandler
 * @package Library\Handler
 */
abstract class HeaderHandler {

    /**
     * define a header with the http code and message
     * @param $statusCode
     */
    static function setStatus($statusCode) {
        static $status_codes = null;

        if ($status_codes === null) {
            $status_codes = array (
                100 => 'Continue',
                101 => 'Switching Protocols',
                102 => 'Processing',
                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',
                207 => 'Multi-Status',
                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                307 => 'Temporary Redirect',
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                422 => 'Unprocessable Entity',
                423 => 'Locked',
                424 => 'Failed Dependency',
                426 => 'Upgrade Required',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
                506 => 'Variant Also Negotiates',
                507 => 'Insufficient Storage',
                509 => 'Bandwidth Limit Exceeded',
                510 => 'Not Extended'
            );
        }

        if ($status_codes[$statusCode] !== null) {
            $status_string = $statusCode . ' ' . $status_codes[$statusCode];
            //For Test Case, PHPUnit call multiple test at once and headers cannot change a lot
            if(!WebServiceDriver::isCommandLineInterface()){
                if(isset($_SERVER['SERVER_PROTOCOL']))
                    header($_SERVER['SERVER_PROTOCOL'] . ' ' . $status_string, true, $statusCode);
                else
                    header($status_string, true, $statusCode);
            }
            $GLOBALS['http_response_code'] = $statusCode;
        }
    }

    /**
     * Set a custom header string
     * @param $customHeader
     */
    static function setCustomHeader($customHeader){
        header($customHeader);
    }

    static function getStatus(){
        $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        return $code;
    }

    /**
     * Set No Cache Control
     */
    static function setNoCacheControl(){
        header('Cache-Control: no-cache, must-revalidate');
    }

    /**
     * Get header from the client
     * @return array
     */
    static function getHeaderFromClient(){
        $allHeaders = array();
        if (function_exists('getallheaders')) {
            $allHeaders = getallheaders();
        } else {
            foreach ($_SERVER as $h => $v) {
                if (preg_match('/HTTP_(.+)/', $h, $hp))
                    $allHeaders[$hp[1]] = $v;
            }
        }

        return $allHeaders;
    }

    /**
     * @param $dateString in format D, Mon, 26 Jul 1997 05:00:00 GMT
     */
    static function setExpire(\DateTime $date){
        header('Expires: ' . $date->format('D, d M Y H:i:s e'));
    }

    /** Set Content Type
     * @param $type json|xml|http
     * @param string $charset, default = utf-8
     */
    static function setContentType($type, $charset='utf-8'){
        switch ($type){
            default:
                header('Content-type: application/' . $type . '; charset='.$charset);
        }
    }
} 