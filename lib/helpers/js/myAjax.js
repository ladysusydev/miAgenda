/**
 * Variables de URL pasadas
 * 
 * @author      José Roberto Alas <jrobertoalas@gmail.com>
 * @copyright   Copyright (C) 2019, Open Source LadySusy
 */

$(document).ready(function() {
    // Agregando un nuevo registro en dia1
    $("#frmtareas1").keydown(function(e) {
        var nombre = $("#nombre1").val();
        if (e.which == 13) {
            if (nombre == "") {
                alert("Ingrese una tarea");
                return false;
            } else {
			    $("#LoadingImage1").show(); // Mostrar cargando imagen
                var datos = 'tarea=registrar&do=dia1&nombre='+$('#nombre1').val()+'&fecha='+$('#fecha').val();
                $.ajax({
                    url: "index.php",
                    type: "POST",
                    dataType:"text",
                    data: datos,
                    success:function(response){
						$("#pagina1").append(response);
						$("#LoadingImage1").hide(); // Escondemos la imagen
					},
                });
                
                $("#frmtareas1")[0].reset();
            }
            return false;
        }
    });
    
    // Agregando un nuevo registro en dia2
    $("#frmtareas2").keydown(function(e) {
        var nombre = $("#nombre2").val();
        if (e.which == 13) {
            if (nombre == "") {
                alert("Ingrese una tarea");
                return false;
            } else {
			    $("#LoadingImage2").show(); // Mostrar cargando imagen
                var datos = 'tarea=registrar&do=dia2&nombre='+$('#nombre2').val()+'&fecha='+$('#fecha').val();
                $.ajax({
                    url: "index.php",
                    type: "POST",
                    dataType:"text",
                    data: datos,
                    success:function(response){
						$("#pagina2").append(response);
						$("#LoadingImage2").hide(); // Escondemos la imagen
					},
                });
                
                $("#frmtareas2")[0].reset();
            }
            return false;
        }
    });
    
   // Eliminando un registro dia1
   $("body").on("click", "#pagina1 .del_button", function(e) {
       e.preventDefault();
       var idPress = this.id.split('-');
       var idDb = idPress[1];
       var misdatos = 'tarea=eliminar&idDel='+idDb;
       
       $(this).hide();
       $.ajax({
           type: "POST",
           url: "index.php",
           dataType: "text",
           data: misdatos,
           success:function(response){
               $('#item_'+idDb).fadeOut(1000);
           },
        });
    });
    
    // Eliminando un registro dia2
   $("body").on("click", "#pagina2 .del_button", function(e) {
       e.preventDefault();
       var idPress = this.id.split('-');
       var idDb = idPress[1];
       var misdatos = 'tarea=eliminar&idDel='+idDb;
       
       $(this).hide();
       $.ajax({
           type: "POST",
           url: "index.php",
           dataType: "text",
           data: misdatos,
           success:function(response){
               $('#item_'+idDb).fadeOut(1000);
           },
        });
    });
    
    // Actualizando tareas
   $("body").on("click", "#pagina1 .mahref", function(e) {
       e.preventDefault();
       var idPress = this.id.split('-');
       var tEst = idPress[0];
       var idDb = idPress[1];
       var misdatos = 'tarea=actualizar&idAct='+idDb+'&Est='+tEst;
       
       $(this).hide();
       $.ajax({
           type: "POST",
           url: "index.php",
           dataType: "text",
           data: misdatos,
           success:function(response){
           $("#test").show(); // Mostrar cargando imagen

           },
        });
    });
});


$(function() {

    $('#login-form-link').click(function(e) {
		$("#login-form").delay(100).fadeIn(100);
 		$("#register-form").fadeOut(100);
		$('#register-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#register-form-link').click(function(e) {
		$("#register-form").delay(100).fadeIn(100);
 		$("#login-form").fadeOut(100);
		$('#login-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});

});
