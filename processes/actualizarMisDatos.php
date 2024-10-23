<?php
session_start();
require '../class/base.php';
include '../class/usuario.php';
$usuario = new Usuario();
if (isset($_POST['informacion-personal'])) {
    if (!empty($_POST['nombre']) && !empty($_POST['apellido'])) {
        //cambiar los datos
        $result = $usuario->actualizarInfoPersonal($_SESSION['id'], $_POST['nombre'], $_POST['apellido']);
        if ($result) {
            $_SESSION['nombre'] = $_POST['nombre'];
            $_SESSION['apellido'] = $_POST['apellido'];
            $mensaje = "Cambio realizado con éxito: Tu información ha sido actualizada correctamente.";
            header("location: ../perfil.php?mensaje=". urlencode($mensaje));
        }
        exit();
    }
}
if (isset($_POST['informacion-correo'])) {
    if (!empty($_POST['correo'])) {
        $result = $usuario->obtenerIdPorCorreo($_POST['correo']);
        if ($result->num_rows>0) {
            $mensaje = "Cambio no realizado: Correo en uso.";
            header("location: ../perfil.php?mensaje=". urlencode($mensaje));
            exit();
        }else{
            $result = $usuario->actualizarCorreo($_SESSION['id'], $_POST['correo']);
            if ($result) {
                $mensaje = "Cambio realizado con éxito: Tu información ha sido actualizada correctamente.";
                header("location: ../perfil.php?mensaje=". urlencode($mensaje));
                exit();
            }else{
                $mensaje = "Cambio no realizado: error al cambiar de correo.";
                header("location: ../perfil.php?mensaje=". urlencode($mensaje));
                exit();
            }
        }
    }
}
if (isset($_POST['informacion-alias'])) {
    if (!empty($_POST['alias'])) {
        $result = $usuario->obtenerIdPorAlias($_POST['alias']);
        if ($result->num_rows>0) {
            $mensaje = "Cambio no realizado: Alias en uso.";
            header("location: ../perfil.php?mensaje=". urlencode($mensaje));
            exit();
        }else{
            $result = $usuario->actualizarAlias($_SESSION['id'], $_POST['alias']);
            if ($result) {
                $mensaje = "Cambio realizado con éxito: Tu información ha sido actualizada correctamente.";
                header("location: ../perfil.php?mensaje=". urlencode($mensaje));
                exit();
            }else{
                $mensaje = "Cambio no realizado: error al cambiar de alias.";
                header("location: ../perfil.php?mensaje=". urlencode($mensaje));
                exit();
            }
        }
    }
}
?>