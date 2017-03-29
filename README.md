# Webservice
A Web Service REST base if you want to work on REST protocol and publish some services.

This project use a Helper file from the Akelos Framework
FileName is Inflector
Inflector for pluralize and singularize English nouns.
This Inflector is a port of Ruby on Rails Inflector. It can be really helpful for developers that want to create 
frameworks based on naming conventions rather than configurations.
It was ported to PHP for the Akelos Framework, a multilingual Ruby on Rails like framework for PHP that will be launched
soon.
@author Bermi Ferrer Martinez
@copyright Copyright (c) 2002-2006, Akelos Media, S.L. http://www.akelos.org
@license GNU Lesser General Public License
@since 0.1
@version $Revision 0.1

### Please fill config.php.default => config.php

As soon as filled you must implement a Security Area Method. I gave you two SecurityArea, public and secure. You can decide which one you want to call.


You should provide theses arguments to use the api in cli mod

        $shortopts = "p:m:d:";

        $longopts  = array(
            "path:", //exemple /{securityArea}/{entities}/{identity}/{entitiesChild1}/ etc....
            "method:", an HTTP METHOD
            "data:" Data can be passe by many way, using http you should pass it in the body, using cli it's must be provide by data = {data}
        );
        
        
# Install
First please copy every dist to non dist ext files. Exemple config.php.dist should become config.php
* .htaccess.dist => .htaccess
* .config.php.dist => config.php


##.htAccess installation
HtAccess define entrypoint to get all the service. We rewrite all urls to specific endpoint which is bootstrap.php.
You can define your own pattern url for this specific endpoint.
We also add instruction to no index all files.
You can remove or manage freely you htaccess according to your fairs uses, security restrictions or/and vhosts

##Domain installation

After that you should define a domaine. A domain is a global path for an specific project, use case or application
Ex: domain public for services from publics domaines or domain backoffice for backoffice services.

To define a domaine 
