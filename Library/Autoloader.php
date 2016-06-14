<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 9/25/14
 * Time: 4:52 PM
 */

namespace Library;
/**
 * Class Autoloader
 * @package Library
 */
class Autoloader
{
    /**
     * Register autoload function
     */
    static public function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Autoloading system
     * Transform namespace structure into directory structure (\NS1\NS2\NS3\className will be
     * search into __DIR__ . '/NS1/NS2/NS3/className.php').
     */
    static public function autoload($class)
    {
        // Autoload only "sub-namespaced" class
        if (strpos($class, __NAMESPACE__.'\\') === 0)
        {
            // Delete current namespace from class one
            $relative_NS     = str_replace(__NAMESPACE__, '', $class);
            // Translate namespace structure into directory structure
            $translated_path = str_replace('\\', '/', $relative_NS);
            // Load class
            $path = __DIR__ . '/' . $translated_path . '.php';

            if(file_exists($path))
                require $path;
            else
                throw new \Exception('FILE NOT FOUND'.$path);

        }
    }
}