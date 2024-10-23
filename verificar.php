<?php
//se reciben los datos del formulario login.
$dataUser = $_POST['data-user'];
$pass = $_POST['password'];

//se hace uso de la clase usuarios.
require 'class/base.php';
include 'class/usuario.php';
$usuario = new Usuario();
$result=$usuario->verificar($dataUser, $pass);
if ($result->num_rows > 0) {
	$result = mysqli_fetch_assoc($result);
	$usuario->iniciarSesion($result['usu_id'], $result['usu_nombre'], $result['usu_apellido'], $result['usu_alias']);
	header("location: menu.php");
} else{
	$mensaje = "El correo electrónico/alias o la contraseña que has ingresado no son correctos. Por favor, verifica tus datos e inténtalo nuevamente.";
    header("Location: index.php?mensaje=" . urlencode($mensaje));
    exit();
}
?>