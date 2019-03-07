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

define('_LS', 1);

/**
 * Class that allows me to define the environment variables of the system
 */
class LSConfig
{
    /**
     * Server URL
     */ 
    public $baseUrl = 'http://localhost';
    
    /**
     * Server
     */ 
    public $server = 'localhost';

    /**
     * System name
     */ 
    public $appName = 'Mi Agenda';
    
    /**
     * Security Hash
     */ 
    public $hashKey = '5037a60152ac0';

    /**
     * User of database
     */ 
    public $user = 'core';
    
    /**
     * Database password
     */ 
    public $password = 'corepwd';
    
    /**
     * ID root
     */ 
    public $userRoot = 1;

    /**
     * Database
     */ 
    public $database = 'lscorephp';
    
    /**
     * Prefix of tables in the database
     */ 
    public $dbprefix = 'ls_';

    /**
     * Language of the application
     */ 
    public $language = 'en-US';
    
    /**
     * State of trace
     */ 
    public $trace = false;

    /**
     * Template defined
     */ 
    public $template = 'ladysusy';
    
    /**
     * Timezone
     */
    public $timezone = 'America/El_Salvador';
    
    /**
     * Ubicación 
     */
    public $locale = 'es_ES';
   
    /**
     * Version of the application
     */ 
    public $version = '1.0';

    /**
     * Application channel
     */ 
    public $channel = 'development';
}
