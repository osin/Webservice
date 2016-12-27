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
            "method:",
            "data:"
        );
