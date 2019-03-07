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
 * Class to get the content to show
 */
class LSContent
{
    /**
     * Allows you to obtain the content, for a component
     * 
     * @param string $nomcomp The name of the component
     * 
     * @return string Content of the component
     */
    public static function render($nomcomp)
    {
        if (empty($nomcomp)) {
            // Error for not defining a component
            echo 'Error the component has not been established';
            
            return;
        }

        // Getting the name of the file
        $file = substr($nomcomp, 4);
            
        // We are going to define a variable for the component
        define('LS_COMPONENTE', LS_COM . '/' . $nomcomp);

        // Getting the file path
        $path = LS_COMPONENTE . '/' . $file . '.php';
    
        // Verifying if the file exists
        if (!file_exists($path)) {
            echo 'The file:'.$path.' not found';
        }
        
        // Getting the output
        $cont = null;
        $cont = self::elComponent($path);

        return $cont;
    }
    
    /**
     * Contents in render
     * 
     * @param string $path Route to obtain the information
     * 
     * @return string Content
     */ 
    protected static function elComponent($path)
    {
        ob_start();
        require_once $path;
        $cont = ob_get_contents();
        ob_end_clean();
        
        return $cont;    
    }
}
