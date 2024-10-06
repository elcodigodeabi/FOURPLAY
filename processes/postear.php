<?php
session_start();
//traigo mis archivos.
require '../class/base.php';
require '../class/post.php';
//recibo los datos.
$usuarioId=$_SESSION['id'];
$descPost=$_POST['desc'];
$juego=$_POST['juego'];
$categoria=$_POST['categoria'];
$imagen=$_FILES['imagen']['tmp_name'];//aqui se almacena el archivo.
$nombreImagen=$_FILES['imagen']['name'];//nombre del archivo.
$tipoImagen=strtolower(pathinfo($nombreImagen,PATHINFO_EXTENSION));//se obtiene el tipo de imagen.
$tamañoImagen=$_FILES['imagen']['size'];

if (empty($_FILES['imagen']['tmp_name']) || $_FILES['imagen']['error'] == UPLOAD_ERR_NO_FILE) {
    $juego = !empty($_POST['juego']) ? intval($_POST['juego']) : NULL;
	$categoria = !empty($_POST['categoria']) ? intval($_POST['categoria']) : NULL;
	$ubicacionPost = "";
	$post= new Post();
	if($post->postear($descPost, $ubicacionPost, $usuarioId, $juego, $categoria)){
		$mensaje = "Tu publicación se ha realizado correctamente. ¡Gracias por compartir!";
		header("location: ../menu.php?mensaje=". urlencode($mensaje));
	}else{
		$mensaje = "Hubo un problema al intentar publicar tu mensaje. Por favor, inténtalo de nuevo más tarde.";
		header("location: ../menu.php?mensaje=". urlencode($mensaje));
	}
	exit();
}

// Cláusulas guardia
if ($tipoImagen !== 'jpg' && $tipoImagen !== 'jpeg' && $tipoImagen !== 'png') {
    $mensaje = "El tipo de archivo no es válido. Solo se permiten imágenes en formato JPG, JPEG o PNG.";
    header("Location: ../menu.php?mensaje=" . urlencode($mensaje));
    exit();
}

if ($tamañoImagen > 5242880) {
    $mensaje = "El tamaño de la imagen excede el límite permitido de 5 MB.";
    header("Location: ../menu.php?mensaje=" . urlencode($mensaje));
    exit();
}

$juego = !empty($_POST['juego']) ? intval($_POST['juego']) : NULL;
$categoria = !empty($_POST['categoria']) ? intval($_POST['categoria']) : NULL;

//realizar la alta del post.
$nombreImagen = time() . "_" . $nombreImagen;
$ubicacion="../profile/".$usuarioId."/"."post/".$nombreImagen;
//esta ubicacion es la cual va a tener el registro sin los dos puntos ".."
$ubicacionPost="profile/".$usuarioId."/"."post/".$nombreImagen;
$post= new Post();
if($post->postear($descPost, $ubicacionPost, $usuarioId, $juego, $categoria)){
	move_uploaded_file($imagen, $ubicacion);
	$mensaje = "Tu publicación se ha realizado correctamente. ¡Gracias por compartir!";
	header("location: ../menu.php?mensaje=". urlencode($mensaje));
}else{
	$mensaje = "Hubo un problema al intentar publicar tu mensaje. Por favor, inténtalo de nuevo más tarde.";
	header("location: ../menu.php?mensaje=". urlencode($mensaje));
}
?>