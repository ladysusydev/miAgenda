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

// Directional separator
if (!defined('SD')) {
    define('SD', DIRECTORY_SEPARATOR);
}

if (!defined('LS_BASE')) {
    $routeBaseTemp = dirname(__FILE__);
    $routeBase = strstr($routeBaseTemp, SD.LS_APP, true);
    define('LS_BASE', $routeBase.SD.LS_APP);
    
    // Include definitions and main libraries
    require_once LS_BASE.SD.'includes'.SD.'defines.php';
}

// Load configuration
ob_start();
require_once LS_CONFIGURACION.SD.'configuration.php';
ob_end_clean();

// System configuration
$config = new LSConfig();

// Establishing bug reports
switch ($config->channel) {
    case 'default':
    case '-1':
        break;
    case 'none':
    case '0':
        error_reporting(0);
        break;
    case 'simple':
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        ini_set('display_errors', 1);
        break;
    case 'maximum':
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        break;
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;
    default:
        error_reporting($config->canal);
        ini_set('display_errors', 1);
        break;
}

// Establecer timezone y ubicación
setlocale(LC_ALL, $config->locale);
date_default_timezone_set($config->timezone);

unset($config);

// Importing libraries
require_once LS_LIBRERIAS.SD.'import.php';
