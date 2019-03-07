<?php
/**
 * LSCorePHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2018, LadySusy
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
 * Variable requirement
 */
class LSReqenvironment
{
    /**
     * Initializing the URL variable
     * 
     * @return boolean true
     */
    public static function initialize()
    {
        // Getting the full path
        $URL = 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        // Passing necessary variables
        $var = self::passed($URL);
        
        self::set($var, 'get', false);
        
        return true;
    }
    
    /**
     * It allows to process a URL
     * 
     * @param string $URL URL to process
     * 
     * @return array with variables passed
     */
    public static function passed($URL)
    {
        if ($parts = self::URL_pieces($URL)) {
            if (isset($parts['query'])) {
                parse_str($parts['query'], $output);
            } else {
                $output = array('ladysusycom'=>'com_base', 'view'=>'base');
            }
        }
        
        return $output;
    }
    
    /**
     * It allows me to split the URL
     * 
     * @param string Starting URL
     * 
     * @return array
     */ 
    public static function URL_pieces($URL)
    {
        $result = array();
        $entities = array('%21', '%2A', 
                          '%27', '%28', 
                          '%29', '%3B', 
                          '%3A', '%40', 
                          '%26', '%3D', 
                          '%24', '%2C', 
                          '%2F', '%3F', 
                          '%25', '%23', 
                          '%5B', '%5D');
            
        $replacements = array('!', '*', "'", 
                              "(", ")", ";", 
                              ":", "@", "&", 
                              "=", "$", ",", 
                              "/", "?", "%", 
                              "#", "[", "]");
        $encodedURL = str_replace($entities, $replacements, urlencode($URL));
        $encodedParts = parse_url($encodedURL);

        foreach ($encodedParts as $key => $value) {
            $result[$key] = urldecode($value);
        }
    
        return $result;
    }
    
    /**
     * Gets a past variable
     * 
     * @param string $name Name of the variable
     * @param boolean $default True o False
     * @param string $method Default value
     * 
     * @return requested variable
     */
    public static function getVar($name, $default = null, $method = 'default')
    {
        // Convirtiendo el metodo en mayusculas
        $method = strtoupper($method);

        // Obtener el metodo.
        switch ($method) {
            case 'GET':
                $entry = &$_GET;
                break;
            case 'POST':
                $entry = &$_POST;
                break;
            case 'FILES':
                $entry = &$_FILES;
                break;
            case 'COOKIE':
                $entry = &$_COOKIE;
                break;
            case 'ENV':
                $entry = &$_ENV;
                break;
            case 'SERVER':
                $entry = &$_SERVER;
                break;
            default:
                $entry = &$_REQUEST;
                $method = 'REQUEST';
                break;
        }
    
        if (isset($entry[$name]) && $entry[$name] !== null) {

            if (is_string($entry[$name])) {
                $var = trim($entry[$name]);
            }
            if (get_magic_quotes_gpc() && ($var != $default)) {
                $var = stripslashes($var);
            }

        } elseif ($default !== null) {
            $var = trim($entry[$name]);
        } else {
            $var = $default;
        }
        
        return $var;
    }
    
    /**
     * Set a past variable
     * 
     * @param string $name Name of the variable
     * @param boolean $valor True o False
     * @param string $metodo The type of method used
     * @param boolean $sc
     * 
     * @return previous variable
     */ 
    public static function setVar($name, $value = null, $method = 'get', $scc = true)
    {
        if (!$scc && array_key_exists($name, $_REQUEST)) {
            return $_REQUEST[$name];
        }
        
        $method = strtoupper($method);
        $pre = array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;

        switch ($method) {
            case 'GET':
                $_GET[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
                
            case 'POST':
                $_POST[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
        }
        
        return $pre;
    }
    
    /**
     * Method set for this class
     * 
     * @param array $array An array of values
     * @param string $metodo Type POST or GET
     * @param boolean $sc
     * 
     * @return void
     */ 
    public static function set($array, $method = 'get', $scc = true)
    {
        foreach ($array as $key => $value) {
            self::setVar($key, $value, $method, $scc);
        }
    }
}
