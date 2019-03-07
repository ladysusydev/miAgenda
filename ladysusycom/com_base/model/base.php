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

/**
 * Clase para el modelo del componente
 */  
class BaseModelBase extends LSModel
{
    /**
     * @var LSRequest
     */
    public $solicitudes = null;
    
    public $idUser = null;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Creando una instancia a la clase solicitudes
        $this->solicitudes = new LSRequest();
        $sesion = LSPrincipal::getSession();
        $this->idUser = $sesion->get('id');
        
        // Llamando al constructor padre
        parent::__construct();
    }
    
    /**
     * Registrando una tarea
     *
     * @param string $tabla El nombre de la tabla
     * @param string $nombre Los datos quee seran procesados en el registro
     * @param string $fecha Fecha a registrar
     *
     * @return boolean true o false
     */
    public function registroTarea($tabla, $nombre, $fecha)
    {    
        $nomb = $this->solicitudes->checkData($nombre);
        $fechaTemp = $fecha['ano'].'-'.$fecha['mesInt'].'-'.$fecha['diaInt'];
        $fecha = $this->solicitudes->checkData($fechaTemp);

        // Creando query para registrar datos.
        $query = "INSERT INTO $tabla (name, date, state, id_user) VALUES ($nomb, $fecha, 'C', $this->idUser)";
        
        if (!$this->_db->query($query)) {
            return false;
        }    
        
        // Obteniendo el id de la tarea registrada
        $id = $this->_db->insert_id;
        echo '<li id="item_'.$id.'">';
        echo '<div class="del_wrapper"><a href="#" class="del_button" id="del-'.$id.'">';
        echo '<img src="imagenes/del.png" border="0" />';
        echo '</a></div>';
        // Obteniendo estado de la tarea
        /*
        $estado = $this->getEstado($id);
        if ($estado == 'T') {
            echo '<a href="#" id="C-'.$id.'" class="mahref"><strike>'.$nombre.'</strike></a>';
        } else {
            echo '<a href="#" id="T-'.$id.'" class="mahref">'.$nombre.'</a>';
        } */
        echo $nombre.'</li>';
        echo '</li>';

        return true;
    }
    
    /**
     * Estado de la tarea
     * 
     * @param int $id id de la tarea
     *
     * @return string
     */
    public function getEstado($id)
    {            
        // Consultando los datos primer div
        $query = 'SELECT t.state AS estado '.
                  'FROM ls_task AS t WHERE t.id = "'.$id.'" AND t.id_user="'.$this->idUser.'"';
    
        if ($resultSQL = $this->_db->query($query)) {
            $datos = $resultSQL->fetch_assoc();
            // liberar el resultado 
            $resultSQL->free();
            return $datos['estado'];
        }
    }
    
    /**
     * Consultando datos
     * 
     * @param string $tabla    El nombre de la tabla
     * @param string $fecha1 Fecha para el primer div
     * @param string $fecha2 Fecha para el segundo div
     *
     * @return mixto
     */
    public function getTarea($tabla, $fecha1, $fecha2)
    {            
        // Fecha de tareas
        $fechades1 = $fecha1['ano'].'-'.$fecha1['mesInt'].'-'.$fecha1['diaInt'];
        $fechades2 = $fecha2['ano'].'-'.$fecha2['mesInt'].'-'.$fecha2['diaInt'];

        // Consultando los datos primer div
        $query1 = 'SELECT t.id AS id, t.name AS nombre, t.state AS estado '.
                  'FROM '.$tabla.' AS t WHERE t.date = "'.$fechades1.'" AND t.id_user="'.$this->idUser.'"';
                  
        // Consultando los datos segundo div
        $query2 = 'SELECT t.id AS id, t.name AS nombre, t.state AS estado '.
                  'FROM '.$tabla.' AS t WHERE t.date = "'.$fechades2.'" AND t.id_user="'.$this->idUser.'"';
        
        if (!$result1 = $this->_db->query($query1)) { 
            return false;
        }     
        
        if (!$result2 = $this->_db->query($query2)) { 
            return false;
        }     
          
        $result['dia1'] = $result1;
        $result['dia2'] = $result2;
        
        return $result;
    }
    
    /**
     * Eliminando una tarea
     *
     * @param string $tabla    El nombre de la tabla
     * @param string $id Id de registro a eliminar
     * 
     * @return    boolean true en caso de exito, o false por falla.
     */
    public function eliminarTarea($tabla, $id)
    {            
        // Consultando los datos
        $query = "DELETE FROM ".$tabla." WHERE id=".$id."";
        
        if (!$result = $this->_db->query($query)) { 
            return false;
        }    
        
        return true;
    }
    
    /**
     * Actualizando una tarea
     *
     * @param string $tabla  El nombre de la tabla
     * @param int $idTarea El id de la tarea a actualizar
     * @param int $estado El estado a actualizar
     *
     * @return booleand o string
     */
    public function actualizarTarea($tabla, $idTarea, $estado)
    {
        try {
            $query = 'UPDATE '.$tabla.' SET state = "'.$estado.'" WHERE id = "'.$idTarea.'"';
            //  echo $query; exit();
            $rstDatos = $this->_db->query($query);
            if (!$rstDatos) {
                throw new Exception ('Error al actualizar la tarea');
            }
        } catch (Exception $e) {
            // Capturando los errores de la base de datos
            echo $e;
        }
     
        return true;
    }
}
