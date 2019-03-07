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
 * Base class of the application
 */
final class LSApplication
{
    /**
     * Application name
     *
     * @var array
     */
     protected $_name = null;

    /**
     * Body content
     *
     * @var string
     */
    public $body = null;

     /**
      * Instance to class LSSession
      * 
      * @var LSSession
      */
    public $session = null;

    /**
     * Session options
     *
     * @var array
     */
    protected $_optionsSession = array();

    /**
     * Construct
     *
     * @param boolean $app Application name
     * @param array $config Session values configuration
     * 
     * @return void
     */
    public function __construct($app = null, $config = array())
    {
        // Define the name of the application
        $this->_name = $app;

        // Set the name of the session
        if (!isset($config['session_name'])) {
            $config['session_name'] = $this->_name;
        }
        
        // Enable default sessions
        if (!isset($config['session'])) {
            $config['session'] = true;
        }

        // Create the session if we pass the name
        if ($config['session'] !== false) {
            $this->setSession(self::getHash($config['session_name']));
        }
        
        $this->_optionsSession = $config;
    }
    
    /**
     * Initialize some values
     */
    public function initialize()
    {
        $error = LSPrincipal::getError();
        
        // set the error handler defined by the user
        set_error_handler(array($error, 'handlerError'));
    }
    
    /**
     * Allows you to route the application
     *
     * @return void
     */
    public function router()
    {
        // Initializing variables
        LSReqenvironment::initialize();
    }
    
    /**
     * Dispatching the application
     * 
     * @param boolean $ladysusycom Component that will be dispatched
     * 
     * @return void
     */
    public function render($ladysusycom = null)
    {
        // Obtaining the component
        if (!$ladysusycom) {
            $ladysusycom = LSComponent::getComponent();
        }
        $contComp = LSContent::render($ladysusycom);
        
        // Rendering the content
        $content = $this->dispatcher($contComp);

        LSAnswer::setBody($content);
    }
    
    /**
     * Dispatching the application, in its respective template
     * 
     * @param string $ content The content to be shipped
     *
     * @return string Content to show
     */
    public function dispatcher($content)
    {
        // Obtaining the template
        $config = new LSconfig();
        $template = $config->template;

        if ($template) {
            ob_start();
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                // There is an AJAX request the header and footer will not be included
                echo $content;
            } else {
                require_once LS_TEMPLATE . SD . $template . '/header.php';
                echo $content;
                require_once LS_TEMPLATE . SD . $template .'/footer.php';
            }
            $cont = ob_get_contents();
            ob_get_clean();
        } else {
            throw new Exception('Error loading the template');
        }

        return $cont;
    }
    
    /**
     * Leaving the application
     *
     * @param int $code Exit code
     *
     * @return void
     */
    public function close($code = 0)
    {
        exit($code);
    }
    
    /**
     * Un seguro HASH
     *
     * @param string $seed Seed string
     *
     * @return string A secure hash
     */
    public static function getHash($seed)
    {
        $conf = LSPrincipal::getConfig();
        return md5($conf->hashKey . $seed);
    }
    
    /**
     * Create the user session
     *
     * The objective is to eliminate old sessions established in the life cookie
     * If a new session is generated it should be saved in the session table
     *
     * @param string $name The name of the session
     *
     * @return LSSession You can call exit or error in the database
     */
    protected function setSession($name)
    {
        $options['name'] = $name;
        $this->session = LSPrincipal::getSession($options);
        
        return $this->session;
    }
    
    /**
     * User login
     * 
     * @param array $priv
     * 
     * @return boolean True o False.
     */
    public function login($priv)
    {
        $user = LSPrincipal::getUser();
        $answer = $user->autentication($priv);
        if ($answer->state === LSUser::ESTADO_EXITO) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get out of the system
     * 
     * @return boolean
     */
    public function logout()
    {
       $user = LSPrincipal::getUser();
       $logout = $user->userLogout();
       
       // Verifying if the user left completely
        if (($logout === true)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Function that will allow redirecting to another URL
     *
     * @param  string  $url
     * @param  string  $replace
     *
     * @return void  Call to close().
     */
    public function __r($url, $replace = null)
    {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: '. $url, $replace);
        
        $this->close();
    }
    
    /**
     * An instance of LSSession
     *
     * @return LSSession()
     */
    public function getSession()
    {
        $session = $this->setSession(self::getHash($this->_optionsSession['session_name']));
        return $session;
    }
    
    /**
     * Method that allows to obtain the contentdo
     * 
     * @return string
     */ 
     public function __toString()
     {
         return LSAnswer::toString();
     }
}
