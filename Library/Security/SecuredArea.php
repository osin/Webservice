<?php

/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 */
namespace Library\Security;
use Library\WebServiceDriver;

/**
 * Class SecuredArea
 * @package Library\Security
 */
class SecuredArea extends Security{

    function __construct(WebServiceDriver $driver){
        $this->driver = $driver;
    }

    /**
     *
     */
    public function checkAccess(){
    }
}