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
 * Clase controladora del componente
 */
class LoginController extends LSController
{
    /**
     * Metodo para el inicio de sesión del usuario
     *
     * @return void
     */
    public function login()
    {
        $app = LSPrincipal::getApplication();
        $modelo = $this->getModel('login');
        $credenciales = $modelo->login();
        $sesion = LSPrincipal::getSession();

        $authUser= $app->login($credenciales);
        
        if (!($authUser instanceof Exception)) {
            $sesion->set('noAuth',LSText::_('NOAUTH'));
            $app->__r('index.php');
        }

        parent::display();
    }
    
    /**
     * Me permite definir la vista, cuando el usuario no
     * se ha autenticado
     * 
     * @return vista del controlador
     */
    public function display()
    {
        LSReqenvironment::setVar('tarea', 'login');
        parent::display();
    }
    
    /**
     * El usuario sale de la sesion iniciada
     * 
     * @return void
     */ 
     public function logout()
     {
         $app = LSPrincipal::getApplication();
         $authUser = $app->logout();
        
         if (!($authUser instanceof Exception)) {
            $app->__r('index.php');
         }
         
        parent::display();
     }
     
      /**
     * El usuario sale de la sesion iniciada
     * 
     * @return void
     */ 
     public function register()
     {
        $app = LSPrincipal::getApplication();
        $modelo = $this->getModel('login');
        
        // Registrar el usuario
        $modelo->register();
        
        $credenciales = $modelo->login();
        $sesion = LSPrincipal::getSession();

        $authUser= $app->login($credenciales);
        
        if (!($authUser instanceof Exception)) {
            $sesion->set('noAuth',LSText::_('NOAUTH'));
            $app->__r('index.php');
        }
         
        parent::display();
     }
}
