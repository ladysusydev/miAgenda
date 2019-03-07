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
 * Class for the main model
 */
class LSModel
{
    /**
     * Connection to the database
     * 
     * @var LSDatabase
     */
    protected $_db;
    
    /**
     * $var array
     */
    protected $result;
    
    /**
     * Name model
     *
     * @var string
     */
    protected $name;
    
    /**
     * Construct
     */
    public function __construct()
    {
        // An instance to the database
        $this->_db = LSPrincipal::getDBO();
    }
    
    /**
     * Method to obtain an instance of the database
     *
     * @return  LSDb
     */
    public function getDbo()
    {
        return $this->_db;
    }
    
    /**
     * Executing SQL queries
     *
     * @param string $sql about the query
     *
     * @return object or string msg error
     */
    public function consult($sql = '')
    {
        if (empty($sql)) {
            return false;
        }
        if ($this->result = $this->_db->query($sql)) {
            return $this->result;
        } else {
            LSError::msgLog($this->_db->errno, $this->_db->error, $_SERVER['PHP_SELF']);
            die(LSText::_('ERROR_EJECUTAR_SQL'));
        }
    }
    
    /**
     * Get the name of the model of the component in the category
     *
     * @return string
     */
    public function getName()
    {
        if (empty($this->name)) {
            $result = null;
            if (!preg_match('/Model(.*)/i', get_class($this), $result)) {
                echo 'error';
            }
            $this->name = strtolower($result[1]);
        }

        return $this->name;
    }
}
