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
$sesion = LSPrincipal::getSession();

if ($sesion->get('noAuth')) {
     $authAlert = $sesion->get('noAuth');
     $sesion->set('noAuth', null);
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-sm-6 text-right">
            <div>
                <div><img src="imagenes/miAgenda1.png"  class="img-fluid" alt="Mi Agenda"/></div><br>
           </div>
        </div>
        
        <div class="col-md-2 col-sm-6">
            <div>
                <form id="login-form" accept-charset="UTF-8" action="index.php" method="post" style="display: block;">
                <p class="titulo-login"><?php echo LSText::_('INGRESAR'); ?></p>
                <input type="text" id="email" class="form-control form-control-sm" name="login" placeholder="<?php LSText::_('USUARIO'); ?>" />
                </br>
                <input type="password" name="password" id="userPassword" class="form-control form-control-sm" placeholder="Password" />
                </br>
                
                <div class="wrapper">
                    <input type="hidden" name="tarea" value="login"/>
                    <input name="commit" class="btn btn-sm btn-primary btn-block" tabindex="3" type="submit" value="<?php echo LSText::_('LOG_IN'); ?>" />
                    <br>
                    <?php if ($authAlert) { ?>
                        <div class="bg-danger text-white"><?php echo $authAlert; ?></div>
                    <?php } ?>
                    <p id="register-form-link" class="text-warning follow"><?php echo LSText::_('NUEVO').' '.LSText::_('USUARIO') ; ?></p>
                </div> 
                </form>
            </div>
            <div>
                <form  id="register-form" accept-charset="UTF-8" action="index.php?ladysusycom=com_login&tarea=register" method="post" style="display: none;">
                <p class="titulo-login"><?php echo LSText::_('NUEVO').' '.LSText::_('USUARIO') ; ?></p>
                <input type="text" id="login" class="form-control form-control-sm" name="login" placeholder="<?php echo LSText::_('USUARIO'); ?>" />
                </br>
                <input type="password" name="password" id="userPassword" class="form-control form-control-sm" placeholder="Password" />
                </br>
                <div class="wrapper">
                    <input type="hidden" name="tarea" value="register"/>
                    <input name="commit" class="btn btn-sm btn-success btn-block" tabindex="3" type="submit" value="<?php echo LSText::_('REGISTRAR'); ?>" />
                    <br>
                    <p id="login-form-link" class="text-warning follow"><?php echo LSText::_('INGRESAR'); ?></p>
                </div> 
                </form> 
            </div>
        </div>
    </div>
</div>
