<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 */
namespace Library\Security;
use Library\WebServiceDriver;
use Library\Exception\NotYetImplementedException;

/**
 * Class DefaultSecurity
 * @package Library\Security
 */
class DefaultSecurity implements SecurityInterface {

    /**
     * @var WebServiceDriver
     */
    protected $driver;

    protected $clientId;

    function __construct(WebServiceDriver $driver, $clientId){
        $this->driver = $driver;
        $this->clientId = $clientId;
    }

    public function checkAccess(){
        throw new NotYetImplementedException();
    }

    /**
     * @return string ClientId
     */
    public function getClientId()
    {
        return $this->clientId;
    }
}