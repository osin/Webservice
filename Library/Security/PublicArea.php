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
 * Class PublicArea
 * @package Library\Security
 */
class PublicArea extends Security{

    function __construct(WebServiceDriver $driver){
        $this->driver = $driver;
    }

    public function checkAccess(){
        throw new NotYetImplementedException();
    }
}