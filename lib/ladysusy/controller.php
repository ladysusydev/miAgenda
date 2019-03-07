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
 * Class of the main controller
 */ 
class LSController
{
    /**
     * URL for addressing
     *
     * @var string
     */
    protected $redireccion;
        
    /**
     * The id of the user entered
     *
     * @var int
     */
    protected $id;
    
    /**
     * An object of the component controller
     *
     * @var Object
     */
    protected static $controller;
        
    /**
     * Name of the view
     *
     * @var string
     */
    protected $component;
        
    /**
     * Route of the component
     *
     * @var string
     */
    protected $routeComp;
        
    /**
     * Tasks
     *
     * @var string
     */
    protected $tasks;

    /**
     * An arrangement of methods
     *
     * @var array
     */
    protected $tasksMet;
        
    /**
     * Arrangement of class methods
     *
     * @var array
     */
    protected $methods_fa;
        
        
    /**
     * The name of the model
     *
     * @var string
     */
    protected $name;
        
    /**
     * Get an instance of the component driver
     * 
     * @param string $component Name of the component
     * 
     * @return object of the controller, or boolean
     */ 
    public static function getInstance($component = null)
    {
        if (is_object(self::$controller)) {
            return self::$controller;
        }
        
        $route_comp = LS_COMPONENTE;
        $file = self::setNameFile('controller', array('name' => 'controller', 'format' => '.php'));
        $routeController = $route_comp . '/' . $file;

        // Getting the name of the controller class
        $class = ucfirst($component) . 'Controller';
                   
        // We include the file that contains our requested controller class
        if (!class_exists($class)) {
                        
            // We verify if the file to be included exists, and we include it
            if (file_exists($routeController)) {
                require_once $routeController;
                                
            } else {
                throw new InvalidArgumentException('The file to be included is NOT found');
            }
        }

        // Instance the class
        if (class_exists($class)) {
            $controller = new $class($config = array());
        } else {        
            throw new InvalidArgumentException('You can not instantiate the class, it does not exist');
            return false;
        }

        return $controller;
    }
        
        
    /**
     * Class constructor
     * 
     * @param array $config An array with component values
     * 
     * @return void
     */ 
    public function __construct($config = array())
    {
        $this->tasksMet = array();
        $methods_cp = get_class_methods('LSController');
        $methods_cs = get_class_methods($this);

        foreach ($methods_cs as $method) {        
            if (!in_array($method, $methods_cp) || $method == 'display') {
                $this->methods_fa[] = strtolower($method);
                $this->tasksMet[strtolower($method)] = $method;
            }
        }
                
        // Defining the base route of the components
        if (array_key_exists('route_com', $config)) {
            $this->routeComp = $config['route_com'];
        } else {
            $this->routeComp = LS_COMPONENTE;
        }
                
        // Registering the task by default
        if (array_key_exists('task_default', $config)) {
            $this->registerTaskDefault($config['task_default']);
        } else {
            $this->registerTaskDefault('display');
        }
    }
        
    /**
     * Get an instance of the component model for the context that is being applied
     * 
     * @param string $name Name of the component.
     * @param string $prefix The prefix to use with the name, to form the class
     * 
     * @return An instance of the component class
     */
    public function getModel($name, $prefix = '')
    {
        if (empty($name)) {
            $name = $this->getName();
        }

        if (empty($prefix)) {
            $prefix = $this->getName() . 'Model';
        }
        
        $modelName = preg_replace('/[^A-Z0-9_]/i', '', $name);
        $classPrefix = preg_replace('/[^A-Z0-9_]/i', '', $prefix);
        $modelClass = ucfirst($classPrefix) . ucfirst($modelName);
        
        if (!class_exists($modelClass)) {
            $name_file = self::setNameFile('model', 
                              array('name' => $modelName, 'format' => '.php'));
            $route = LS_COMPONENTE. '/model/' .$name_file;
                
            if ($route) {
                require_once $route;
            } else {       
                echo 'Error the file does not exist!!!!!!!!!!!!';
                return null;
            }
        }
                
        return new $modelClass();

    }
        
    /**
     * Present the component view
     * 
     * @return void
     */
    public function display()
    {
        // Defining the component element, for the view
        $name = $this->getAction();
        $view = $this->getView($name);
 
        // Obtain and establish the model
        if ($model = $this->getModel($name)) {
            
            // Place the model in the view
            $view->setModel($model, true);
        }   
                
        $view->display();
        
        return $this;
    }
    
    /**
     * Get an action to execute
     * 
     * @return string del componente
     */ 
    public function getAction()
    {
        $viewComponent = substr(strstr(LS_COMPONENTE, '_'), 1);

        if ($viewComponent == 'login') {
            $action = $this->getName();
        } else {
            $action = strtolower(LSReqenvironment::getVar('view'));
        }

        return $action;
    }
    
    /**
     * Registering a task by default
     * 
     * @param string $method The method to register
     * 
     * @return values
     */ 
    public function registerTaskDefault($method)
    {
        $this->registerTask('__default', $method);

        return $this;
    }

    /**
     * Register a task to a method in the class
     *
     * @param string $task The task
     * @param string $method The name of the method in the derived class to perform for this task
     *
     */
    public function registerTask($task, $method)
    {
        if (in_array(strtolower($method), $this->methods_fa)) {
            $this->tasksMet[strtolower($task)] = $method;
        }

        return $this;
    }
    
    /**
     * Get the name of the driver
     * 
     * @return Controller name
     */ 
    public function getName()
    {
        if (empty($this->name)) {
            $result = null;
            if (!preg_match('/(.*)Controller/i', get_class($this), $result)) {
                echo 'Error getting the name';
            }
            
            $this->name = strtolower($result[1]);
        }

        return $this->name;
    }
                
    /**
     * Get an instance of one of the views to use in the component
     *
     * @param string $name component
     * @param string $prefix prefix used
     *
     * @return object view
     */
    protected function getView($name = '', $prefix = '')
    {
        if (empty($name)) {
            $name = $this->getName();
        }

        if (empty($prefix)) {
            $prefix = $this->getName() . 'View';
        }
        
        LSReqenvironment::setVar('process', $name, 'get', false);
        
        // Limpiando el name las variables de la view
        $viewName = preg_replace('/[^A-Z0-9_]/i', '', $name);
        $classPrefix = preg_replace('/[^A-Z0-9_]/i', '', $prefix);
        $viewClass = ucfirst($classPrefix) . ucfirst($viewName);
                
        if (!class_exists($viewClass)) {
            $name_file = self::setNameFile('view', 
                              array('name' => $viewName, 'format' => '.php'));
            $route = LS_COMPONENTE. '/view/' .$name_file;

            if ($route) {
                require_once $route;
                                
                if (!class_exists($viewClass)) {
                    echo 'Error the class of the included file does not exist';
                    return null;
                }
            } else {
                return null;
            }
        }
        
        return new $viewClass;
    }
        
    /**
     * Create file
     *
     * @param string $type The type of what is required
     * @param array $parts file information
     *
     * @return string Name file
    */
    protected static function setNameFile($type, $parts = array())
    {
        $name = '';
        switch ($type) {
            case 'controller':
                $name = strtolower($parts['name']) . $parts['format'];
                break;

            case 'view':
                $name = strtolower($parts['name']) . '/view' . $parts['format'];
                break;
                        
            case 'model':
                $name = strtolower($parts['name']) . $parts['format'];
                break;
        }

        return $name;
    }
        
    /**
     * Run a component task
     *
     * @param string $task The task to be executed
     *
     * @return mixto The value returned by the called or false method
     */
    public function execute($task)
    {
        $this->task = $task;
        $task = strtolower($task);

        if (isset($this->tasksMet[$task])) {
            $task_do = $task;
        } elseif (isset($this->tasksMet['__default'])) {
            $task_do = $this->tasksMet['__default'];
        }

        $this->task_hacer = $task_do;
        $retval = $this->$task_do();
                
        return $retval;
    }    
}
