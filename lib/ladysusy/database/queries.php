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
 * Class for queries to the database
 */ 
class LSQueryElement
{
    /**
     *  @var string The name element
     */
    protected $name = null;

    /**
     * @var array An array of elements
     */
    protected $elements = null;

    /**
     * @var string Separator of the elements
     */
    protected $separator = null;
    
    /**
     * Class constructor
     * 
     * @param string $name What is the name of the element
     * @param mixto $elements What is a string type array
     * @param string $separator List separator
     * 
     * @return void
     */
    public function __construct($name, $elements, $separator = ',')
    {
        $this->elements = array();
        $this->name = $name;
        $this->separator = $separator;
        $this->add($elements);
    }
    
    /**
     * Magic function to convert the query in String
     *
     * @return  string
     */
    public function __toString()
    {
        if (substr($this->name, -2) == '()') {
            return PHP_EOL . substr($this->name, 0, -2) . '(' . implode($this->separator, $this->elements) . ')';
        } else {
            return PHP_EOL . $this->name . ' ' . implode($this->separator, $this->elements);
        }
    }
    
    /**
     * Include elements that will be part of the list.
     *
     * @param mixtos $elements, a string or array.
     *
     * @return void
     */
    public function add($elements)
    {
        if (is_array($elements)) {
           $this->elements = array_merge($this->elements, $elements);
        } else {
           $this->elements = array_merge($this->elements, array($elements));
        }
    }    
    
    /**
     * Get the elements of this element
     *
     * @return  string
     */
    public function getElements()
    {
        return $this->elements;
    }
}

/**
 * Class to build queries :-)
 *
 */
class LSDatabaseQueries
{
    /**
     * @var A string value, representing the type of query
     */
    protected $type = '';

    /**
     * @var The select element of the class LSQueryElement
     */
    protected $select = null;

    /**
     * @var The element from from the class LSQueryElement
     */
    protected $from = null;

    /**
     * @var List of columns at the time of insertion, a class elements LSQueryElement
     */
    protected $columns = null;

    /**
     * @var The WHERE element of the LSQueryElement class
     */
    protected $where = null;

    /**
     * @var The JOIN element of the class LSQueryElement
     */
    protected $join = null;

    /**
     * @var The order element of the class LSQueryElement
     */
    protected $order = null;

    /**
     * @var The group element of the class LSQueryElement
     */
    protected $group = null;

    /**
     * @var The union element of the class LSQueryElement
     */
    protected $union = null;
    
    /**
     * @var The auto increment of the INSERT elementto
     */
    protected $autoIncrementRow = null;
    
    /**
     * @var LSQueriesElement The list of values for an INSERT statement
     */
    protected $values = null;
    
    /**
     * @var LSQueriesElement The list of values for an INSERT statement
     */
    protected $having = null;
    
    /**
     * Magic function to convert the query
     *
     * @return string Complete query
     */
    public function __toString()
    {
        $query = '';
        switch ($this->type) {
            case 'select':
                $query .= (string) $this->select;
                $query .= (string) $this->from;
                
                if ($this->join) {
                   // Special case for JOIN
                   foreach ($this->join as $join) {
                       $query .= (string) $join;
                   }
                }
                if ($this->where) {
                   $query .= (string) $this->where;
                }
                
                if ($this->order) {
                   $query .= (string) $this->order;
                }
                
                if ($this->group) {
                   $query .= (string) $this->group;
                }
                
                if ($this->having) {
                    $query .= (string) $this->heaving;
                }
                
                if ($this->union) {
                    $query .= (string) $this->union;
                }
                break;
                
            case 'delete':
                $query .= (string) $this->delete;
                $query .= (string) $this->from;

                if ($this->join) {
                    
                    // Special case for JOIN
                    foreach ($this->join as $join) {
                        $query .= (string) $join;
                    }
                }

                if ($this->where) {
                    $query .= (string) $this->where;
                }

                if ($this->order) {
                    $query .= (string) $this->order;
                }
                break;
                
            case 'update':
                $query .= (string) $this->update;

                if ($this->join) {
                    
                    // Special case for JOIN
                    foreach ($this->join as $join) {
                        $query .= (string) $join;
                    }
                }

                $query .= (string) $this->set;

                if ($this->where) {
                    $query .= (string) $this->where;
                }

                if ($this->order) {
                    $query .= (string) $this->order;
                }
                break;
                
            case 'insert':
                $query .= (string) $this->insert;

                if ($this->set) {
                    $query .= (string) $this->set;
                } elseif ($this->values) {
                    if ($this->columns) {
                        $query .= (string) $this->columns;
                    }

                    $elements = $this->values->getElements();

                    if (!($elements[0] instanceof $this)) {
                        $query .= ' VALUES ';
                    }

                    $query .= (string) $this->values;
                }
                break;
        }
        
       return $query;
    }
    
    /**
     * Add the FROM clause of the query to the table
     * 
     * @param mixed $tables that is a string or array of table names
     *
     * @return Object of the class LSDatabaseQueries
     */
    public function from($tables)
    {    
        if (is_null($this->from)) {
            $this->from = new LSQueryElement('FROM', $tables);
        } else {
            $this->from->add($tables);
        }
        
        return $this;
     }
    
    /**
     * Add a simple condition or an array of conditions with the WHERE clause to the query
     * 
     * @param string $terms Conditions to be evaluated
     * @param string $separator Condition separator
     * 
     * @return string with query
     */
    public function where($terms, $separator = 'AND')
    {
        if (is_null($this->where)) {
            $separator = strtoupper($separator);
            $this->where = new LSQueryElement('WHERE', $terms, " $separator ");
        } else {
            $this->where->add($terms);
        }
        
        return $this;
     }
    
    /**
     * Add a simple column, or array of columns, to the SELECT query
     *
     * @param mixed $columns a string or array of elements names
     *
     * @return Object of the class LSDatabaseQueries
     */
    public function select($columns)
    {
        $this->type = 'select';
        if (is_null($this->select)) {
            $this->select = new LSQueryElement('SELECT', $columns);
        } else {
           $this->select->add($columns);
        }
        
        return $this;
    }
    
    /**
     * Adding a JOIN clause to the query
     *
     * Example of use:
     * $query->join('INNER', 'b ON b.id = a.id);
     *
     * @param string  $type The JOIN type
     * @param mixed  $condition A string or array of terms
     *
     * @return Object of the LSDatabaseQueries class
     */
    public function join($type, $condition)
    {
        if (is_null($this->join)) {
            $this->join = array();
        }
        $this->join[] = new LSQueryElement(strtoupper($type) . ' JOIN', $condition);
    
        return $this;
    }
    
    /**
     * Adding the ORDER option to the query
     *
     * Usar:
     * $query->order('foo')->order('bar');
     * $query->order(array('foo','bar'));
     *
     * @param mixed $columns A string or array columns
     *
     * @return Object of the LSDatabaseQueries class
     */
    public function order($columns)
    {
        if (is_null($this->order)) {
            $this->order = new LSQueryElement('ORDER BY', $columns);
        } else {
            $this->order->add($columns);
        }

        return $this;
    }
    
    /**
     * Adding the GROUP option to the query
     *
     * Use:
     * $query->group('foo');
     * @param  mixed $columns A string or array columns
     *
     * @return Object of the LSDatabaseQueries class
     */
    public function group($columns)
    {
        if (is_null($this->group)) {
            $this->group = new LSQueryElement('GROUP BY', $columns);
        } else {
            $this->group->add($columns);
        }
 
        return $this;
    }
    
    /**
     * Adding to a union to the query
     *
     * @param mixed    $query
     * @param boolean  $distinct
     * @param string   $separator
     *
     * @return mixed
     */
    public function union($query, $distinct = false, $separator = '')
    {
        if ($distinct) {
            $name = 'UNION DISTINCT ()';
            $separator = ')' . PHP_EOL . 'UNION DISTINCT (';
        } else {
            $separator = ')' . PHP_EOL . 'UNION (';
            $name = 'UNION ()';
        }
        
        if (is_null($this->union)) {
                $this->union = new LSQueryElement($name, $query, "$separator");
        } else {
            $separator = '';
            $this->union->add($query);
        }

        return $this;
    }
    
    
    /**
     * Clearing sentence
     * 
     * @param string Sclause The type of statement to execute
     * 
     * @return Object of the LSDatabaseQueries class
     */ 
    public function clear($clause = null)
    {
        switch ($clause)
        {
            case 'select':
                $this->select = null;
                $this->type = null;
                break;

            case 'delete':
                $this->delete = null;
                $this->type = null;
                break;

            case 'update':
                $this->update = null;
                $this->type = null;
                break;

            case 'insert':
                $this->insert = null;
                $this->type = null;
                $this->autoIncrementRow = null;
                break;

            case 'from':
                $this->from = null;
                break;

            case 'join':
                $this->join = null;
                break;

            case 'set':
                $this->set = null;
                break;

            case 'where':
                $this->where = null;
                break;

            case 'group':
                $this->group = null;
                break;

            case 'having':
                $this->having = null;
                break;

            case 'order':
                $this->order = null;
                break;

            case 'columns':
                $this->columns = null;
                break;

            case 'values':
                $this->values = null;
                break;

            case 'union':
                $this->union = null;
                break;

            default:
                $this->type = null;
                $this->select = null;
                $this->delete = null;
                $this->update = null;
                $this->insert = null;
                $this->from = null;
                $this->join = null;
                $this->set = null;
                $this->where = null;
                $this->group = null;
                $this->having = null;
                $this->order = null;
                $this->columns = null;
                $this->values = null;
                $this->autoIncrementRow = null;
                $this->union = null;
                break;
        }

        return $this;
    }
    
    /**
     * Add a table name to the DELETE identifier of the query
     *
     * Use:
     * $query->delete('table')->where('id = 1');
     *
     * @param string $table The name of the table where it will be deleted
     *
     * @return Object of the LSDatabaseQueries class
     */
    public function delete($table = null)
    {
        $this->type = 'delete';
        $this->delete = new LSQueryElement('DELETE', null);

        if (!empty($table)) {
            $this->from($table);
        }

        return $this;
    }
    
    /**
     * Used to obtain the year of a date
     * 
     * @param string $date Date to use
     * 
     * @return string Returns string with the year of the date
     */
    public function year($date)
    {
        return 'YEAR(' . $date . ')';
    }

    /**
     * Used to obtain the month of a date
     *
     * @param string $date Date to use
     * 
     * @return string Returns string with the month of the date
     */
    public function month($date)
    {
        return 'MONTH(' . $date . ')';
    }

    /**
     * Used to obtain the day of a date
     *
     * @param string $date Date to use
     * 
     * @return string Returns string with date day
     */
    public function day($date)
    {
        return 'DAY(' . $date . ')';
    }

    /**
     * Used to obtain the time of a date
     *
     * @param string $date Date to use
     *
     * @return string Returns string with the time of the date
     */
    public function time($date)
    {
        return 'HOUR('. $date. ')';
    }

    /**
     * Used to obtain the minute of a date
     *
     * @param string $date Date to use
     *
     * @return string Returns string with the minute of the date
     */
    public function minute($date)
    {
        return 'MINUTE('. $date. ')';
    }

    /**
     * Used to obtain the second of a date
     *
     * @param string $date Date to use
     *
     * @return string Returns string with the second of the date
     */
    public function second($date)
    {
        return 'SECOND('. $date. ')';
    }
    
    
    /**
     * A condition using the HAVING statement
     * 
     * Use:
     * $query->group('id')->having('COUNT(id) > 5');
     * 
     * @param mixed $condition a string or array of columns
     * @param string$separator El separator par la union de las terms
     * 
     * @return string Returns the condition performed
     */
    public function having($condition, $separator = 'AND')
    {
        if (is_null($this->having)) {
            $separator = strtoupper($separator);
            $this->having = new LSQueryElement('HAVING', $condition, " $separator ");
        } else {
            $this->having->add($condition);
        }

        return $this;
    }
    
    /**
     * Add a table name to the INSERT statement of the query
     * 
     * Uso:
     * $query->insert('table')->set('id = 1');
     * $query->insert('table')->columns('id, title')->values('1,2')->values('3,4');
     * $query->insert('table')->columns('id, title')->values(array('1,2', '3,4'));
     *
     * @param mixed $table The name of the table
     * @param boolean $incrementRow The name of the rows to increase
     *
     * @return Object of the LSDatabaseQueries class
     */
    public function insert($table, $incrementRow = false)
    {
        $this->type = 'insert';
        $this->insert = new LSQueryElement('INSERT INTO', $table);
        $this->autoIncrementRow = $incrementRow;

        return $this;
    }
    
    /**
     * Adding a string or array to the condition
     *
     * Use:
     * $query->set('a = 1')->set('b = 2');
     * $query->set(array('a = 1', 'b = 2');
     *
     * @param mixed $condition A string or array of terms
     * @param string $separator The separator of the condition of the string
     *
     * @return  Object of the LSDatabaseQueries class
     */
    public function set($condition, $separator = ',')
    {
        if (is_null($this->set)) {
            $separator = strtoupper($separator);
            $this->set = new LSQueryElement('SET', $condition, "\n\t$separator ");
        } else {
            $this->set->add($condition);
        }

        return $this;
    }
    
    /**
     * Add a name of table to the query
     *
     * Use:
     * $query->update('table')->set(...);
     *
     * @param string $table The table to update
     *
     * @return Object of the LSDatabaseQueries class
     */
    public function update($table)
    {
        $this->type = 'update';
        $this->update = new LSQueryElement('UPDATE', $table);

        return $this;
    }

    /**
     * Add values to use for an INSERT INTO statement
     *
     * Use:
     * $query->values('1,2,3')->values('4,5,6');
     * $query->values(array('1,2,3', '4,5,6'));
     *
     * @param string $values A list of values or array of values
     *
     * @return Object of the LSDatabaseQueries class
     */
    public function values($values)
    {
        if (is_null($this->values)) {
            $this->values = new LSQueryElement('()', $values, '),(');
        } else {
            $this->values->add($values);
        }

        return $this;
    }
    
    /**
     * Add a column name or an array of column names to be used with INSERT INTO
     *
     * @param mixed $columns A column name, or an array of column names
     *
     * @return Object of the LSDatabaseQueries class
     */
    public function columns($columns)
    {
        if (is_null($this->columns)) {
            $this->columns = new LSQueryElement('()', $columns);
        } else {
            $this->columns->add($columns);
        }

        return $this;
    }
}
