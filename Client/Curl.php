<?php
/**
 * A Curl Class to manage your call
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 02/04/15
 * Time: 12:06
 */

namespace Tools;

/**
 * Class Curl
 * @package Tools
 */
class Curl {
    static $ContentTypeJson = 'application/json';
    static $ContentTypeFormData = 'application/x-www-form-urlencoded';


    protected $curl;
    protected $headers = array();
    protected $url;
    protected $httpMethod;
    protected $postFields;
    private $states = array();
    private $curlResult;
    private $curlInfo;
    private $curlErrors;
    private $curlHttpCodeReturn;

    /**
     * @param $host Host you want to request
     * @param $path ressources to fecth
     * @param $httpMethod GET, POST, PUT, DELETE, PATCH
     */
    function __construct($host, $path="", $httpMethod = 'GET'){
        $this->url = $host.$path;
        $this->httpMethod = $httpMethod;
        return $this;
    }

    function setData($data, $contentType){
        $this->headers['Content-type'] = $contentType;
        switch ($contentType) {
            case self::$ContentTypeFormData:
                $this->postFields = http_build_query($data);
                break;
            case self::$ContentTypeJson:
                $this->postFields = json_encode($data);
                break;
            default:
                return $this;
        }
        $this->afterSetData();
        return $this;
    }

    protected function afterSetData(){
        $this->setContentLength();
    }

    private function setContentLength(){
        $this->headers['Content-Length'] = strlen($this->postFields);
        return $this;
    }

    /**
     * this method is call in Curl::exec() after curl_init()
     */
    protected function beforeExec(){
    }

    function exec(){
        global $sugar_config;
        $this->curlResult = $this->curlErrors = $this->curlInfo = $this->curlHttpCodeReturn = null;
        $this->curl = curl_init();
        $this->beforeExec();
        if(isset($sugar_config['curl_ssl_verifypeer'])){
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, $sugar_config['curl_ssl_verifypeer']);
        }
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->httpMethod);
        if(count($this->headers)){
            curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->formatHeaders());
        }
        if($this->postFields)
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->postFields);
        $this->curlResult = curl_exec($this->curl);
        $this->curlInfo = curl_getinfo($this->curl);
        $this->curlError = curl_error($this->curl);
        curl_close($this->curl);
        if(isset($this->curlInfo['http_code']))
            $this->curlHttpCodeReturn = $this->curlInfo['http_code'];
        $this->setState();
        return $this;
    }

    function getStates(){
        return $this->states;
    }
    private function setState(){
        $this->states[] = clone $this;
    }

    public function getLastState(){
        return $this->states[count($this->states)-1];
    }

    function getCurlResult(){
        return $this->curlResult;
    }

    function getCurlInfo(){
        return $this->curlInfo;
    }

    function getCurlError(){
        return $this->curlErrors;
    }

    function getCurlHttpCodeReturn(){
        return $this->curlHttpCodeReturn;
    }

    /**
     * @param array $parameters associative array
     */
    function setQueryParameters($parameters = array()){
        $this->url .='?'.http_build_query($parameters);
    }

    /**
     * @param array $headers
     */
    function setHeaders($headers = array()){
        foreach($headers as $key => $value){
            $this->headers[$key] = $value;
        }
    }

    /**
     * @return array $returnHeaders Formated Headers
     */
    protected function formatHeaders()
    {
        $returnHeaders = array();
        foreach($this->headers as $key => $value)
        {
            $returnHeaders[] = $key . ': ' . $value;
        }
        return $returnHeaders;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return mixed
     */
    public function getPostFields()
    {
        return $this->postFields;
    }
}