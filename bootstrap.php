<?php
define('__START_EXECUTION_TIME__', microtime(true));
require 'config.php';
chdir(__ROOT_DIR__); // allow to call from whatever path
require 'Library/Autoloader.php';

use Library\Autoloader;
use \Library\Handler\HeaderHandler;
use \Library\Security\Security;
use \Library\WebServiceDriver;
use \Library\WebServiceFactory;
use \Library\WebServiceOutput;
use \Library\Exception\Exception;

Autoloader::register();
Exception::registerExceptionHandler();

HeaderHandler::setNoCacheControl();
HeaderHandler::setExpire((new DateTime())->setTimestamp(__EXPIRE_DURATION__));
HeaderHandler::setContentType(__OUTPUT_TYPE__);

global $auth, $driver, $wsFactory; //theses objects are used anywhere in application

//retrieve path and arguments and configurations
$driver = new WebServiceDriver(__OUTPUT_TYPE__, __DEFAULT__LANGUAGE__);

// Retrieving correct security agent (by domain)
$auth = Security::getSecurity($driver, HeaderHandler::getHeaderFromClient("User-Agent"));
$auth->checkAccess();

// Getting service called
$wsFactory = new WebServiceFactory($auth);
$service = $wsFactory->getService($driver);

/*
 * To prevent bad programming method you need to call this method
 * Developer must return a web service output
 */
if(!($service->exec() instanceof Library\WebServiceOutput))
    throw new Library\Exception\WebServiceOutputIncorrect();

WebServiceOutput::getInstance()->write();
