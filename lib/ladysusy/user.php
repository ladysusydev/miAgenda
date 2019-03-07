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
 * LS Class de Users
 */
class LSUser
{
    /**
     * This is the return code when authentication is a success
     *
     * @const ESTADO_EXITO
     */
    const ESTADO_EXITO = 1;

    /**
     * This is the return code when the authentication is canceled
     *
     * @const ESTADO_CANCELAR
     */
    const ESTADO_CANCELAR = 2;
    
    /**
     * This is the return code when the authentication fails
     *
     * @const ESTADO_FALLIDO
     */
    const ESTADO_FALLIDO = 3;
    
    /**
     * This is the initial request code
     *
     * @const ESTADO_REQUES
     */
    const ESTADO_REQUES = 4;
    
    /**
     * An instance to the class
     * 
     * @var LSAutentication
     */
    protected static $instance;

    /**
     * It allows to verify if the password will be encrypted
     * 
     * @var boolean
     */
    private $encrypt = true;

    /**
     * Root rights, within the system
     *
     * @var boolean
     */
    protected $isRoot = null;
    
    /**
     * Session user
     * 
     * @var LSSession
     */
    public $session = null;
    
    /**
     * ID user
     *
     * @var int
     */
    public $id = null;
    
    /**
     * ID nivel
     *
     * @var int
     */
    public $idNivel = null;
    
    /**
     * An instance to the Main class
     * 
     * @var LSprincipal
     */
    public $app = null;
    
    /**
     * Constructor
     *
     * @param string Name of the application in progress
     */
    public function __construct()
    {
        // Creating instances
        $this->app = LSPrincipal::getApplication();
        $this->session = $this->app->getSession();
    }
    
    /**
     * Returns an object of the LSUser class, if it does not exist
     *
     * @return LSUser The object of the class
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            $user = new LSUser();
            self::$instance = $user;
        }
        
        return self::$instance;
    }

    /**
     * Verify if the user is authenticated
     *
     * @param bool $state Verify if the user is authenticated or not
     *
     * @return boolean
     */
    public function isAuthenticated($state = null)
    {
        return $this->session->get('UserAuthenticated') === true;
    }
    
    /**
     * It allows to collect the autentication
     *
     * @param array $priv An array with the user's start data
     *
     * @return boolean True in case of success
     */
    public function autentication($priv)
    {
        // Authentication answers
        $answer = new LSAutenticationAnswer;
        $this->userLogin($priv, $answer);

        return $answer;
    }
    
    /**
     * Log in with email, password and save the data in the session
     *
     * @param array $priv array with user data
     * @param object $answer Authentication response object
     *
     * @return void o boolean
     */
    public function userLogin($priv, &$answer)
    {
        // Import class HASH
        ladysusyimport('ladysusy.hash');

        // Verifying if the password is blank
        if (empty($priv['password'])) {
            $respuesta->estado = self::ESTADO_FALLIDO;
            return;
        }

        // Obteniendo consulta a la base de datos.
        $result = $this->consultUserBD($priv);
       
        
        if ($result) {
            while ($userLogin = $result->fetch_assoc()) {
                if ($userLogin['Password']) {
                    if (LSHash::compPassword($userLogin['Password'], $priv['password'])) {
                       
                        // Incorrect Login
                        $answer->state = self::ESTADO_EXITO;
                        $this->session->set('UserAuthenticated', true);
                            
                    } else {
                        $answer->state = self::ESTADO_FALLIDO;
                        return;
                    }    
                }
                // Defining the id of the user in a session variable
                $this->session->set('id', $userLogin['id']);  
            }
        } else {
            $answer->state = self::ESTADO_FALLIDO;
        }
    }
    
    /**
     * Close the session user
     *
     * @return boolean True in case of success
     */
    public function userLogout()
    {
        // Deleting the user session
        $this->session->destroySession();
        
        return true;
    }
    
    /**
     * Obtaining data from the database
     *
     * @param array $priv Array containing the user's data
     *
     * @return array o boolean
     */
    public function consultUserBD($priv)
    {

        try {
            // Validating data
            $userIdent = $priv['login'];
            
            // Verifying if an email was entered as a user
            if ($this->is_email($userIdent)) {
                
                $ident = "u.email = '$userIdent'";
            } else {
                
                $ident = "u.user = '$userIdent'";
            }
            
            $_db = LSPrincipal::getDBO();
            $query = $_db->getQuery();
            $query->select("u.id AS id, u.password AS Password");
            $query->from("&&__users AS u");
            $query->where($ident);
            
            $resultSQL = $_db->setQuery($query);
            $totalRow= $resultSQL->num_rows;
                
            if ($totalRow == 0) {
                return false;
            } else {
                return $resultSQL;
            }
        } catch(Exception $e) {
            echo $e->getMesssage();
        }
    }
    
    /**
     * Validating a user, to verify if it is email
     * 
     * @param string $user User entered or email
     * 
     * @return boolean true o false
     */ 
    public function is_email($user) 
    {
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generating a HASH
     * 
     * return string
     */ 
    public function generateHash($password)
    {
        $hash = LSHash::hash($password);
        
        return $hash;
    }
}

/**
 * Response class for authentication
 */
class LSAutenticationAnswer
{
    /**
     * Response status
     *
     * @var string
     */
    public $state = LSUser::ESTADO_FALLIDO;
}
