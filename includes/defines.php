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

// Global definitions
$parts = explode(DIRECTORY_SEPARATOR, LS_BASE);
 
// File paths
define('LS_ROOT', implode(DIRECTORY_SEPARATOR, $parts));
define('LS_APLICACION', LS_ROOT);
define('LS_CONFIGURACION', LS_ROOT);
define('LS_LIBRERIAS', LS_ROOT . '/lib');
define('LS_ADMINISTRACION', LS_ROOT . '/admin');
define('LS_LENGUAJE', LS_ROOT . '/language');
define('LS_COM', LS_ROOT . '/ladysusycom');
define('LS_LOGS', LS_ROOT . '/logs/');
define('LS_TEMPLATE', LS_ROOT . '/template');

// Definitions for error handling
define("ADMIN_EMAIL", "jrobertoalas@gmail.com");
define("LOG_FILE", LS_LOGS."error.log");
 
// Destination types
define("DEST_EMAIL", "1");
define("DEST_LOGFILE", "3");
