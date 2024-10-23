<?php
//recibir lo datos:
include '../class/base.php';
require_once '../class/usuario.php';

// Comprobar si el formulario ha sido enviado
    $correo = $_POST['correo'];
    $usuario = new Usuario();
    $result = $usuario->obtenerIdPorCorreo($correo);
    if ($result->num_rows > 0) {
    	$mensaje = $usuario->gestionarRecuperacionConCorreo($correo);
    	$mensaje = urldecode($mensaje);
    	header("location: ../recuperacion.php?mensaje=". $mensaje);
    } else{
    	header("location: ../recuperacion.php?mensaje=Error+al+enviar+el+enlace,verifique+si+el+correo+es+el+correspondiente.");
    }
exit();    
?>