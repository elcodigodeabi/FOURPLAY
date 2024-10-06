<?php
	if (isset($_GET['usuario'])&&isset($_GET['juego'])) {
	 	$usuarioId = $_GET['usuario'];
	 	$juegoId = $_GET['juego'];
	 	include '../class/base.php';
	 	include '../class/juego.php';
	 	$juego = new Juego();
	 	$result = $juego->eliminarJuego($usuarioId, $juegoId);
	 	if ($result) {
	 		$mensaje = "La preferencia del juego ha sido eliminada exitosamente.";
	 		header("location: ../perfil.php?mensaje=" . urlencode($mensaje));
	 	} else{
	 		$mensaje = "Hubo un error al intentar eliminar la preferencia del juego. Por favor, intenta de nuevo.";
	 		header("location: ../perfil.php?mensaje=" . urlencode($mensaje));
	 	}
	}else{
		header("location: ../perfil.php?mensaje=error");
	} 
?>