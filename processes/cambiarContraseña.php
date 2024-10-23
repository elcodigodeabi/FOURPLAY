<?php
session_start();
require '../class/base.php';
require_once '../class/usuario.php';

$pass = $_POST['pass'];
$passConfirmacion = $_POST['pass-confirmacion'];
$token = $_POST['token'];

//clausulas guardias para verificar datos necesarios:
if (!isset($_POST['token'])) {
    $mensaje = "error token";
    header("location: ../recuperacion.php?mensaje=".urlencode($mensaje));
    exit();
}
if ($pass !== $passConfirmacion) {
    $mensaje = "¡Error!Las contraseñas no coinciden";
    header("location: ../recuperacion.php?token=".$token."&mensaje=" . urlencode($mensaje));
    exit();
}

$usuario = new Usuario();
$resultado = $usuario->cambiarClaveConToken($token, $pass);

if ($resultado) {
    $mensaje = "La contraseña fue cambiada con exito";
    header("location: ../index.php?mensaje=" . urlencode($mensaje));
    exit();
} else {
    $mensaje = "Ah sucedido un error al cambiar la contraseña.Intentelo mas tarde.";
    header("Location: ../index.php?mensaje=" . urlencode($mensaje));
    exit();
}
?>