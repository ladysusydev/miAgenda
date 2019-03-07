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

// Importing library that will allow me to include the scripts
if (!class_exists('LSLoads')) {
    require_once LS_LIBRERIAS .'/loads.php';
}

// Starting the automatic charger
LSLoads::init();

// Importing the main libraries
LSLoads::import('ladysusy.ladysusy');
LSLoads::import('ladysusy.database.database');
LSLoads::import('ladysusy.model');
LSLoads::import('ladysusy.database.queries');
LSLoads::import('ladysusy.database.requests');
LSLoads::import('ladysusy.session');
LSLoads::import('ladysusy.environment.reqenvironment');
LSLoads::import('ladysusy.error');
LSLoads::import('ladysusy.user');
LSLoads::import('ladysusy.environment.content');
LSLoads::import('ladysusy.answer');
LSLoads::import('ladysusy.text');
LSLoads::import('ladysusy.hash');
LSLoads::import('ladysusy.controller');
LSLoads::import('ladysusy.view');

/**
 * Importing the library to obtain components
 */ 
if (!class_exists('LSComponent')) {
    require_once LS_APLICACION.'/includes/component.php';
}
