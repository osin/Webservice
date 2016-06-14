<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 */
namespace Library;

/**
 * This class is responsible for the switch from the road passed as a parameter to the service.
 * It also has a garbage containing all parameters passed through the url
 * Class WebServiceDriver
 * @package WebService
 */
use Library\Exception\Exception;
use Library\Exception\MissingParametersException;

class WebServiceDriver
{

    /**
     * @var \Library\WebServiceGarbage
     */
    private $garbage;
    private $path;
    private $method;
    private $languages;
    private $data;

    /**
     * set path, garbage from GET | POST, method and Default Language
     * @param $returnType
     * @param $defaultLanguage = "en|fr|es|whatever" set the default language
     * @throws Exception
     */
    public function __construct($returnType, $defaultLanguage= "en")
    {
        if(self::isCommandLineInterface())
            $this->getParametersFromCommandLineInterface();
        else
            $this->getParametersFromHttp();
        $this->checkMinimumParameters();
        $this->garbage = new WebServiceGarbage($this->path, $this->data);
        $this->returnTYpe = $returnType;
        $this->setLanguages($defaultLanguage);
    }

    /**
     * Check minimum requirements to instantiate class
     * @throws MissingParametersException
     */
    private function checkMinimumParameters(){
        if(!$this->path)
            throw new MissingParametersException(__CLASS__, '$this->path');
        if(!$this->method)
            throw new MissingParametersException(__CLASS__, '$this->method');
    }

    /**
     * Get shorts and longs parameters
     */
    private function getParametersFromCommandLineInterface(){
        $shortopts = "p:m:d:";

        $longopts  = array(
            "path:",
            "method:",
            "data:"
        );
        $options = array_merge($_SERVER['argv'], getopt($shortopts, $longopts));
        if(!is_array($options))
            return;
        $this->setPathFromCLIArgs($options);
        $this->setMethodFromCLIArgs($options);
        $this->setDataFromCLIArgs($options);
    }

    private function setDataFromCLIArgs($options){
        if(isset($options['d']))
            $this->data = $options['d'];
        if(isset($options['data']))
            $this->data = $options['data'];
    }

    private function getParametersFromHttp(){
        $this->setPathFromHTTPGet();
        $this->setMethodFromHTTPGet();
    }

    private function setPathFromHTTPGet(){
        if (isset($_GET['components']))
            $this->path = $_GET['components'];
    }

    private function setPathFromCLIArgs($options){
            if(isset($options['p']))
                $this->path = $options['p'];
            if(isset($options['path']))
            $this->path = $options['path'];
    }

    private function setMethodFromHTTPGet(){
        if (isset($_SERVER['REQUEST_METHOD']))
            $this->method = $_SERVER['REQUEST_METHOD'];
    }

    private function setMethodFromCLIArgs($options){
            if(isset($options['m']))
                $this->method = $options['m'];
            if(isset($options['method']))
                $this->method = $options['method'];
    }

    /**
     * Return the supposed relative path to webservice class
     * @return string
     */
    public function getWebServicePath()
    {

        $args = $this->garbage->getScheme();
        $path = '';

        foreach ($args as $key => $arg) {
            if ($key > 0 && ($key % 2 > 0))
                $path .= $arg . '/';
        }
        $path = $args[0] . '/' . $path;

        return $path;
    }

    /**
     * Get the method
     * @return GET|POST|PUT|DELETE and more..
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return \Library\WebServiceGarbage
     */
    public function getGarbage()
    {
        return $this->garbage;
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set languages
     * @param $defaultLanguageIfMissedByClient
     * @param $languages array() you can force specific languages.
     * @return $this
     */
    private function setLanguages($defaultLanguageIfMissedByClient, $languages = array())
    {
        $user_languages = $languages;

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            //1 step we want an array at the end, and localisation could have ';'
            if (strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ';') !== false) {
                $array = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                $localisations = implode(',', $array);
            } else {
                $localisations = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                if (strlen($localisations) == 2) { //langue is defined on 2 chars only
                    $user_languages[] = $localisations;
                }
            }

            //2nd step localisation could have ','
            if (strpos($localisations, ',') !== false) {
                $languages = explode(',', $localisations);
                foreach ($languages as $language) {
                    if (strlen($language) == 2) { //langue is defined on 2 chars only
                        $user_languages[] = $language;
                    }
                }
            }
        }

        //3rd step we have all languages accepted by client in array so we just had in last the default language
        if(isset($user_languages) && is_array($user_languages))
            $user_languages[] = $defaultLanguageIfMissedByClient;
        $this->languages = $user_languages;
        return $this;
    }

    static function isCommandLineInterface()
    {
        return (php_sapi_name() === 'cli');
    }
}