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
// Evitar acceso directo
defined('_LS') or die;

$user = LSPrincipal::getUser();
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>miAgenda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="imagenes/favicon.ico">
    
    <!-- Los estilos -->
    <link rel="stylesheet" href="template/<?php echo $config->template; ?>/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="template/<?php echo $config->template; ?>/css/miagenda.css" type="text/css" />
    <link rel="stylesheet" href="template/<?php echo $config->template; ?>/css/gijgo.min.css" type="text/css" />
    <link rel="stylesheet" href="template/<?php echo $config->template; ?>/css/font-awesome.min.css" type="text/css" />

    <!-- Codigo JS, necesario -->
    <script src="template/<?php echo $config->template; ?>/js/jquery-3.3.1.js" type="text/javascript"></script>
    <script src="template/<?php echo $config->template; ?>/js/popper.min.js" type="text/javascript"></script>
    <script src="template/<?php echo $config->template; ?>/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="template/<?php echo $config->template; ?>/js/gijgo.min.js" type="text/javascript"></script>
    
    <style>
      body {
        padding-top: 60px;
      }
       
    </style>
    <script src="lib/helpers/js/myAjax.js"></script> 
</head>
<body>
<div id="imgls">
    <img class="cel" src="imagenes/Cel.png" alt="Cel"/>
    <img class="taza" src="imagenes/Taza.png"  alt="Taza"/>
</div>
<?php
if ($user->isAuthenticated()) {
$contex = new stdClass();
$contex->opcion = strtolower(LSReqenvironment::getVar('ladysusycom'));
if ($contex->opcion == 'com_base') {
?>
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-2">
            <div class="row">
                <div class="col-8 p-3">
                    <a href="index.php" class="navbar-link"><img src="imagenes/logo.png"></a>
                    <a href="index.php?ladysusycom=com_login&tarea=logout" class="navbar-link"><img src="imagenes/logout.png"></a> 
                </div>
                
                <div class="col-3 pt-3 pb-0 pl-0 pr-0">
                    <form action="index.php?ladysusycom=com_base" method="post">
                    <div class="form-group">
                        <div class="input-group col-md-12">
                            <input id="datepicker" name="fecha" class="form-control-sm border-right-0 border" type="search" value="search">
                        </div>
                    </div>
                    <script>
                        $('#datepicker').datepicker({ uiLibrary: 'bootstrap4', modal: true, header: true, footer: false, showOnFocus: true, showRightIcon: false, format: 'dd-mm-yyyy'
                        });
                    </script>
                </div>
                <div class="col-1 pt-3 pb-0 pl-0 pr-0">
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary btn-sm">Buscar</button>
                    </div>
                    </form>
                </div>
                
            </div>
        </div>
        <div class="col-md-10 offset-md-2">
    <?php
        } else {
    }
}
?>
