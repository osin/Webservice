<?php
namespace Library\Security;

use Library\WebServiceDriver;

interface SecurityInterface
{

    function __construct(WebServiceDriver $driver, $clientId);

    function checkAccess();

    function getClientId();
}