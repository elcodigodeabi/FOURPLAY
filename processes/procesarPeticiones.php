<?php
$usuarioId = $_GET['usuario'];
$grupoId = $_GET['grupo'];
$proceso = $_GET['proceso'];
//traer clases
include '../class/base.php';
include '../class/grupo.php';
$grupo = new Grupo();
echo $usuarioId." ".$grupoId." ".$proceso;
if ($proceso == "aceptar peticion") {
	$result = $grupo->aceptarPeticion($usuarioId, $grupoId);
	if ($result) {
		$mensaje = "Se ah aceptado la peticion al grupo.";
	}else{
		$mensaje = "No se ah aceptado la peticion al grupo.";
	}
	header("location: ../grupo.php?gru_id=".$grupoId."&mensaje=". urlencode($mensaje));
	exit();
}
if ($proceso == "eliminar peticion") {
	$result = $grupo->eliminarPeticion($usuarioId, $grupoId);
	if ($result) {
		$mensaje = "Se ah eliminado la peticion al grupo.";
	}else{
		$mensaje = "No se ah eliminado la peticion al grupo.";
	}
	header("location: ../grupo.php?gru_id=".$grupoId."&mensaje=". urlencode($mensaje));
	exit();
}
?>