<?php
/**
 * LSCorePHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019, LadySusy
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package		LSCorePHP
 * @author		LadySusy Dev
 * @copyright	Copyright (c) 2019, LadySusy (http://www.ladysusy.org/)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */
 
// Avoid direct access
defined('_LS') or die;

/**
 * Class LSLoads
 */ 
class LSLoads
{
    /**
     * The file that is imported
     * 
     * @var array
     */
    protected static $fileImported = array();
    
    /**
     * Import files
     * 
     * @param string $route, contains the file path to include
     * 
     * @return
     */
    public static function import($route)
    {
        // Verifying if the route is already imported
        if (!isset(self::$fileImported[$route])) {
           
            // Configure some variables
            $success  = false;
            $base = dirname(__FILE__);
            $path = str_replace('.', DIRECTORY_SEPARATOR, $route);
                
            // If the file exists to include
            if (is_file($base . '/' . $path . '.php')) {
                $success = (bool) include_once $base . '/' . $path . '.php';
            }
            self::$fileImported[$route] = $success;
        }

        return self::$fileImported[$route];
    }
    
    
    /**
     * Starting the automatic charger
     */ 
     public static function init() 
     {
         spl_autoload_register(array('LSLoads', 'loads'));
     }
    
    /**
     * This method allows me to load all the files located in lib/cms
     * 
     * @param  string $class What will we load
     * 
     * @return  void
     */
     private static function loads($class)
     {
        if (class_exists($class, false)) {
            return true;
        }

        // Separating LS from file name
        $routeLoads = LS_LIBRERIAS.'/helpers';
        $route = $routeLoads . '/' . strtolower($class) . '.php';

        // We include the file, verifying that there is
        if (file_exists($route)) {
            return include $route;
        }
     }
}
 
 /**
  * Importing necessary libraries when required
  * 
  * @param   string  $route, that will be what we are going to import
  * 
  * @return  boolean  True
 */
function ladysusyimport($route)
{
    return LSLoads::import($route);
}
