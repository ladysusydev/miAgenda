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
 * Clase base para controlar la vista
 */
class BaseViewBase extends LSView
{    
    /**
     * Lista de tareas en forma de array
     * @var array
     */ 
    public $listaTareas = null;
    
    /**
     * Una instancia al modelo del componente
     * @var object
     */ 
    public $modelo = null;
    
    /**
     * $var string $dia1
     */ 
    public $dia1 = null;
    
     /**
     * $var string $dia2
     */ 
    public $dia2 = null;
    
    /**
     * Presenta los datos en la vista
     * 
     * @param string $template El template a usar
     * 
     * return void
     */
    public function display($template = null)
    {
        // Asignaciones necesarias
        $this->modelo = $this->getModel();
        $tarea = LSReqenvironment::getVar('tarea', null, 'post');
        $fechaBusq = LSReqenvironment::getVar('fecha', null, 'post');
        $app = LSPrincipal::getApplication('aplicacion');
        if (isset($_POST['do'])) {
            $dFecha = $_POST['do'];
        }

        // Definiendo estado de la fecha
        if ($fechaBusq == null) {
            $fecha = null;
        } else {
            // Invirtiendo fecha al estilo yyyy-mm-dd
            $fecha = Utilities::invertirFecha($fechaBusq);
        }
        // Calculando fechas necesarias para despliegue de informacion en DIV
        $this->dia1 = $this->fechaTareas('d1', $fecha);
        $this->dia2 = $this->fechaTareas('d2', $fecha);
        
        if (!empty($tarea)) {
            if ($tarea == 'registrar') {
                $nombre = $_POST['nombre'];
                if ($dFecha == 'dia1') {
                    $faccion = $this->dia1;
                } else {
                    $faccion = $this->dia2;
                }
                $fecha = date("Y"."-"."m"."-"."d");
                $result = $this->modelo->registroTarea('ls_task', $nombre, $faccion);
                if ($result != false) { 
                    // No hacemos nada
                } 
            } elseif ($tarea == 'eliminar') {
                $idDel = $_POST['idDel'];
                $this->modelo->eliminarTarea('ls_task', $idDel);
                
            } elseif ($tarea == 'actualizar') {
                $idTarea = $_POST['idAct'];
                $estado = $_POST['Est'];
                $this->modelo->actualizarTarea('ls_task', $idTarea, $estado);
            }
        }
        
        if ($tarea == null) {
            $this->listaTareas = $this->datosIniciales($fecha);
            if ($this->listaTareas == false) {
                echo 'Lo siento se dio un error al consultar los datos';
            }
            $pensamiento = $this->pensamiento();
            $this->MensajePensamiento = $pensamiento['text'];
            $this->athorPensamiento = $pensamiento['author'];
            parent::display($template);
        }
        
        
    }
    
    /**
     * Funcion que me permite obtener los datos iniciales
     * 
     * @param booleand $fecha Fecha inicial
     * 
     * @return Object
     */ 
    public function datosIniciales($fecha = null)
    {    
       $datosIni = $this->modelo->getTarea('ls_task', $this->dia1, $this->dia2); 
       return $datosIni;
   }
  
   /**
    * Me permite obtener el dia para la tarea
    * 
    * @param string $diaE Valor representativo para el dia que se quiere obtener
    * @param string $fecha La fecha sobre la que se tomara accion
    * 
    * @return array
    */ 
   public function fechaTareas($diaE, $fecha = null) 
   {
       // Formando arreglo de dia
       $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
       $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
       
       // Obteniendo el dia numerico a mostrar
       if ($diaE == 'd1') {
           if ($fecha) {
               $array_fecha = explode('-', $fecha);
               $anog = $array_fecha[0];
               $mesg = $array_fecha[1];
               $diag = $array_fecha[2];
               $diaStr = $dias[Utilities::diaSemana($anog, $mesg, $diag)];
               $diaInt = $diag;
               $mesInt = $mesg;
               $mesStr = $meses[$mesInt-1];
               $anoRet = $anog;
           } else {
               $diaStr = $dias[date('w')];
               $diaInt = date('d');
               $mesInt = date('m');
               $mesStr = $meses[$mesInt-1];
               $anoRet = date('Y');
           }
       } elseif ($diaE == 'd2') {
           if ($fecha) {
               $array_fecha = explode('-', $fecha);
               $anog = $array_fecha[0];
               $mesg = $array_fecha[1];  
               $diag = $this->getProximo($array_fecha[2], $mesg, $anog);
               // Agregando un cero cuando es requerido
               if ($diag < 10) {
                   $diaInt = str_pad($diag, 2, "0", STR_PAD_LEFT); 
               } else {
                   $diaInt = $diag;
               }
               
               if ($diaInt == 01) {
                   if ($mesg == 12) {
                        $mesg = 1;
                        $mesg = str_pad($mesg, 2, "0", STR_PAD_LEFT);
                        $anog += 1;
                   } else {
                       $mesg += 1;
                   }
               } 
               $diaStr = $dias[Utilities::diaSemana($anog, $mesg, $diaInt)];
               $mesInt = $mesg;
               $mesStr = $meses[$mesInt-1];
               $anoRet = $anog;
          } else {
               $diaStr = (date('w') == 6) ? $dias[date('w')+(-6)] : $dias[date('w')+1];
               $diaInt = $this->getProximo(date('d'));
               
               // Consultado salto al primer dia, para definir nuevo mes
               if ($diaInt == 1) {
                   $aum = 1;
               } else {
                   $aum = 0;
               }
               
               // Agregando un cero cuando es requerido
               if ($diaInt < 10) {
                   $diaInt = str_pad($diaInt, 2, "0", STR_PAD_LEFT); 
               }
               $mesInt = (date('n')+$aum);
               $mesStr = $meses[$mesInt-1];
               $anoRet = date('Y');
          }
       }
       $ret['diaStr'] = $diaStr;
       $ret['diaInt'] = $diaInt;
       $ret['mesStr'] = $mesStr;
       $ret['mesInt'] = $mesInt;
       $ret['ano'] = $anoRet;
       
       return $ret;
    }
    
    /**
     * Obteniendo un dia despues de hoy
     * 
     * $param int $dia El dia sobre el que se actuara
     * $param int $mes El mes sobre el que se actuara
     * $param int $ano El ano sobre el que se actuara
     * 
     * @return int
     */ 
    public function getProximo($dia, $mes = null, $ano = null)
    {
        // Obteniendo el mes, para aumento dias finales
        if ($mes) {
            $mesAct = $mes;
        } else {
            $mesAct = date('n');
        }
        // Obteniendo datos de ano biciestros
        if ($ano) {
            if (($ano % 4 == 0 && $ano % 100 != 0) || $ano % 400 == 0) {
                $anoBic = 1;
            } else {
                $anoBic = 0;
            }
        } else {
            $anoBic = date('L');
        }

        switch ($mesAct) {
            // Para meses de 30 dias
            case 04: 
            case 06: 
            case '09': 
            case 11:
                $mesAccion = 30;
                break;
            // Para meses de 31 dias
            case 01:
            case 03:
            case 05:
            case 07:
            case '08':
            case 10:
            case 12:
                $mesAccion = 31;
                break;
            // Para meses de 28 dias
            case 02:
                if ($anoBic) {
                    $mesAccion = 29;
                } else {
                    $mesAccion = 28;
                }
                break; 
        }
        
        if (($dia == 30 && $mesAccion == 30) || ($dia == 31 && $mesAccion == 31)) {
            $diaNum = 01;
        } elseif ($dia == 28 && $mesAccion == 28) {
            $diNum = 01;
        } elseif ($dia == 29 && $mesAccion == 29) {
            $diaNum = 01;
        } else {
             $diaNum = $dia + 1;
        }
        
        return $diaNum;
    }

    /**
     * Obteniendo mensaje del dia
     */ 
    public function pensamiento() {
        $pensamientos = array(
                    array('mensaje'=>'"No habrá nada que te pueda asustar si te niegas a tener miedo."','autor'=>'Gandhi.'),
                    array('mensaje'=>'"Solo te puedes cambiar a ti mismo pero a veces eso lo cambia todo."','autor'=>'Gary W Goldstein.'),
                    array('mensaje'=>'"Despertamos en otros la misma actitud mental que tenemos hacia ellos."','autor'=>'Elbert Hubbard.'),
                    array('mensaje'=>'"Eres capaz de mucho más de lo que estas pensando, imaginando o haciendo ahora."','autor'=>'Myles Munroe.'),
                    array('mensaje'=>'"¿Cómo voy a vivir hoy de acuerdo al mañana con el que estoy comprometido?"','autor'=>'Tony Robbins.'),
                    array('mensaje'=>'"Un pequeño cambio positivo puede cambiar tu día entero o tu vida entera."','autor'=>'Nishant Grover.'),
                    array('mensaje'=>'"La única diferencia entre un buen y mal día es tu actitud."','autor'=>'Dennis S. Brown.'),
                    array('mensaje'=>'"Sin una confianza humilde pero razonable en tus propias fuerzas, no puedes ser exitoso o feliz."','autor'=>'Norman Vincent Peale.'),
                    array('mensaje'=>'"Todo es o una oportunidad para crecer o un obstáculo que evita que crezcas. Puedes decidir."','autor'=>'Wayne Dyer.'),
                    array('mensaje'=>'"Una actitud positiva puede realmente convertir los sueños en realidada-lo hizo para mi."','autor'=>'David Bailey.'),
                    array('mensaje'=>'"Estas son mis últimas palabras hacia ti. No tengas miedo de la vida. Cree que merece la pena vivirla y tu creencia creará el hecho."','autor'=>'William James.'),
                    array('mensaje'=>'"En lo que te conviertes es mucho más importante que lo que consigues. Lo que consigues esta influenciado por lo que eres."','autor'=>'Jim Rohn.'),
                    array('mensaje'=>'"Una actitud de expectativa positiva es la marca de una personalidad superior."','autor'=>'Brian Tracy.'),
                    array('mensaje'=>'"Las buenas cosas ocurren todos los días. Solo nos tenemos que dar cuenta de ellas."','autor'=>'Anne Wilson Schaef.'),
                    array('mensaje'=>'"Hay una pequeña diferencia en las personas, pero esa diferencia marca una gran diferencia. La pequeña diferencia es la actitud. La gran diferencia es si es positiva o negativa."','autor'=>'W. Clement Stone.'),
                    array('mensaje'=>'"Es algo maravilloso ser optimista. Te mantiene sano y resiliente."','autor'=>'Daniel Kahneman.'),
                    array('mensaje'=>'"El único lugar donde tus sueños son imposibles es en tus pensamientos."','autor'=>'Robert H Schulle.'),
                    array('mensaje'=>'"El aprendizaje es un regalo. Incluso cuando el dolor es tu maestro."','autor'=>'Maya Watson.'),
                    array('mensaje'=>'"Ama la vida que tienes para poder vivir la vida que amas."','autor'=>'Huseein Nishah.'),
                    array('mensaje'=>'"Tu sonrisa te dará un semblante positivo que hará que la gente se sienta mejor a tu alrededor."','autor'=>'Les Brown.'),
                    array('mensaje'=>'"Creo que cualquier cosa es posible si tienes la mentalidad, voluntad y deseo para hacerlo y dedicarle tiempo."','autor'=>'Roger Clemes.'),
                    array('mensaje'=>'"La persona que puede llevar el espíritu de la risa a una habitación es bendecida."','autor'=>'Bennet Cerf.'),
                    array('mensaje'=>'"El pensamiento positivo es algo más que un eslogan. Cambia la forma en la que nos comportamos. Creo firmemente que cuando soy positivo, soy mejor y hago mejores a los demás."','autor'=>'Harvey Mackay.'),
                    array('mensaje'=>'"Cuando eres entusiasta sobre lo que haces, sientes energía positiva. Es muy sencillo."','autor'=>'Paulo Coelho.'),
                    array('mensaje'=>'"Los ganadores tienen el hábito de crearse sus propias expectativas antes del evento."','autor'=>'Brian Tracy.'),
                    array('mensaje'=>'"El hombre no es más que el producto de sus pensamientos. Se convierte en lo que piensa."','autor'=>'Gandhi.'),
                    array('mensaje'=>'"Nunca eres demasiado viejo para tener otra meta u otro sueño."','autor'=>'C.S Lewis.'),
                    array('mensaje'=>'"Nunca digas nada de ti mismo que no quieres que se convierta en realidad."','autor'=>'Brian Tracy.'),
                    array('mensaje'=>'"Los peores tiempos pueden ser los mejores si piensas con energía positiva."','autor'=>'Domenico Dolce.'),
                    array('mensaje'=>'"Todo pensamiento es una semilla. Si plantas semillas podridas, no cuentes con recoger manzanas deliciosas."','autor'=>'Bill Meyer.'),
                    array('mensaje'=>'"La inspiración viene de tu interior. Uno tiene que ser positivo. Cuando lo eres, ocurren cosas buenas."','autor'=>'Deep Roy.'),
                    array('mensaje'=>'"Mantén tu cara hacia el sol y no podrás ver una sombra."','autor'=>'Helen Keller.'),
                    array('mensaje'=>'"Soy un pensador positivo, y creo que es lo que me ayuda en los momentos más difíciles."','autor'=>'Roger Federer.'),
                    array('mensaje'=>'"Convierte siempre una situación negativa en una positiva."','autor'=>'Michael Jordan.'),
                    array('mensaje'=>'"Vive la vida al máximo y enfócate en lo positivo."','autor'=>'Matt Cameron.'),
                    array('mensaje'=>'"El mundo es lo que creemos que es. Si podemos cambiar nuestros pensamientos, podemos cambiar el mundo."','autor'=>'H.M. Tomlinson.'),
                    array('mensaje'=>'"Muchas veces la gente mira al lado negativo de lo que no pueden hacer. Yo siempre miro al lado positivo de lo que puedo hacer."','autor'=>'Chuck Norris.'),
                    array('mensaje'=>'"El optimismo perpetuo es una multiplicador de fuerzas."','autor'=>'Colin Powell.'),
                    array('mensaje'=>'"Todos estamos aquí por una razón especial. Deja de ser un prisionero del pasado. Conviértete en el arquitecto de tu futuro."','autor'=>'Robin Sharma.'),
                    array('mensaje'=>'"La única cosa que se interpone entre un hombre y lo que quiere en la vida, es a menudo la voluntad de intentarlo y la fe de que es posible conseguirlo."','autor'=>'Richard M. DeVos.'),
                    array('mensaje'=>'"Si no estas encendidos con entusiasmo, serás encendido con entusiasmo."','autor'=>'Vince Lombardi.'),
                    array('mensaje'=>'"La preocupación da a menudo una gran sombra a algo pequeño."','autor'=>'Proverbio suizo.'),
                    array('mensaje'=>'"Adoptando la actitud correcta se puede convertir un estrés negativo en uno positivo."','autor'=>'Dr. Hans Selye.'),
                    array('mensaje'=>'"No conozco ese hombre, lo debo conocer mejor."','autor'=>'Abraham Lincoln.'),
                    array('mensaje'=>'"Una autoimagen fuerte y positiva es la mejor preparación posible para el éxito."','autor'=>'Joyce Brothers.'),
                    array('mensaje'=>'"Encuentra un lugar en tu interior donde haya alegría, y la alegría quemará el dolor."','autor'=>'Joseph Campbell.'),
                    array('mensaje'=>'"Puede que una actitud positiva no resuelva todos tus problemas, pero molestará las suficientes personas para hacer que el esfuerzo merezca la pena."','autor'=>'Herm Albright.'),
                    array('mensaje'=>'"Debes comenzar a pensar en ti mismo como la persona que quieres ser."','autor'=>'David Viscott.'),
                    array('mensaje'=>'"Una actitud fuertemente positiva creará más milagros que cualquier droga."','autor'=>'Patricia Neal.'),
                    array('mensaje'=>'"El pesimismo lleva a la debilidad, el optimismo al poder."','autor'=>'William James.'),
                    array('mensaje'=>'"La situación no es mala, tus pensamientos respecto a la situación son negativos. Cámbialos."','autor'=>'Anonimo.'), 
                    array('mensaje'=>'"La diferencia entre ganar y perder es a menudo no rendirse."','autor'=>'Walt Disney.'),
                    array('mensaje'=>'"La única discapacidad en la vida es una mala actitud."','autor'=>'Scott Hamilton.'),
                    array('mensaje'=>'"Para realizar una acción positiva, debemos desarrollar aquí una visión positiva."','autor'=>'Dalai Lama.'),
                    array('mensaje'=>'"Di y haz algo positivo que mejore la situación; no se necesita un cerebro para quejarse."','autor'=>'Robert A.Cook.'),
                    array('mensaje'=>'"La correción hace mucho, pero el ánimo hace mucho más."','autor'=>'Johann Wolfgang von Goethe.'),
                    array('mensaje'=>'"No se trata de la situación, sino si reaccionamos negativamente o positivamente a la situación."','autor'=>'Zig Ziglar.'),
                    array('mensaje'=>'"Si no estas cometiendo errores, no estas haciendo nada."','autor'=>'John Wooden.'),
                    array('mensaje'=>'"Primero tienes que aprender las reglas del juego. Luego tienes que jugar mejor que nadie."','autor'=>'Albert Einstein.'),
                    array('mensaje'=>'"Para tener éxito, necesitas encontrar algo a lo que aferrarte, algo que te motive, algo que te inspire."','autor'=>'Tony Dorsett.'),
                    array('mensaje'=>'"Cuando reemplaces los pensamientos negativos con los positivos, empezarás a tener resultados positivos."','autor'=>'Willie Nelson.'),
                    array('mensaje'=>'"Un pequeño pensamiento positivo en la mañana puede cambiar todo tu día."','autor'=>'Desconocido.'),
                    array('mensaje'=>'"Nuestras mentes pueden dar forma a lo que una cosa será porque actuamos de acuerdo a nuestras expectativas."','autor'=>'Federico Fellini.'), 
                    array('mensaje'=>'"Soy un optimista. No tiene mucho sentido ser otra cosa."','autor'=>'Winston Churchill.'),
                    array('mensaje'=>'"Tienes que aceptar lo que viene y lo único importante es que lo afrontes con coraje y con lo mejor que tienes."','autor'=>'Eleanor Roosevelt.'),
                    array('mensaje'=>'"Cuando estés en el valle, mantén tu meta firmemente en mente y tendrás energías renovadas para continuar la escalada."','autor'=>'Denis Waitley.'),
                    array('mensaje'=>'"Nutre la mente como lo harías con tu cuerpo. La mente no puede sobrevivir con comida chatarra."','autor'=>'Jim Rohn.'),
                    array('mensaje'=>'"Esta es la ley de la atracción: no atraes lo que quieres. Atraes lo que eres."','autor'=>'Wayne Dyer.'),
                    array('mensaje'=>'"Escribe en tu corazón que cada día es el mejor día del año."','autor'=>'Ralph Waldo Emerson.'),
                    array('mensaje'=>'"No puedes tener una vida positiva y una mente negativa."','autor'=>'Joyce Meyer.'),
                    array('mensaje'=>'"El pensador positivo ve lo imposible, siente lo intangible y consigue lo imposible."','autor'=>'Desconocido.'),
                    array('mensaje'=>'"Termina el día siempre con un pensamiento positivo. No importa lo duras que fueron las cosas, mañana es una buena oportunidad para hacerlas mejor."','autor'=>'Desconocido.'),
                    array('mensaje'=>'"La gente se vuelve realmente notable cuando empiezan a pensar que pueden hacer cosas. Cuando creen en si mismos, tienen el primer secreto del éxito."','autor'=>'Norman Vincent Peale.'),
                    array('mensaje'=>'"Aprende a sonreír en toda situación. Míralo como una oportunidad para probar tu fuerza y habilidad."','autor'=>'Joe Brown.'),
                    array('mensaje'=>'"El día es lo que haces de él. ¿Por qué no hacer un gran día?','autor'=>'Steve Schulte.'),
                    array('mensaje'=>'"Somos responsables de lo que somos, y no importa lo queremos ser, tenemos el poder de hacernos a nosotros mismos."','autor'=>'Swami Vivekanand.'),
                    array('mensaje'=>'"Lo has hecho antes y lo puedes hacer ahora. Mira las posibilidades positivas. Redirecciona la energía sustancial de tu frustración y conviértela en determinación positiva, efectiva e imparable."','autor'=>'Ralph Marston.'),
                    array('mensaje'=>'"No dejes que la negatividad del mundo te desmotive. En lugar de ello, date a ti mismo lo que te motiva."','autor'=>'Les Brown.'),
                    array('mensaje'=>'"Siempre hay flores para los que quieren verlas."','autor'=>'Henri Matise.'),
                    array('mensaje'=>'"Ser miserable es un hábito; ser feliz es un hábito; y la elección es tuya."','autor'=>'Tom Hopkins.'),
                    array('mensaje'=>'"Trabaja duro, se positivo y levántate temprano. Es la mejor parte del día."','autor'=>'George Allen.'),
                    array('mensaje'=>'"El optimismo es el rasgo humano más importante, porque nos permite mejorar nuestra situación y esperar un mañana mejor."','autor'=>'Seth Godin.'),
                    array('mensaje'=>'"Niégate a que la situación determine tu actitud."','autor'=>'Charles R. Swindoll.'),
                    array('mensaje'=>'"El mundo se mueve tan rápido estos días que el hombre que dice que no se puede hacer algo, es interrumpido por alguien que lo esta haciendo."','autor'=>'Elbert Hubbard.'),
                    array('mensaje'=>'"Si alguien te dice “no puedes”, realmente quiere decir “no puedo”."','autor'=>'Sean Stephenson.'),
                    array('mensaje'=>'"Hay dos maneras de desprender luz: ser la vela o el espejo que la refleja."','autor'=>'Edith Wharton.'),
                    array('mensaje'=>'"La frustración, aunque dolorosa a veces, es muy positiva y una parte esencial del éxito."','autor'=>'Bo Bennett.'),
                    array('mensaje'=>'"El sol no brilla para unas pocas flores y árboles, sino para el placer de todo el mundo."','autor'=>'Henry Ward.'),
                    array('mensaje'=>'"En lugar de pensar en lo que te hace falta, piensa en qué tienes que le hace falta a los demás."','autor'=>'Desconocido.'),
                    array('mensaje'=>'"El pensamiento positivo te dejará hacer mejor cualquier cosa que el pensamiento negativo."','autor'=>'Zig Ziglar.'),
                    array('mensaje'=>'"Si no defiendes algo, caerás por cualquier cosa."','autor'=>'Malcom X.'),
                    array('mensaje'=>'"Ámate a ti mismo. Es importante mantenerse positivo porque la belleza viene del interior al exterior."','autor'=>'Jenn Proske.'),
                    array('mensaje'=>'"Una persona es grande por sus grandes cualidades, no por la ausencia de fallos."','autor'=>'Anonimo.'),
                    array('mensaje'=>'"No dejes que la gente te falte el respeto. Rodeate de gente positiva."','autor'=>'Cuba Gooding, Jr.'),
                    array('mensaje'=>'"El pasado no tiene poder sobre el momento presente."','autor'=>'Eckhart Tolle.'),
                    array('mensaje'=>'"Cuando hago el bien, me siento bien. Cuando hago el mal, me siento mal. Esa es mi religión."','autor'=>'Abraham Lincoln.'),
                    array('mensaje'=>'"La mejor forma de ganar autoestima es hacer lo que tememos."','autor'=>'Anonimo.'),
                    array('mensaje'=>'"Tenemos la habilidad para decidir en qué pensamientos vamos a detenernos."','autor'=>'David DeNotaris.'),
                    array('mensaje'=>'"No puedes parar las olas, pero puedes aprender a surfear."','autor'=>'Jon Kabat-Zinn.'),
                    array('mensaje'=>'"Una actitud positiva es algo en lo que todos pueden trabajar y aprender a usar."','autor'=>'Joan Lunden.'),
                    array('mensaje'=>'"Una idea mediocre que genera entusiasmo llegará más lejos que una gran idea que no inspire a nadie."','autor'=>'Mary Kay Ash.'),
                    array('mensaje'=>'"Es increíble. Si la dejas, la vida cambia rápidamente de forma positiva."','autor'=>'Lindsey Vonn.'),
                    array('mensaje'=>'"Cuando te encuentres con una situación negativa, no pienses sobre ella. Hazla positiva."','autor'=>'Yoko Ono.'),
                    array('mensaje'=>'"Trabaja con energía y paz, sabiendo que los pensamientos y esfuerzos correctos traerán inevitablemente los resultados correctos."','autor'=>'James Allen.'),
                    array('mensaje'=>'"Puede que no haya llegado donde intentaba ir, pero creo que he terminado donde necesitaba estar."','autor'=>'Douglas Adams.'),
                    array('mensaje'=>'"Cada día trae nuevas posibilidades."','autor'=>'Martha Beck.'),
                    array('mensaje'=>'"La repetición constante lleva a la convicción."','autor'=>'Robert Collier.'),
                    array('mensaje'=>'"Cuanto más conscientes somos de qué somos realmente, menos problemas tenemos."','autor'=>'Lynn Grabhorn.'),
                    array('mensaje'=>'"El odio ha causado muchos problemas en este mundo y no ha solucionado ni uno."','autor'=>'Maya Angelou.'),
                    array('mensaje'=>'"Lo mejor que puedes dar a tu enemigo es el perdón; a un oponente, tolerancia; a un amigo, tu corazón; a un niño, buen ejemplo; a un padre, respeto; a tu madre, sentirse orgullosa; a ti mismo, quererte; a todo hombre, caridad."','autor'=>'Benjamin Franklin.'),
                    array('mensaje'=>'"La próxima vez que te sientas algo incómodo con la presión en tu vida, recuerda que sin presión, no hay diamantes. La presión es parte del éxito."','autor'=>'Eric Thomas.'),
                    array('mensaje'=>'"No puedes poner un limite a nada. Cuanto más sueñas, más lejo llegas."','autor'=>'Michael Phelps.'),
                    array('mensaje'=>'"Prefiero morir persiguiendo lo que quiero, que vivir haciendo lo que me quita la vida."','autor'=>'Anonimo.'),
                    array('mensaje'=>'"Primero piensa en lo que realmente quieres. Luego persíguelo con perseverancia, no te rindas. Cuando decaigas piensa en las recompensas. Con eso habrás conseguido el 50% de tu meta."','autor'=>'Anonimo.'),
                    array('mensaje'=>'"La actitud lo es todo; engloba lo que hacemos, lo que decimos, lo que pensamos y lo que obtenemos."','autor'=>'Anonimo.'),
                    array('mensaje'=>'"Llamamos buena suerte al resultado de tener una buena actitud, esforzarse, arriesgarse, perseverar y mostrarse."','autor'=>'Anonimo.'),
                    array('mensaje'=>'"Todo el coraje necesario es un pensamiento positivo para eliminar los otros cien negativos."','autor'=>'Desconocido.'),
                    array('mensaje'=>'"El tipo más importante de libertad es ser lo que realmente eres."','autor'=>'Jim Morrison.'),
                    array('mensaje'=>'"Si encuentras un camino sin obstáculos, es probable que no lleve a ninguna parte."','autor'=>'Frank A. Clark.'),
                    array('mensaje'=>'"No se trata de la meta. Se trata de crecer para convertirse en la persona que puede lograr esa meta."','autor'=>'Tony Robbins.'),
                    array('mensaje'=>'"Termina el día con un pensamiento positivo. Mañana tendrás una oportunidad de hacerlo mejor.'),
                    array('mensaje'=>'"Enamórate de tu vida cada minuto de ella.'),
                    array('mensaje'=>'"Lo mejor esta por llegar, siempre y cuando tú mismo lo quieras.'),
                    array('mensaje'=>'"Los pensamientos positivos no hacen conseguir automáticamente cosas imposibles, pero las cosas imposibles no se pueden conseguir sin pensamientos positivos.'),
                    array('mensaje'=>'"Si la oportunidad no llama, construye una puerta."','autor'=>'Milton Berle.'),
                    array('mensaje'=>'"No vemos las cosas como son, las vemos como nosotros somos."','autor'=>'Anais Nin.'),
                    array('mensaje'=>'"Si estamos creciendo, siempre estaremos fuera de nuestra zona de confort."','autor'=>'John C Maxwell.'),
                    array('mensaje'=>'"La felicidad, como la infelicidad, es una elección proactiva."','autor'=>'Stephen Covey.'),
                    array('mensaje'=>'"No puedes hacer elecciones positivas durante el resto de tu vida sin un ambiente que haga esas elecciones sencillas, naturales y agradables.'),
                    array('mensaje'=>'"Una actitud positiva provoca una reacción en cadena de pensamientos, eventos y resultados positivos. Es un catalizador y produce resultados extraordinarios."','autor'=>'Wade Boggs.'),
                    array('mensaje'=>'"Prácticamente no hay nada imposible en este mundo si simplemente pones tu mente en ello y mantienes una actitud positiva."','autor'=>'Lou Holtz.'),
                    array('mensaje'=>'"El tiempo que disfrutaste malgastando no fue malgastado."','autor'=>'John Lennon.'),
                    array('mensaje'=>'"La esperanza es el sueño del hombre despierto."','autor'=>'Aristóteles.'),
                    array('mensaje'=>'"Se agradecido por lo que tienes; acabarás teniendo más. Si te concentras en lo que no tienes, nunca tendrás lo suficiente."','autor'=>'Oprah Winfrey.'),
                    array('mensaje'=>'"Puedes crear la vida que quieres comenzando por tener una visión de ella en tu mente."','autor'=>'Anonimo'),
                    array('mensaje'=>'"Es igual de sencillo ser positivo que negativo. Solo son hábitos que puedes aprender."','autor'=>'Anonimo'),
                    array('mensaje'=>'"Haz lo que te gusta, que te guste lo que haces es el secreto de la felicidad."','autor'=>'Anonimo'),
                    array('mensaje'=>'"Piensa positivo, los pensamientos son como neumáticos que mueven nuestra vida en la dirección que canalizamos nuestros deseos."','autor'=>'Anonimo'),
                    array('mensaje'=>'"Sonríe al día y el día te sonreirá de vuelta."','autor'=>'Anonimo'),
                    array('mensaje'=>'"Nunca te rindas, los milagros ocurren cada día."','autor'=>'Anonimo'),
                    array('mensaje'=>'"Entrena tu mente para que aprenda a ver lo bueno de cada situación."','autor'=>'Anonimo'),
                    array('mensaje'=>'"La felicidad de tu vida depende de la calidad de tus pensamientos positivos."','autor'=>'Anonimo.'),
                    array('mensaje'=>'"Tienes que luchar y vencer los malos días para poder disfrutar de los mejores."','autor'=>'Anonimo.'),
                    array('mensaje'=>'"Cada pensamiento positivo que tenemos está forjando el camino hacia el futuro que deseamos."','autor'=>'Anonimo.'),
                    array('mensaje'=>'"Los pensamientos positivos generan energías positivas que atraen las mejores experiencias."','autor'=>'Anonimo.'));
        $totalPensamientos =(count($pensamientos));
        $num = rand(0, $totalPensamientos);

        $mensajeHer = array();
        $mensajeHer['text'] = $pensamientos[$num]['mensaje'];
        $mensajeHer['author'] = $pensamientos[$num]['autor'];
        
        return $mensajeHer;
    }
}
