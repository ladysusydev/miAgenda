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
 * Requests for data, to the database needed
 */
class LSRequest extends LSModel
{
    /**
      * Query data
      * 
      * @var array
      */
    public static $datos = null;
    
    /**
     * Result
     * 
     * @var array
     */ 
    public $resultData;
    
    /**
     * It allows me to traverse an array completely from an SQL query 
     * 
     * @param array $sql The SQL query
     * 
     * @return array associative
     */
    public static function loadAssoc($sql)
    {
        self::$data = array();
        while ($result = $sql->fetch_assoc()) {
            array_push(self::$data, $result);
        }
       
        $sql->free();
        return self::$data;    
    }
    
    /**
     * Allows you to traverse an array completely from an SQL query
     * 
     * @param array $sql The SQL query
     * 
     * @return array object
     */
    public static function loadObject($sql)
    {
        self::$data = array();
        while ($result = $sql->fetch_object()) {
            array_push(self::$data, $result);
        }

        $sql->free();
        
        return self::$data;
    }
    
    /**
     * Function that allows to validate the data, in order to avoid SQL injection
     * 
     * @param $valor a string, which will be the data to be evaluated
     * 
     * @return string formatted
     */
    public function checkData($value)
    {
        // Verifying if and removing backslashes
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        
        // Verifying if the variable is not a number
        if (!is_numeric($value)) {
        
            $value = "'" . $this->_db->real_escape_string($value) . "'";
        }
        
        return $value;
    }
    
    /**
     * It allows to create the necessary consultation for the update of the data
     * 
     * @param string $table Name of the table to be updated
     * @param array $data Data that will be updated
     * @param string $id The cambo that identifies the id
     * @param int $value_id The value of the id
     * 
     * @return string with sql query
     */
    public function mysql_update_array($table, $data, $id, $value_id) 
    {
        foreach ($data as $row => $value) 
        {
            $rows[] = sprintf("%s = %s", $row, $this->check_data($value));
        }
        
        if (is_string($value_id)) {
            $value_id = $this->check_data($value_id);
        }
        
        $list_rows = implode(',', $rows);    
        $query = sprintf("UPDATE %s SET %s WHERE %s = %s", $table, $list_rows, $id, $value_id);
            
        return $query;
    }
    
    /**
     * Allows you to create the necessary query for data deletion
     * 
     * @param string $table Name of the table to be updated
     * @param string $id The field that identifies the id
     * @param int $value_id The value id
     * 
     * @return string with sql query
     */
    public function mysql_eliminar_array($table, $id, $value_id) 
    {    
        $query = sprintf("DELETE FROM %s WHERE %s = %s", $table, $id, intval($value_id));
            
        return $query;
    }
}
