<?php
session_start();
require 'class/base.php';
require 'class/usuario.php';
require 'class/mensaje.php';
require 'class/grupo.php';

$grupoId = isset($_GET['gru_id']) ? (int)$_GET['gru_id'] : 0;
$usuarioId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

if ($grupoId === 0 || $usuarioId === 0) {
    die("Grupo o usuario no válido.");
}

$grupo = new Grupo();
$miembro = $grupo->esMiembro($usuarioId, $grupoId);

if (!$miembro) {
    die("No eres miembro de este grupo.");
}

// Obtener los mensajes del grupo
$mensaje = new Mensaje();
$mensajes = $mensaje->obtenerMensajesPorGrupo($grupoId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>4play - Grupo</title>
    <link rel="icon" href="public/favicon.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="public/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="public/grupo.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container-form-editgrupo">
        <form class="edit_grupo" action="processes/editarGrupo.php" method="post" enctype="multipart/form-data">
            <span id="cerrar-form-editgrupo">X</span>
            <?php
            $row = $grupo->getInfo($grupoId);
            $row = mysqli_fetch_assoc($row);
            ?>
            <h4><?php echo $row['gru_nombre'];?></h4>
            <img src="" alt=""><input type="file" name="imagen_grupo">

            <label for="">Nombre del grupo</label>
            <input type="text" value="<?php echo $row['gru_nombre'];?>" name="nombre">

            <label for="">Descripción del grupo</label>
            <input type="text" value="<?php echo $row['gru_desc'];?>" name="desc">

            <input type="hidden" name="gru_id" value="<?php echo $_GET['gru_id']; ?>">

            <input type="submit" value="Cambiar" name="Cambiar">
        </form>
    </div>
    <!-- Cargar nuevo grupo -->
    <div class="container-form-addgrupo">
        <form action="processes/crearGrupo.php" method="POST" enctype="multipart/form-data">
            <span id="cerrar-form-grupo">X</span>
            <input type="file" name="imagen_grupo" required>
            <input type="text" placeholder="Nombre del grupo" name="nombre" required>
            <input type="text" placeholder="Descripcion del grupo" name="desc" required>
            <input type="submit" value="Crear Grupo" name="creargrupo">
        </form>
    </div>
    <div class="display">
        <?php include 'templates/header.php'; ?>
        <?php include 'templates/nav.php'; ?>
        <main class="contenedor-grupo">
            <div class="grupo">
                <div class="grupo__head">
                    <?php
                    $row = $grupo->getInfo($grupoId);
                    $row = mysqli_fetch_assoc($row);
                    ?>
                    <div class="edit-grupo" id="editar-grupo">
                        <img class="edit-grupo__imagen" src="grupos/<?php echo $row['gru_id']; ?>/imagen_grupo.png?v=<?php echo time(); ?>">
                        <span class="edit-grupo__text">
                            <b><?php echo $row['gru_nombre'] ;?></b><br>
                            <span><?php $descripcion = $row['gru_desc'];echo mb_strimwidth($descripcion, 0, 50, '...'); ;?></span>
                        </span>
                    </div>
                    <div id="herramientasGrupo" class="herramientas-grupo">
                        <div class="herramientas-grupo__item">
                            <?php include 'public/grupo-adherir-usuario.php' ;?>
                            <span class="herramientas-grupo__item__nombre">Nuevo miembro</span>
                        </div>
                    </div>
                    <!-- formulario para adherir nuevo miembro-->
                    <form style="display: none;" id="formAdherirUsuario" action="processes/agregarMiembro.php" method="post" class="adherir-usuario">
                        <button class="adherir-usuario__boton" type="submit">+</button>
                        <input class="adherir-usuario__grupo" type="hidden" name="grupoId" value="<?php echo $grupoId ;?>">
                        <input class="adherir-usuario__alias" placeholder="Escribe aqui el alias del usuario..." type="text" name="alias">
                    </form>
                </div>
                <div class="grupo__chat" id="chat">
                    <?php while ($row = mysqli_fetch_assoc($mensajes)): ?>
                        <?php if ($_SESSION['id'] == $row['usu_id']) { ?>
                            <div class="mensaje mi-mensaje">
                                <div class="mensaje__head mi-head">
                                    <strong>Yo</strong><small><?php echo $row['men_hora'];?></small>
                                    <img class="mensaje__head__imagen" src="profile/<?php echo $row['usu_id'] ;?>/foto_perfil/perfil.png?v=<?php echo time(); ?>">
                                </div>
                                <p class="mensaje__text mi-text"><?php echo htmlspecialchars($row['men_text']); ?></p>
                            </div>
                        <?php } else { ?>
                            <div class="mensaje">
                                <div class="mensaje__head">
                                    <img class="mensaje__head__imagen" src="profile/<?php echo $row['usu_id'] ;?>/foto_perfil/perfil.png?v=<?php echo time(); ?>">
                                    <strong><?php echo $row['usu_alias']; ?></strong><small><?php echo $row['men_hora'];?></small>
                                </div>
                                <p class="mensaje__text"><?php echo htmlspecialchars($row['men_text']); ?></p>
                            </div>
                        <?php } ?>
                    <?php endwhile; ?>
                </div>
                <form class="grupo__campo-de-texto" id="mensajeForm">
                    <input type="hidden" name="grupoId" id="grupoId" value="<?php echo $grupoId; ?>">
                    <textarea placeholder="Escribe tu mensaje..." class="grupo__campo-de-texto__texto" id="mensaje" required></textarea>
                    <button class="grupo__campo-de-texto__boton" type="submit">Enviar</button>
                </form>
            </div>
        </main>
        <?php include 'templates/aside.php'; ?>
    </div>    
<script>
    const grupoId = document.getElementById('grupoId').value;
    const usuarioId = <?php echo $usuarioId; ?>; // Obtener el ID del usuario desde la sesión
    const alias = "<?php echo $_SESSION['alias']; ?>"; // Pasar el alias desde PHP a JavaScript

    const conn = new WebSocket('ws://localhost:8080'); // URL del servidor WebSocket

    conn.onopen = () => {
        console.log('Conectado al servidor WebSocket');
    };

    conn.onmessage = (event) => {
        const msg = JSON.parse(event.data);

        // Solo mostrar mensajes de otros usuarios en el grupo actual
        if (msg.grupoId == grupoId) {
            const chatDiv = document.getElementById('chat');
            const mensajeDiv = document.createElement('div');
            mensajeDiv.classList.add('mensaje');
            //insertar los mensajes
            if (msg.usuarioId == usuarioId) {
                mensajeDiv.innerHTML = `
                    <div class="mensaje mi-mensaje">
                        <div class="mensaje__head mi-head">
                            <strong>Yo</strong><small>${msg.hora}</small>
                            <img class="mensaje__head__imagen" src="profile/${msg.usuarioId}/foto_perfil/perfil.png?v=${Date.now()}">
                        </div>
                        <p class="mensaje__text mi-text">${msg.texto}</p>
                    </div>`;
            } else {
                mensajeDiv.innerHTML = `
                    <div class="mensaje">
                        <div class="mensaje__head">
                            <img class="mensaje__head__imagen" src="profile/${msg.usuarioId}/foto_perfil/perfil.png?v=${Date.now()}">
                            <strong>${msg.alias}</strong><small>${msg.hora}</small>
                        </div>
                        <p class="mensaje__text">${msg.texto}</p>
                    </div>`;
            };
            chatDiv.appendChild(mensajeDiv);
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }
    };

    document.getElementById('mensajeForm').addEventListener('submit', (event) => {
        event.preventDefault();
        const mensaje = document.getElementById('mensaje').value;

        // Obtener la fecha y hora actuales
        const fecha = new Date().toISOString().split('T')[0]; // Fecha en formato 'YYYY-MM-DD'
        const hora = new Date().toTimeString().split(' ')[0]; // Hora en formato 'HH:MM:SS'

        // Enviar el mensaje al servidor WebSocket
        conn.send(JSON.stringify({
            grupoId: grupoId,
            usuarioId: usuarioId,
            alias: alias,
            texto: mensaje,
            fecha: fecha,
            hora: hora
        }));

        // Limpiar el campo del mensaje
        document.getElementById('mensaje').value = '';
    });        
</script>
<script>    
    document.getElementById('mensaje').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            if (event.shiftKey) {
                // Si Shift + Enter, inserta un salto de línea
                const cursorPos = this.selectionStart;
                const textBefore = this.value.substring(0, cursorPos);
                const textAfter = this.value.substring(cursorPos, this.value.length);
                this.value = textBefore + '\n' + textAfter;
                this.selectionStart = this.selectionEnd = cursorPos + 1;
                event.preventDefault(); // Evitar el envío del formulario
            } else {
                // Si solo Enter, envía el formulario pero sin recargar la página
                event.preventDefault(); // Evitar el comportamiento por defecto (salto de línea y envío del form)
                document.getElementById('mensajeForm').dispatchEvent(new Event('submit')); // Enviar el formulario de manera personalizada
            }
        }
    });
    function scrollToBottom() {
        var chat = document.getElementById("chat");
        chat.scrollTop = chat.scrollHeight;
    }

    window.onload = function() {
        scrollToBottom();
    }
</script>
<script>
    const herramientasGrupo = document.getElementById('herramientasGrupo');
    const formAdherirUsuario = document.getElementById('formAdherirUsuario');

    herramientasGrupo.addEventListener('click', function() {
        // Alterna la visibilidad del formulario
        if (formAdherirUsuario.style.display === 'none') {
            formAdherirUsuario.style.display = 'flex';
        } else {
            formAdherirUsuario.style.display = 'none';
        }
    });
</script>
<script>
    // Seleccionar elementos del DOM
    const editarGrupo = document.getElementById('editar-grupo');
    const contenedorFormEditGrupo = document.querySelector('.container-form-editgrupo');
    const cerrarEditGrupo = document.getElementById('cerrar-form-editgrupo');

    // Mostrar el formulario cuando se hace clic en el botón "Editar grupo"
    editarGrupo.addEventListener('click', function() {
        contenedorFormEditGrupo.classList.add('activado');
    });

    // Ocultar el formulario cuando se hace clic en el botón de cerrar
    cerrarEditGrupo.addEventListener('click', function() {
        contenedorFormEditGrupo.classList.remove('activado');
    });
</script>
<script>
        const crearGrupo = document.querySelector('.crear-nuevo-grupo');
        const contenedorFormGrupo = document.querySelector('.container-form-addgrupo');
        const cerrarGrupo = document.getElementById('cerrar-form-grupo');

        crearGrupo.addEventListener('click', function() {
            contenedorFormGrupo.classList.add('activado');
        });
        cerrarGrupo.addEventListener('click', function() {
            contenedorFormGrupo.classList.remove('activado');
        }); 
    </script>
</body>
</html>