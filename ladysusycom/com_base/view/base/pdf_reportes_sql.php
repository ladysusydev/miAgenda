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
define('_LS');
 
 /*
  * Clase para obtener datos de los reportes
  */
class PDFReportes extends LSModel
{
    /**
     * Constructor
     * 
     * Recoge los datos seteados en el controlador principal, para ser usados en el componente
     */
    public function __construct()
    {
        // Llamando al constructor padre
        parent::__construct();
    }
    
    
    /**
     * Consultando datos de tareas
     * 
     * @param date $fecha Fecha en la que se ejecuto la tarea
     * @param int $id Id de la tarea
     *
     * @return Object en caso de exito y null por falla
     */
    public function reporteTareas($fecha, $id)
    {             

        // Consultando los datos
        $query = 'SELECT t.name AS nombre FROM ls_task AS t WHERE t.date = "'.$fecha.'" AND t.id_user="'.$id.'"';
        if (!$result = $this->_db->query($query)) { 
            return null;
        }
        return $result;
    }
    
    /**
     * Consultando datos del usuario
     * 
     * @param int $id Id del usuario
     *
     * @return Object en caso de exito y null por falla
     */
    public function getUsuario($id)
    {             
        // Consultando los datos
        $query = 'SELECT u.name AS nombre FROM ls_users AS u WHERE u.id="'.$id.'"';
        if (!$result = $this->_db->query($query)) { 
            return null;
        }
        return $result;
    } 
}
