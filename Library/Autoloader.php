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
     * @param $class class to require
     * @throws \Exception throw basic \Exception
     */
    static public function autoload($class)
    {
        // Autoload only "sub-namespaced" class
        if (strpos($class, __NAMESPACE__.'\\') === 0)
        {
            $path = self::getFilePathFromNameSpacedClass($class);
            if(file_exists($path))
                require $path;
            else
                throw new \Exception('FILE NOT FOUND '.$path);

        }
    }

    static public function getFilePathFromNameSpacedClass($nameSpacedClassName) {
        // Delete current namespace from class one
        $relativeNameSpace     = str_replace(__NAMESPACE__, '', $nameSpacedClassName);
        // Translate namespace structure into directory structure
        $translatedPath = str_replace('\\', '/', $relativeNameSpace);
        // Load class
        $path = __DIR__ . '/' . $translatedPath . '.php';
        return $path;
    }
}