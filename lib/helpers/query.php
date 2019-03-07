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
 * clase
 */
class Query extends LSModelo
{
    
    /**
     * Cuenta el total de registros
     * 
     * @param string $tabla Nombre de la tabla
     * 
     * @return int Total de registros
     */ 
    public function totalRegistros($tabla)
    {        
        $db = $this->getDbo();
        // Consultando los datos
        $sql = "SELECT * FROM ".$tabla."";
        if ($result = $db->query($sql)) { 
            // Obtener el total
            $total = $result->num_rows;
            $result->close();
        }    
        
        return $total;
    }
    
    
    public function busquedaContenido($tabla, $text, $accion)
    {
        $text = trim($text);
        if ($text == '') {
            return array();
        }

        $wheres = array();
        switch ($accion) {
            case 'equipos':
                $textTemp    = '%'.$text.'%';
                $texto         = "'".$textTemp."'";
                $wheres2    = array();
                $wheres2[]    = 'e.activo LIKE '.$texto;
                $wheres2[]    = 'm.nombre LIKE '.$texto;
                $wheres2[]    = 'mo.nombre LIKE '.$texto;
                $wheres2[]    = 'u.nombre LIKE '.$texto;
                $where        = '(' . implode(') OR (', $wheres2) . ')';
                break;
        }

        $db = $this->getDbo();
        $query = $db->getQuery();
        $query->select('e.id AS id, e.activo AS activo, e.estado AS estado, m.nombre AS marca, mo.nombre AS modelo, u.nombre AS ubicacion');
        $query->from($tabla.' AS e');
        $query->join('','mant_marca AS m ON e.id_marca=m.id');
        $query->join('','mant_modelo AS mo ON e.id_modelo=mo.id');
        $query->join('','mant_ubicacion AS u ON e.id_ubicacion=u.id');
        $query->join('','mant_utilitarios AS c ON e.id_utilitarios=c.id');
        $query->where($where);
        $query->order('e.id ASC');
        
        if (!$result = $db->query($query)) { 
            return false;
        }    

        return $result;
            
    }
    
    /**
     * Obteniendo datos del usuario
     * 
     * @param  int  $id  Id del usuario
     *
     * @return query en caso de exito y null por falla
     */
    public function getDatosUser($id)
    {            
        // Consultando los datos
        $db = $this->getDbo();
        $query = $db->getQuery();
        $query->select('u.nombre1 AS Nombre, u.foto AS Foto');
        $query->from('&&__user AS u');
        $query->where('u.id = '.$id.'');
        
        if (!$resultSQL = $db->setQuery($query)) {
            return null;
        }   
        
        return $resultSQL;
    }
}
