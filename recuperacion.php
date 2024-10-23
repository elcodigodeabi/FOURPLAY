<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="public/recuperacion.css">
	<title>Recuperacion</title>
	<link rel="icon" href="public/favicon.png" type="image/png">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
	<div class="display">
		<nav><div class="contenedor-logo"><?php include 'public/logo.php'; ?><b class="contenedor-logo__titulo">Fourplay</b></div><div class="boton-login"><span class="boton-login__text">Tenes una cuenta hecha?</span><a href="index.php" class="boton-login__boton">Inicia sesión</a></div></nav>
		<?php if (isset($_GET['token'])) { ?>
			<form action="processes/cambiarContraseña.php" method="post" class="nueva-contraseña">
				<input type="hidden" name="token" value="<?php echo $_GET['token'];?>">
				<p class="nueva-contraseña__text">Por favor, ingresa una nueva contraseña. Asegúrate de que sea segura y compleja, utilizando una combinación de letras, números y símbolos.</p>
				<label class="nueva-contraseña__labels" for="nueva_contrasena">Nueva Contraseña:</label>
		        <input class="nueva-contraseña__inputs" type="password" name="pass" required>
		        <label class="nueva-contraseña__labels" for="confirmar_contrasena">Confirmar Contraseña:</label>
		        <input class="nueva-contraseña__inputs" type="password" name="pass-confirmacion" required>
		        <input class="nueva-contraseña__inputs input--submit" type="submit" value="Cambiar Contraseña">
			</form>	
		<?php }else{ ?>
			<form action="processes/enviarEnlace.php" class="solicitud-recuperacion" method="post">
				<h4 class="solicitud-recuperacion_titulo">Solicitud de contraseña</h4>
				<p class="solicitud-recuperacion_text">Por favor, ingresa tu correo electrónico registrado en nuestra página para recibir un enlace de recuperación de contraseña a tu correo, recorda fijarte en tu casilla de email.</p>
		        <label for="correo" class="solicitud-recuperacion_labels">Correo electrónico:</label>
		        <input type="email" name="correo" class="solicitud-recuperacion_inputs" required>
		        <button type="submit" class="solicitud-recuperacion_inputs input--submit">Enviar enlace de recuperación</button>
		    </form>
		<?php } ?>
		<?php if (isset($_GET['mensaje'])) { ?>
				<div class="tarjeta-errores">
					<?php include 'public/error.php';?>
					<p class="tarjeta-errores__text"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
				</div>
		<?php } ?>
	</div>
	<script type="text/javascript">
		setTimeout(function() {
		    const tarjetaErrores = document.querySelector('.tarjeta-errores');
		    if (tarjetaErrores) {
		        tarjetaErrores.style.display = 'none';
		    }
		}, 8000);
	</script>
</body>
</html>