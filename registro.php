<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro</title>
	<link rel="icon" href="public/favicon.png" type="image/png">
	<link rel="stylesheet" type="text/css" href="public/registro.css">
	<link rel="stylesheet" type="text/css" href="public/fondo.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
	<div class="display-registro">
		<nav><div class="contenedor-logo"><?php include 'public/logo.php'; ?><b class="contenedor-logo__titulo">Fourplay</b></div><div class="boton-login"><span class="boton-login__text">Tenes una cuenta hecha?</span><a href="index.php" class="boton-login__boton">Inicia sesión</a></div></nav>
		<form action="processes/registrar.php" method="post" class="registro">
			<h4 class="registro__titulo">¡Bienvenido a Fourplay!</h4>
			<p class="registro__frase">Encuentra amigos con quienes compartir tus partidas favoritas.</p>
			<label class="registro__labels">Nombre</label>
			<input class="registro__inputs" type="text" name="nombre" maxlength="50" pattern="[^*\(\)\[\]\{\}<>]*" title="No se permiten caracteres como paréntesis, corchetes o llaves" required>
			<label class="registro__labels">Apellido</label>
			<input class="registro__inputs" type="text" name="apellido" maxlength="50" pattern="[^*\(\)\[\]\{\}<>]*" title="No se permiten caracteres como paréntesis, corchetes o llaves" required>
			<label class="registro__labels">Alias (nombre de usuario)</label>
			<input class="registro__inputs" type="text" name="alias" maxlength="50" pattern="[^*\(\)\[\]\{\}<>]*" title="No se permiten caracteres como paréntesis, corchetes o llaves" required>
			<label class="registro__labels">Mail</label>
			<input class="registro__inputs" type="email" name="correo" maxlength="50" pattern="[^*\(\)\[\]\{\}<>]*" title="No se permiten caracteres como paréntesis, corchetes o llaves" required>
			<label class="registro__labels">Contraseña</label>
			<input class="registro__inputs" type="password" name="password" maxlength="50" pattern="[^*\(\)\[\]\{\}<>]*" title="No se permiten caracteres como paréntesis, corchetes o llaves" required>
			<label class="registro__labels">Confirmar contraseña</label>
			<input class="registro__inputs" type="password" name="password-confirmar" maxlength="50" pattern="[^*\(\)\[\]\{\}<>]*" title="No se permiten caracteres como paréntesis, corchetes o llaves" required>
			<p class="registro__text">Si tienes problemas para registrarte, verifica que tu correo electrónico y nombre de usuario no estén ya en uso. Estamos aquí para ayudarte a disfrutar de tu experiencia en nuestra red social de videojuegos. ¡Gracias por ser parte de nuestra comunidad!</p>
			<input class="registro__inputs input-submit" type="submit" value="Registrarse">
		</form>
		<?php if (isset($_GET['mensaje'])) { ?>
			<div class="tarjeta-errores">
				<?php include 'public/error.php';?>
				<p class="tarjeta-errores__text"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
			</div>
		<?php } ?>
	</div>
	<?php include 'templates/fondo-login.php'; ?>
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