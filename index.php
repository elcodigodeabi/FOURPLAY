<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="public/login.css">
	<link rel="stylesheet" type="text/css" href="public/fondo.css">
	<title>¡Bienvenido/a a 4play!</title>
	<link rel="icon" href="public/favicon.png" type="image/png">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
	<div class="display-login">
		<div class="registro"><span class="registro__text">¿No tienes una cuenta?</span><a href="registro.php"><button class="registro__boton">Registrate</button></a></div>
		<form action="verificar.php" method="post" class="login">
			<div class="login__logo"><?php include 'public/logo.php';?></div>
			<h2 class="login__titulo">Fourplay</h2>
			<h4 class="login__subtitulo">¡Te damos la bienvenida!</h4>
			<label class="login__labels">Usuario o correo</label>
			<input class="login__inputs" type="text" name="data-user" id="data-user" maxlength="255" pattern="[^*\(\)\[\]\{\}<>]*" title="No se permiten caracteres como paréntesis, corchetes o llaves" required>
			<label class="login__labels">Contraseña</label>
			<input class="login__inputs" type="password" name="password" id="password" maxlength="30" pattern="[^*\(\)\[\]\{\}<>]*" title="No se permiten caracteres como paréntesis, corchetes o llaves" required>
			<input class="login__inputs input--submit" type="submit" value="Ingresar">
			<div class="recuperacion"><span class="recuperacion__text">¿Te olvidaste tu contraseña?</span><a href="recuperacion.php"><button type="button" class="recuperacion__boton">¡Haz click aqui!</button></a></div>
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