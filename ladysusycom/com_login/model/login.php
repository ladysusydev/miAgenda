<?php
/**
 * miAgenda
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
 * @package		miAgenda
 * @author		LadySusy Dev
 * @copyright	Copyright (c) 2019, LadySusy (http://www.ladysusy.org/)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

// Evitar acceso directo
defined('_LS') or die;

/**
 * Clase para el modelo del componente
 */ 
class LoginModelLogin extends LSModel
{
	
	/**
     * @var LSRequest
     */
    public $solicitudes = null;
   
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Creando una instancia a la clase solicitudes
        $this->solicitudes = new LSRequest();
        
        // Llamando al constructor padre
        parent::__construct();
    }
    
    
    /**
     * Metodo para el inicio de sesión del usuario
     *
     * @return void
     */
    public function login()
    {
        // Obteniendo datos del usuario
        $login = LSReqenvironment::getVar('login', null, 'post');
        $password = LSReqenvironment::getVar('password', null, 'post');
    
        $priv = array();
        $priv['login'] = $login;
        $priv['password'] = $password;
        
        return $priv;
    }
    
    /**
     * Registrar usuario
     * 
     * @return
     */
     public function register() 
     {
		$login = LSReqenvironment::getVar('login', null, 'post');
        $password = LSHash::hash(LSReqenvironment::getVar('password', null, 'post'));
        $log = $this->solicitudes->checkData($login);
        $passw= $this->solicitudes->checkData($password);
		$query = 'INSERT INTO ls_users (user, password, name) VALUES ('.$log.', '.$passw.', "Tes tuser")';
		$this->_db->query($query);
		
		$priv = array();
        $priv['login'] = $login;
        $priv['password'] = $password;
		 
		 return $priv;
	 } 
}


























