<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 */
namespace Library\Security;

use Library\Autoloader;
use Library\Exception\AreaNotFoundException;
use Library\Exception\NotYetImplementedException;
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
    const DEFAULT_SECURITY = 'system';

    /**
     * @var WebServiceDriver $driver
     */
    protected $driver;

    /**
     * @var unique client identifier
     */
    protected $id;

    /**Return security object
     *
     * @param \Library\WebServiceDriver $driver
     *
     * @return PublicArea|SecuredArea
     * @throws AreaNotFoundException
     */
    static function getSecurity(WebServiceDriver $driver, $id)
    {
        //recupère le domain public ou secure
        $area = $driver->getGarbage()->getArea();
        $className = __NAMESPACE__ . __NAMESPACE_SEPARATOR__ .ucfirst($area . 'Security');

        if (file_exists(Autoloader::getFilePathFromNameSpacedClass($className))) {
            return new $className($driver, $id);
        }

        HeaderHandler::setStatus(404);
        throw new AreaNotFoundException($area);
    }


    public function getId()
    {
        return $this->id;
    }

    function __construct(WebServiceDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     *
     */
    public function checkAccess()
    {
        throw new NotYetImplementedException();
    }
}