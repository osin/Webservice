<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 */
namespace Library\Security;
use Library\Exception\AreaNotFoundException;
use Library\WebServiceDriver;
use Library\Handler\HeaderHandler;

/**
 * Class Security
 * This class manage the security
 * Implement this class (and submethods) if you want to check access
 * Note that in case of not access, must throw an error
 * @package Library\Security
 */
abstract class Security
{
    const secureArea = 'secure';
    const publicArea = 'public';
    const systemArea = 'system';

    /**
     * @var WebServiceDriver $driver
     */
    protected $driver;

    protected $id;

    /**Return security object
     *
     * @param \Library\WebServiceDriver $driver
     *
     * @return PublicArea|SecuredArea
     * @throws \Exception
     */
    static function getSecurity(WebServiceDriver $driver)
    {
        //recupère le domain public ou secure
        $area = $driver->getGarbage()->getArea();
        switch ($area) {
            case self::secureArea:
                return new SecuredArea($driver);
            case self::publicArea:
                return new PublicArea($driver);
            case self::systemArea:
                return new SystemArea($driver);
            default:
                HeaderHandler::setStatus(404);
                throw new AreaNotFoundException($area);
        }
    }




    public function getId(){
        return $this->id;
    }

    /** You must implement this function by domain
     * @return mixed
     */
    abstract function checkAccess();
}