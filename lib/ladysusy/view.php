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
 * This class allows me to manage the set and get objects among other things
 */
class LSView
{
    /**
     * An arrangement of models
     * 
     * @var array
     */ 
    protected $_models = array();
    
    /**
     * The default model
     * 
     * @var array
     */ 
    protected $_defaultModel = null;
    
    
    public function __construct()
    {
    }
    
    /**
     * It allows me to load the template in the view
     * 
     * @param string $tpl Template name to load
     * 
     * @return string With the content of the template
     */ 
    public function display($tpl = null)
    {
        $result_com = $this->loadTemplate($tpl);

        if ($result_com instanceof Exception) {
            return $result_com;
        }
        echo $result_com;
    }
    
    /**
     * Load the template in the requested view
     * 
     * @param string $tpl Name of file to load
     * 
     * @return Template content
     */ 
    public function loadTemplate($tpl = null)
    {
        // Getting the action to run in the view.
        $process = LSReqenvironment::getVar('process');
        
        // Creating the name of the template file to load    
        $arch = isset($tpl) ? $tpl : 'default';
        
        // Defining the base route of the view to be displayed
        $this->routeView = LS_COMPONENTE . '/view/' . $process.'/'.$arch.'.php';

            ob_start();
            require_once $this->routeView;
            $cont = ob_get_contents();
            ob_end_clean();
            
        return $cont;
    }
    
    /**
     * Setting the model to use in the view, for a certain category
     * 
     * @param array $model An instance by model reference
     * @param boolean $default A default model
     * 
     * @return objeto del model
     */ 
    public function setModel(&$model, $default = false)
    {
        $name = strtolower($model->getName());
    
        $this->_models[$name] = &$model;

        if ($default) {
            $this->_defaultModel = $name;
        }

        return $model;
    }

    /**
     * Get model
     * 
     * @param string $name Name of the model
     * 
     * @return array
     */ 
    public function getModel($name = null)
        {
            if ($name === null) {
                $name = $this->_defaultModel;
            }
        
            return $this->_models[strtolower($name)];
        }
        
    
    public function get($property, $default = null)
    {
        if (is_null($default)) {
            $model = $this->_defaultModel;
        } else {
            $model = strtolower($default);                 
        }
    
        // Verifying first that the model exists
        if (isset($this->_models[$model])) {
            // If the model exists, we obtain the method
            $method = 'get' . ucfirst($property);
            
            if (method_exists($this->_models[$model], $method)) {
                // Obtenemos el resultado
                $result = $this->_models[$model]->$method();
            }
        }

        return $result;
    }
}
