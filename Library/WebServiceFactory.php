<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 */
namespace Library;

use Library\Exception\NotYetImplementedException;
use Library\Exception\WebServiceNotFoundException;
use Library\Handler\HeaderHandler;
use Library\Helper\Inflector;
use Library\Security\Security;

/**
 * Factory service, use to provide the service being requested
 * Class WebServiceFactory
 * @package WebService
 */
class WebServiceFactory
{
    private $_auth;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->_auth = $security;
    }

    /**
     * @param WebServiceDriver $driver
     * @return Service\Service $service
     * @throws NotYetImplementedException
     * @throws WebServiceNotFoundException
     */
    public function getService(WebServiceDriver $driver)
    {
        $serviceClass = $this->getWebServiceClass($driver);
        $serviceFile = $this->getWebServiceFile($driver);
        if (!file_exists($serviceFile)){
            HeaderHandler::setStatus(404);
            throw new WebServiceNotFoundException($serviceClass, $serviceFile);
        }

        require $serviceFile;
        //We retrieve the service by calling the webservice class and passing to the webservice the garbage
        try{
            $reflection = new \ReflectionClass($serviceClass);
        }catch (\ReflectionException $e){
            HeaderHandler::setStatus(404);
            throw new WebServiceNotFoundException($serviceClass .' / '. $serviceClass .'s', $serviceFile);
        }

        $service = $reflection->newInstance();
        $service->init($driver->getGarbage());
        return $service;
    }

    /**
     * Get the class of the webService (GetAccount, CreateAccount, DeleteAccount, UpdateAccount...)
     * @param WebServiceDriver $driver
     * @return string
     * @throws NotYetImplementedException
     */
    private function getWebServiceClass(WebServiceDriver $driver)
    {
        $method = strtoupper($driver->getMethod());
        switch ($method) {
            case 'DELETE':
                $className = 'Delete';
                break;
            case 'POST':
                $className = 'Create';
                break;
            case 'PUT':
                $className = 'Update';
                break;
            case 'GET':
                $className = 'Get';
                break;
            default:
                WebServiceOutput::getInstance()->addError(-1, 'Unknow HTTP METHOD '.$method);
                throw new NotYetImplementedException();
        }
        $args = $driver->getGarbage()->getScheme();
        foreach ($args as $key => $arg) {
            if ($key % 2 != 0)
                $suffix = $arg; //sufix is name of entity in most of case (accounts, contacts, ...etc!)
        }

        /**
         * Standard classname is recognized in the urls by their entities name in lower case (accounts, contacts)
         */
        if(ctype_lower($suffix)){
            /*
             * The goal of this portion is complexe.
             * If you put a name in lower case it should be singularize to get the name of the class
             * in case you provide a name as suffix like SUGARCRM or RestVersion, we do not remove last character because it's a name
             */
            $suffix = ucfirst(Inflector::singularize($suffix));
        }
        $suffix = str_replace(' ', '', ucwords(str_replace('-', ' ', $suffix)));
        $className.=$suffix;
        return $className;
    }


    /**
     * Return the namespace
     * @param WebServiceDriver $driver
     *
     * @return string
     */
    private function getWebServiceNameSpace(WebServiceDriver $driver)
    {
        $args = $driver->getGarbage()->getShemeParts();
        $path = '';

        foreach ($args as $key => $arg) {
            if ($key > 0 && ($key % 2 > 0))
                $path .= ucfirst($arg) . '\\';
        }
        $path = ucfirst($args[0]) . '\\' . $path;

        return $path;
    }

    /**
     * @param WebServiceDriver $driver
     * @return string
     */
    private function getWebServiceFile(WebServiceDriver $driver)
    {
        return "services/{$driver->getWebServicePath()}{$driver->getMethod()}.php";
    }
}