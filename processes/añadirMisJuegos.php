<?php
session_start();
	if (isset($_POST['juego-opcion'])) {
		//clausula guardia
		if ($_POST['juego-opcion'] === "") {
		    header("Location: ../perfil.php?mensaje=" . urlencode("Por favor, selecciona un juego válido."));
		    exit();
		}
		//recibo datos
		$idJuego = $_POST['juego-opcion'];
		$id = $_SESSION['id'];
		//cargar mi preferencia
		include '../class/base.php';
		include "../class/juego.php";
		$juego = new Juego();
		//verificar juego
		$result = $juego->verificarJuego($idJuego, $id);
		if ($result->num_rows>0) {
			$mensaje = "Este juego ya ha sido añadido a tus preferencias previamente.";
			header("location: ../perfil.php?mensaje=". urlencode($mensaje));
			exit();
		}
		$result = $juego->añadirJuego($idJuego, $id);
		if ($result) {
			$mensaje= "¡Juego agregado exitosamente a tus preferencias! 🎮 ¡Disfruta tu próxima aventura!";
			header("location: ../perfil.php?mensaje=". urlencode($mensaje));
			exit();
		}else{
			$mensaje= "Hubo un error al agregar el juego a tus preferencias. 😕 Por favor, intenta de nuevo.";
			header("location: ../perfil.php?mensaje=". urlencode($mensaje));
			exit();
		}
	}
?>