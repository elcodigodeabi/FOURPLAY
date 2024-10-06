<?php
session_start();
include '../class/base.php';
include '../class/post.php';

// Verificamos si el ID del post está presente en la URL
if (!isset($_GET['post'])) {
    $mensaje = urlencode('ID del post no especificado.');
    header("Location: ../menu.php?mensaje=$mensaje");
    exit();
}

$posId = $_GET['post'];
$post = new Post();

// Obtenemos los datos del post
$datosPost = $post->obtenerPostPorId($posId);

// Verificamos que el post exista y que el usuario que lo creó es el mismo que está en la sesión
if ($datosPost && $datosPost['usu_id'] == $_SESSION['id']) {
    // Eliminamos el post
    if ($post->eliminarPostPorId($posId)) {
        $mensaje = urlencode('Post eliminado exitosamente.');
    } else {
        $mensaje = urlencode('Error al eliminar el post.');
    }
} else {
    $mensaje = urlencode('No tienes permiso para eliminar este post o el post no existe.');
}

// Redireccionamos a menu.php con el mensaje
header("Location: ../menu.php?mensaje=$mensaje");
exit();
?>