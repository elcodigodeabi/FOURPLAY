<?php
require '../class/base.php'; 
include("../class/usuario.php");

$alias = $_POST['alias'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$pass = $_POST['password'];
$passConfirmar = $_POST['password-confirmar'];

$user = new Usuario();

function errores($tipo){	
	if ($tipo=="error contraseña") {return "Algo fallo al confirmar las contraseñas,por favor revise bien ambas contraseñas al registrarse.";}
	if ($tipo=="error mail o alias") { return "Al parecer el correo o alias que ya estan en uso, revise si tiene una cuenta ya activa en nuestra plataforma";}
	if ($tipo=="error registro") { return "Ah ocurrido un error al registrarse, intentelo mas tarde";}
}
//clausulas guardia: estas clausulas son para asegurar y avisar de por algun tipo de error.
//confirmar contraseña:
if ($pass !== $passConfirmar) {
	$mensaje = errores("error contraseña");
    header("Location: ../registro.php?mensaje=" . urlencode($mensaje));
    exit();
}
//mail y alias existente:
$result = $user->verificarUsuarioRegistro($alias, $correo);
if($result->num_rows > 0){
	$mensaje = errores("error mail o alias");
    header("Location: ../registro.php?mensaje=" . urlencode($mensaje));
    exit();
}

//comprobar registro
$result = $user->insertarUsuario($alias, $correo, $nombre, $apellido, $pass);
if ($result) {
	//Obtener id del usuario creado.
	$registro = $user->obtenerIdporCorreo($correo);
	$registro = mysqli_fetch_assoc($registro);
	//codigo para crear los directorios del usuario creado.
	$profileDir = 'profile';
	$newDir = '../'.$profileDir . '/' . $registro['usu_id'];

	mkdir($newDir, 0755, true);
	$subDirs = ['foto_perfil', 'post'];
	foreach ($subDirs as $subDir) {
	    mkdir($newDir . '/' . $subDir, 0755, true);
	}

	//codigo para copiar perfil.png a la carpeta perfil foto_perfil.

	// Ruta de la imagen original
	$source = '../public/perfil.png';

	// Ruta de destino
	$destination = $newDir . '/foto_perfil/perfil.png';

	// Copiar la imagen
	if (copy($source, $destination)) {
	    echo "Imagen copiada con éxito.";
	} else {
	    echo "Error al copiar la imagen.";
	}

	$mensaje = "¡Registro completado con éxito! Bienvenido/a, ya puedes disfrutar de todas las funciones de nuestra plataforma. 🎉";
	header("Location: ../index.php?mensaje=" . urldecode($mensaje));
}else{
	$mensaje = errores("error registro");
	header("Location: ../registro.php?mensaje=" . urlencode($mensaje));
	exit();
}
?>