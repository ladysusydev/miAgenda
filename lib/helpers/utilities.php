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

class Utilities
{
   /*
    * Metodo que me permite invertir una fecha completa
    * 
    * @param string $fecha que sera invertida, esta debe estar en el formato dd-mm-yyyy
    * 
    * @return string con fecha invertida
    */
   public static function invertirFecha($fecha)
   {
      // Separando la fecha
      $array_fecha = explode('-', $fecha);
  
      $extr = array_slice($array_fecha, 2);
      array_pop($array_fecha);
      $reversa = array_reverse($array_fecha);
        
      // Combinando los array
      $com = array_merge($extr,$reversa);
   
      // Uniendo el array en un string
      $fecha_conf = implode('-', $com);

      return $fecha_conf;
   }
   
   /**
    * Obteniendo el dia de la semana
    * 
    * @param int $ano El año
    * @param int $mes El mes
    * @param int $dia El dia
    * 
    * @return string dia
    */
   public static function diaSemana($ano, $mes, $dia) 
   {
      $dia = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
      return $dia;
   }
   
   /**
      * Me permite obtener todos los elementos de un formulario
      * 
      * @param array $keys   Valores a obtener
      * @param string $exclede   Valor a excluir
      * 
      * @return array con los valores de los elementos
      */ 
    public static function datosPost($keys, $exclude = null)
    {
      $array = array();
      // Un ciclo con la información
      foreach ($_POST as $key=>$val)
      {
         if (is_array($keys))
         {
            if (in_array($key, $keys)) $array[$key] = $val;
         } elseif($keys==="TODOS") {
            if (isset($exclude))
            {
               if(is_array($exclude))
               {
                  if (!in_array($key,$exclude)) $array[$key] = $val;
               } else {
                  if ($key!=$exclude) $array[$key] = $val;
               }
         
            } else {
               $array[$key] = $val;
            }
      
         } else 
       
         return $_POST[$keys];
      }
      return $array;
   }
   
    /**
    * Me permite eliminar un array
    * 
    * @param  $array
    * @param  $deleteItkey
    * @param  $useOldKeys
    * 
    * @return boolean True En caso de exito
    */ 
   public static function elimArray(&$array, $deleteItKey, $useOldKeys = FALSE)
   {
      if($deleteItKey === FALSE) return FALSE;
      unset($array[$deleteItKey]);
         
      return TRUE;
   }
}
