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
 * Class for the singlenton employer
 */
class LSDatabase extends mysqli
{
    /**
     * @var array Allows to instantiate the class only once
     */
    private static $_instance = array();
        
    /**
     * Method that allows me to instantiate the connection only 
     * to the database, avoiding instances of future connections
     * 
     * @return An object of the LSDatabase class
     */
    public static function getInstance()
    {
        $key = get_called_class() . serialize(func_get_args());
        if (!isset(self::$_instance[$key])) {
            $c = new ReflectionClass(get_called_class());
            self::$_instance[$key] = $c->newInstanceArgs(func_get_args());
        }
        
        return self::$_instance[$key];
    }
}

/**
 * Class that connects to the database
 */ 
class LSDb extends LSDatabase
{
    /**
     * @var string host name
     */
    private $_host;
        
    /**
     * @var string user of the database
     */
    private $_user;
        
    /**
     * @var string password of the user of the database
     */
    private $_password;
        
    /**
     * @var string name of the database
     */
    private $_bd;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $conf = LSPrincipal::getConfig();
        $this->_host = $conf->server;
        $this->_user = $conf->user;
        $this->_password = $conf->password;
        $this->_db = $conf->database;
                
        @parent::__construct($this->_host, 
                    $this->_user, 
                    $this->_password, 
                    $this->_db
                    );
                
        if (mysqli_connect_error()) {
            die('Connection error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
        }
                
        $this->set_charset('utf8');
    }
    
    /**
     * Obtener A new object of LSDatabaseQueries
     *
     * @return Object LSDatabaseQueries
     */
    public function getQuery()
    {
        if (!class_exists('LSDatabaseQueries')) {
            die('Failed to load the class, for SQL queries');
        }
        
        return new LSDatabaseQueries($this);
    }
    
    /**
     * Getting the query
     * 
     * @param  string  $query The SQL query
     *
     * @return object of the consulted data
     */
    public function setQuery($query)
    {
        $_db = self::getInstance();
        
        // Formatting the SQL query
        $sql = $this->prepareSQL($query);
        $resultSQL =  $_db->query($sql);
        
        return $resultSQL;
    }
        
    /**
     * Formatting the SQL query
     * 
     * @param string  $sql SQL query
     * @param string  $prefix The prefix set for tables
     * 
     * @return string SQL formatted
     */ 
     public function prepareSQL($sql, $prefix = '&&__')
     {
        $conf = LSPrincipal::getConfig();
        $sqlForm = str_replace($prefix, $conf->dbprefix, $sql);
    
        return $sqlForm;
     }
     
    /**
     * Clone is not allowed
     */
    public function __clone()
    {
        throw new Exception("Cannot clone ".__CLASS__." class"); 
    }
}   
