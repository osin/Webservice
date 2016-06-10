<?php
/**
 * @author: Housseini Toumani
 * @package Osin/Tools
 * @link https://github.com/Osin/Tools
 * Date: 02/09/14
 * Time: 13:53
 */

namespace Library\Handler;

use Library\Exception\Exception;
use Library\Exception\JsonException;
use Library\WebServiceDriver;

/**
 * Class TranslateHandler
 * @package Library\Handler
 */
abstract class TranslateHandler
{
    const default_language = 'en';

    /** get the message in correct
     * @param $code
     *
     * @return mixed
     */
    static function get($path, $code)
    {
        $translations = self::getLocalisation($path);
        if (isset($translations) && isset($translations[$code]))
            $message = $translations[$code];
        else
            return $code;
        $args = func_get_args();
        //retrieve arguments from function and pass them to the translated message
        if (count($args) > 2) {
            array_shift($args);
            array_shift($args);
            return vsprintf($message, $args);
        }
        return $message;
    }

    /**
     * Get file locale in specific language
     * @param $path
     * @param boolean $reloadFile force to reload the file, usefull for tests case
     * @return mixed|array|null
     * @throws \Library\Exception\Exception
     */
    static function getLocalisation($path)
    {
        $translations = null;
        global $driver;
        if (!($driver instanceof WebServiceDriver))
            $languages = array(self::default_language);
        else
            $languages = $driver->getLanguages();
        foreach ($languages as $language) {
            chdir(__ROOT_DIR__);
            $lang_file = "locale/$path/" . $language . '.json';
            if (is_file($lang_file)) {
                $lang_file_content = file_get_contents($lang_file);
                $translations = json_decode($lang_file_content, true);
                if($translations === null)
                    throw new Exception("An error has been throw but langage is not valid JSON");
                return $translations;
            }
        }
    }
}