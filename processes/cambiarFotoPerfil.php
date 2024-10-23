<?php
session_start();
require '../class/base.php';
// Ruta al directorio donde está el archivo perfil.png
$directorio = '../profile/' . $_SESSION['id'] . '/foto_perfil/';
$nombre_archivo = 'perfil.png';

// Recibir los datos del archivo
$archivo_temporal = $_FILES['foto']['tmp_name'];
$nombre_archivo_original = $_FILES['foto']['name'];
$tipo_archivo = strtolower(pathinfo($nombre_archivo_original, PATHINFO_EXTENSION));
$tamaño_archivo = $_FILES['foto']['size'];

// Validar el tipo y tamaño del archivo
if (!in_array($tipo_archivo, ['jpg', 'jpeg', 'png'])) {
    $mensaje = 'Solo se permiten archivos de imagen JPEG, JPG o PNG.';
    header("Location: ../perfil.php?mensaje=" . urlencode($mensaje));
    exit;
}

if ($tamaño_archivo > 5242880) { // 5 MB
    $mensaje = 'El archivo excede el tamaño máximo permitido (5 MB).';
    header("Location: ../perfil.php?mensaje=" . urlencode($mensaje));
    exit;
}

// Verificar si el directorio existe y crearlo si no
if (!is_dir($directorio) && !mkdir($directorio, 0777, true)) {
    $mensaje = 'No se pudo crear el directorio para almacenar la foto de perfil.';
    header("Location: ../perfil.php?mensaje=" . urlencode($mensaje));
    exit;
}

// Ruta completa al archivo destino
$ruta_destino = $directorio . $nombre_archivo;

// Mover el archivo subido y reemplazar perfil.png
if (move_uploaded_file($archivo_temporal, $ruta_destino)) {
    $mensaje = 'La foto de perfil se ha subido y reemplazado correctamente.';
} else {
    $mensaje = 'Hubo un error al mover el archivo. Verifica los permisos del directorio.';
}

// Redireccionar a la página de perfil con el mensaje
header("Location: ../perfil.php?mensaje=" . urlencode($mensaje));
exit;
?>